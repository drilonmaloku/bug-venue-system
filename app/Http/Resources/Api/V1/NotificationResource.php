<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class NotificationResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Content
            'title' => $this->data['title'] ?? null,
            'message' => $this->data['message'] ?? null,
            'type' => $this->data['type'] ?? 'general',
            
            // Status
            'read' => !is_null($this->read_at),
            'read_at' => $this->formatDate($this->read_at),
            
            // Data
            'data' => $this->data,
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            
            // Links
            'links' => [
                'mark_as_read' => route('api.v1.notifications.read', $this->id),
            ],
        ];
    }
}
