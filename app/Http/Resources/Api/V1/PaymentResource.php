<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class PaymentResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Amount
            'amount' => $this->formatPrice($this->value ?? $this->amount),
            
            // Method
            'method' => [
                'code' => $this->payment_method ?? 1,
                'label' => $this->payment_method_label ?? 'Cash',
            ],
            
            // Status
            'status' => $this->status,
            
            // Reference
            'reference_number' => $this->reference_number,
            'notes' => $this->notes,
            
            // Relations
            'reservation_id' => $this->reservation_id,
            'reservation' => $this->whenLoadedRelation('reservation', new ReservationResource($this->reservation)),
            
            'client_id' => $this->client_id,
            'client' => $this->whenLoadedRelation('client', new ClientResource($this->client)),
            
            // Timestamps
            'paid_at' => $this->formatDate($this->date ?? $this->paid_at),
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.payments.show', $this->id),
            ],
        ];
    }
}
