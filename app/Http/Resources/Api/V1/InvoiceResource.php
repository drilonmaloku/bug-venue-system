<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class InvoiceResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Invoice Info
            'invoice_number' => $this->invoice_number,
            'description' => $this->description,
            
            // Amount
            'amount' => $this->formatPrice($this->amount),
            
            // Status
            'status' => $this->status,
            
            // Relations
            'reservation_id' => $this->reservation_id,
            'reservation' => $this->whenLoadedRelation('reservation', new ReservationResource($this->reservation)),
            
            // Timestamps
            'issued_at' => $this->formatDate($this->issued_at ?? $this->created_at),
            'due_at' => $this->formatDate($this->due_at),
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.reservations.invoices.show', [$this->reservation_id, $this->id]),
            ],
        ];
    }
}
