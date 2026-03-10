<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class MenuResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Basic Info
            'name' => $this->name,
            'description' => $this->description,
            
            // Pricing
            'price' => $this->formatPrice($this->price),
            'cost' => $this->formatPrice($this->cost),
            
            // Category
            'category' => $this->category,
            
            // Status
            'is_active' => $this->is_active ?? true,
            
            // Location
            'location_id' => $this->location_id,
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.menus.show', $this->id),
            ],
        ];
    }
}
