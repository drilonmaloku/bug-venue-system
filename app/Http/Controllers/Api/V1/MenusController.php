<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\MenuResource;
use App\Modules\Menus\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenusController extends BaseApiController
{
    /**
     * List all menus
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Menu::orderBy('name');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        $menus = $query->paginate($filters['per_page'] ?? 15);

        return $this->paginatedResponse(
            MenuResource::collection($menus)
        );
    }

    /**
     * Create a new menu
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $menu = Menu::create($validated);

        return $this->createdResponse(
            new MenuResource($menu),
            'Menu created successfully'
        );
    }

    /**
     * Get a single menu
     */
    public function show(Menu $menu): JsonResponse
    {
        return $this->successResponse(
            new MenuResource($menu)
        );
    }

    /**
     * Update a menu
     */
    public function update(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'cost' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'category' => ['sometimes', 'nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $menu->update($validated);

        return $this->successResponse(
            new MenuResource($menu->fresh()),
            'Menu updated successfully'
        );
    }

    /**
     * Delete a menu
     */
    public function destroy(Menu $menu): JsonResponse
    {
        $menu->delete();

        return $this->noContentResponse();
    }
}
