<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class ExpenseResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Basic Info
            'title' => $this->title ?? $this->name,
            'description' => $this->description,
            
            // Amount
            'amount' => $this->formatPrice($this->price ?? $this->amount),
            
            // Category
            'category' => $this->category,
            
            // Date
            'date' => $this->date,
            
            // Relations
            'user_id' => $this->user_id,
            'user' => $this->whenLoadedRelation('user', new UserResource($this->user)),
            
            'reservation_id' => $this->reservation_id,
            
            // Location
            'location_id' => $this->location_id,
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.expenses.show', $this->id),
            ],
        ];
    }
}
