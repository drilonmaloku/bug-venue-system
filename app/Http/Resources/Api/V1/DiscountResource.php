<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class DiscountResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Discount Info
            'description' => $this->description,
            'reason' => $this->reason,
            
            // Amount
            'amount' => $this->formatPrice($this->amount),
            
            // Type
            'type' => $this->type,
            'percentage' => $this->percentage,
            
            // Relations
            'reservation_id' => $this->reservation_id,
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.reservations.discounts.show', [$this->reservation_id, $this->id]),
            ],
        ];
    }
}
