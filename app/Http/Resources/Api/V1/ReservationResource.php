<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class ReservationResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            // Primary Identifiers
            'id' => $this->id,
            'uuid' => $this->uuid,
            'reservation_number' => $this->reservation_number ?? ('RES-' . $this->id),
            
            // Status
            'status' => [
                'code' => $this->status,
                'label' => $this->status_label ?? $this->getStatusLabel(),
                'class' => $this->status_class ?? $this->getStatusClass(),
            ],
            
            // Event Details
            'event_name' => $this->event_name ?? $this->description,
            'event_type' => [
                'code' => $this->reservation_type,
                'label' => $this->reservation_type_name ?? $this->getReservationTypeName(),
            ],
            
            // Dates
            'date' => $this->date,
            'start_date' => $this->date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            
            // Venue
            'venue' => $this->whenLoadedRelation('venue', new VenueResource($this->venue)),
            
            // Client
            'client' => $this->whenLoadedRelation('client', new ClientResource($this->client)),
            
            // Guest Information
            'guest_count' => [
                'adults' => $this->number_of_guests ?? 0,
                'children' => 0,
                'total' => $this->number_of_guests ?? 0,
            ],
            
            // Financial Summary
            'financial' => [
                'menu_price' => $this->formatPrice($this->menu_price),
                'current_payment' => $this->formatPrice($this->current_payment),
                'total_payment' => $this->formatPrice($this->total_payment),
                'balance' => $this->formatPrice(($this->total_payment ?? 0) - ($this->current_payment ?? 0)),
            ],
            
            // Assignment
            'assigned_to' => $this->whenLoadedRelation('user', new UserResource($this->user)),
            'manager_id' => $this->manager_id,
            
            // Menu & Decor
            'menu' => $this->whenLoadedRelation('menu', new MenuResource($this->menu)),
            'menu_id' => $this->menu_id,
            'decor' => $this->whenLoadedRelation('decor', new DecorResource($this->decor)),
            'decor_id' => $this->decor_id,
            
            // Notes & Planning
            'notes' => $this->when($request->has('include_notes'), $this->notes),
            'description' => $this->description,
            
            // Related Data Counts
            'counts' => [
                'payments' => $this->whenCounted('payments'),
                'invoices' => $this->whenCounted('invoices'),
                'guests' => $this->whenCounted('guests'),
                'discounts' => $this->whenCounted('discounts'),
                'comments' => $this->whenCounted('comments'),
            ],
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'self' => route('api.v1.reservations.show', $this->id),
            ],
        ];
    }

    private function getStatusLabel(): string
    {
        return match((int) $this->status) {
            1 => __('reservations.status.confirmed'),
            2 => __('reservations.status.not_confirmed'),
            3 => __('reservations.status.canceled'),
            default => 'Unknown',
        };
    }

    private function getStatusClass(): string
    {
        return match((int) $this->status) {
            1 => 'planned',
            2 => 'finished',
            3 => 'canceled',
            default => 'planned',
        };
    }

    private function getReservationTypeName(): string
    {
        $types = [
            1 => 'Ditë e Plotë',
            2 => 'Mëngjes',
            3 => 'Mbrëmje',
        ];
        return $types[$this->reservation_type] ?? 'Unknown';
    }
}
