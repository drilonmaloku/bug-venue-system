<?php

namespace App\Services\Api\V1;

use App\Modules\Payments\Models\Payment;
use App\Modules\Reservations\Models\Reservation;

class PaymentService
{
    /**
     * Create payment for reservation
     */
    public function createForReservation(Reservation $reservation, array $data): Payment
    {
        $data['reservation_id'] = $reservation->id;
        $data['client_id'] = $reservation->client_id;

        // Handle value/amount field mapping
        if (isset($data['amount']) && !isset($data['value'])) {
            $data['value'] = $data['amount'];
        }

        $payment = Payment::create($data);

        // Update reservation totals
        $reservation->updateTotalData();

        return $payment;
    }

    /**
     * Update payment
     */
    public function update(Payment $payment, array $data): Payment
    {
        $payment->update($data);

        // Update reservation totals if linked
        if ($payment->reservation) {
            $payment->reservation->updateTotalData();
        }

        return $payment->fresh();
    }

    /**
     * Delete payment
     */
    public function delete(Payment $payment): bool
    {
        $reservation = $payment->reservation;
        
        $result = $payment->delete();

        // Update reservation totals if linked
        if ($reservation) {
            $reservation->updateTotalData();
        }

        return $result;
    }

    /**
     * Get payment summary
     */
    public function getSummary(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $query = Payment::query();

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        return [
            'total' => $query->sum('value'),
            'cash' => (clone $query)->where('payment_method', 1)->sum('value'),
            'bank' => (clone $query)->where('payment_method', 2)->sum('value'),
            'count' => $query->count(),
        ];
    }
}
