<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\{
    StoreUserRequest,
    UpdateUserRequest
};
use App\Http\Resources\Api\V1\{
    UserResource,
    ReservationResource
};
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsersController extends BaseApiController
{
    /**
     * List all users
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'role' => 'nullable|string',
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = User::with('roles')->orderBy('first_name');

        if (!empty($filters['role'])) {
            $query->role($filters['role']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('last_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        $users = $query->paginate($filters['per_page'] ?? 15);

        return $this->paginatedResponse(
            UserResource::collection($users)
        );
    }

    /**
     * Create a new user
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        
        // Assign role
        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $this->createdResponse(
            new UserResource($user->load('roles')),
            'User created successfully'
        );
    }

    /**
     * Get a single user
     */
    public function show(User $user): JsonResponse
    {
        return $this->successResponse(
            new UserResource($user->load(['roles', 'permissions']))
        );
    }

    /**
     * Update a user
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        // Handle password update
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        // Update role if provided
        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return $this->successResponse(
            new UserResource($user->fresh()->load('roles')),
            'User updated successfully'
        );
    }

    /**
     * Delete a user
     */
    public function destroy(User $user): JsonResponse
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return $this->errorResponse(
                'Cannot delete your own account',
                422,
                'CANNOT_DELETE_SELF'
            );
        }

        $user->delete();

        return $this->noContentResponse();
    }

    /**
     * Get current user profile
     */
    public function profile(Request $request): JsonResponse
    {
        return $this->successResponse(
            new UserResource($request->user()->load(['roles', 'permissions']))
        );
    }

    /**
     * Update current user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'language' => ['sometimes', 'nullable', 'string', 'in:en,al'],
        ]);

        $user->update($validated);

        return $this->successResponse(
            new UserResource($user->fresh()->load('roles')),
            'Profile updated successfully'
        );
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return $this->errorResponse(
                'Current password is incorrect',
                422,
                'INVALID_PASSWORD'
            );
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return $this->successResponse(null, 'Password changed successfully');
    }

    /**
     * Get user reservations
     */
    public function reservations(User $user): JsonResponse
    {
        $reservations = $user->reservations()
            ->with(['client', 'venue'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            ReservationResource::collection($reservations)
        );
    }

    /**
     * Get user permissions
     */
    public function permissions(User $user): JsonResponse
    {
        return $this->successResponse([
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}
