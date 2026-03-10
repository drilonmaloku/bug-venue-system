<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class ClientResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Individual
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            
            // Company
            'company_name' => $this->company_name,
            
            // Contact
            'email' => $this->email,
            'phone' => $this->phone_number ?? $this->phone,
            'phone_secondary' => $this->phone_secondary,
            
            // Address
            'address' => $this->address,
            'city' => $this->city,
            
            // Statistics
            'statistics' => [
                'total_reservations' => $this->reservations_count ?? 0,
                'total_payments' => $this->whenCounted('payments'),
            ],
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.clients.show', $this->id),
                'reservations' => route('api.v1.clients.reservations', $this->id),
            ],
        ];
    }
}
