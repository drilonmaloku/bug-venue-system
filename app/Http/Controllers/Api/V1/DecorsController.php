<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\DecorResource;
use App\Modules\Decors\Models\Decor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DecorsController extends BaseApiController
{
    /**
     * List all decors
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:100',
            'category' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Decor::withCount('reservations')
            ->orderBy('name');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        $decors = $query->paginate($filters['per_page'] ?? 15);

        return $this->paginatedResponse(
            DecorResource::collection($decors)
        );
    }

    /**
     * Create a new decor
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_id' => ['nullable', 'integer'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $decor = Decor::create($validated);

        return $this->createdResponse(
            new DecorResource($decor),
            'Decor created successfully'
        );
    }

    /**
     * Get a single decor
     */
    public function show(Decor $decor): JsonResponse
    {
        return $this->successResponse(
            new DecorResource($decor->loadCount('reservations'))
        );
    }

    /**
     * Update a decor
     */
    public function update(Request $request, Decor $decor): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'image_id' => ['sometimes', 'nullable', 'integer'],
            'category' => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        $decor->update($validated);

        return $this->successResponse(
            new DecorResource($decor->fresh()),
            'Decor updated successfully'
        );
    }

    /**
     * Delete a decor
     */
    public function destroy(Decor $decor): JsonResponse
    {
        $decor->delete();

        return $this->noContentResponse();
    }

    /**
     * Get decors by category
     */
    public function byCategory(string $category): JsonResponse
    {
        $decors = Decor::where('category', $category)
            ->orderBy('name')
            ->get();

        return $this->successResponse(
            DecorResource::collection($decors)
        );
    }
}
