<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class VenueResource extends BaseResource
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
            'capacity' => $this->capacity,
            'description' => $this->description,
            
            // Features
            'has_av_system' => $this->has_av_system,
            'has_stage' => $this->has_stage,
            
            // Location
            'location_id' => $this->location_id,
            
            // Statistics
            'reservations_count' => $this->whenCounted('reservations'),
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.venues.show', $this->id),
            ],
        ];
    }
}
