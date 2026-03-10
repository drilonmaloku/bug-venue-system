<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class GuestResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Basic Info
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => trim($this->first_name . ' ' . $this->last_name),
            
            // Contact
            'email' => $this->email,
            'phone' => $this->phone,
            
            // Status
            'status' => $this->status,
            'rsvp_status' => $this->rsvp_status ?? $this->status,
            
            // Check-in
            'checked_in' => $this->checked_in ?? false,
            'checked_in_at' => $this->formatDate($this->checked_in_at),
            
            // Additional Info
            'dietary_requirements' => $this->dietary_requirements,
            'notes' => $this->notes,
            
            // Relations
            'reservation_id' => $this->reservation_id,
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.reservations.guests.show', [$this->reservation_id, $this->id]),
            ],
        ];
    }
}
