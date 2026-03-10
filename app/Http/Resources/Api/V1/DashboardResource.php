<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class DashboardResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'stats' => [
                'total_reservations' => $this['total_reservations'] ?? 0,
                'confirmed_reservations' => $this['confirmed_reservations'] ?? 0,
                'pending_reservations' => $this['pending_reservations'] ?? 0,
                'cancelled_reservations' => $this['cancelled_reservations'] ?? 0,
                'total_revenue' => $this->formatPrice($this['total_revenue'] ?? 0),
                'total_payments' => $this->formatPrice($this['total_payments'] ?? 0),
                'outstanding_balance' => $this->formatPrice($this['outstanding_balance'] ?? 0),
            ],
            
            'today' => [
                'reservations_count' => $this['today_reservations'] ?? 0,
                'guests_count' => $this['today_guests'] ?? 0,
                'revenue' => $this->formatPrice($this['today_revenue'] ?? 0),
            ],
            
            'upcoming' => ReservationResource::collection($this['upcoming'] ?? []),
            
            'recent_activity' => $this['recent_activity'] ?? [],
        ];
    }
}
