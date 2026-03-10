<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseApiController
{
    /**
     * Login user and create token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user is active
        if (method_exists($user, 'isActive') && !$user->isActive()) {
            return $this->errorResponse('Account is inactive or suspended.', 403, 'ACCOUNT_INACTIVE');
        }

        // Delete existing tokens for this device
        if ($request->device_id) {
            $user->tokens()->where('name', $request->device_id)->delete();
        }

        // Create token
        $token = $user->createToken(
            $request->device_id ?? 'api-token',
            ['*']
        );

        // Update last login
        if (in_array('last_login_at', $user->getFillable())) {
            $user->update(['last_login_at' => now()]);
        }

        return $this->successResponse([
            'user' => new UserResource($user->load('roles')),
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        return $this->successResponse([
            'user' => new UserResource($request->user()->load('roles', 'permissions')),
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        
        // Create new token
        $token = $user->createToken('api-token');

        return $this->successResponse([
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ], 'Token refreshed successfully');
    }
}
