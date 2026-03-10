<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Profile
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'initials' => substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1),
            
            // Contact
            'email' => $this->email,
            'phone' => $this->phone,
            'username' => $this->username,
            
            // Role & Status
            'role' => $this->whenLoaded('roles', function () {
                $role = $this->roles->first();
                return $role ? [
                    'code' => $role->name,
                    'label' => $role->name,
                ] : null;
            }),
            
            // Language
            'language' => $this->language,
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.users.show', $this->id),
            ],
        ];
    }
}
