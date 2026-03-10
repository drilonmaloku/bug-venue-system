<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class DecorResource extends BaseResource
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
            
            // Image
            'image_url' => $this->image_url,
            
            // Category
            'category' => $this->category,
            
            // Location
            'location_id' => $this->location_id,
            
            // Statistics
            'reservations_count' => $this->whenCounted('reservations'),
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.decors.show', $this->id),
            ],
        ];
    }
}
