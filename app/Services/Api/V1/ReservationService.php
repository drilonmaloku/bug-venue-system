<?php

namespace App\Services\Api\V1;

use App\Models\User;
use App\Modules\Clients\Models\Client;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Models\ReservationComment;
use App\Modules\Reservations\Models\ReservationStaff;
use App\Modules\Payments\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    /**
     * Create a new reservation
     */
    public function create(array $data): Reservation
    {
        return DB::transaction(function () use ($data) {
            // Handle new client creation
            if (isset($data['client'])) {
                $client = Client::create($data['client']);
                $data['client_id'] = $client->id;
                unset($data['client']);
            }

            // Generate reservation number if not provided
            if (empty($data['reservation_number'])) {
                $data['reservation_number'] = $this->generateReservationNumber();
            }

            // Set default status
            if (empty($data['status'])) {
                $data['status'] = 2; // On hold/not confirmed
            }

            // Set location
            if (empty($data['location_id']) && auth()->check()) {
                $data['location_id'] = auth()->user()->getCurrentLocationId();
            }

            // Create reservation
            $reservation = Reservation::create($data);

            // Load relationships
            $reservation->load(['client', 'venue', 'user']);

            return $reservation;
        });
    }

    /**
     * Update a reservation
     */
    public function update(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);
        $reservation->refresh();

        return $reservation;
    }

    /**
     * Delete a reservation
     */
    public function delete(Reservation $reservation): bool
    {
        return DB::transaction(function () use ($reservation) {
            return $reservation->delete();
        });
    }

    /**
     * Check venue availability
     */
    public function checkAvailability(array $data): array
    {
        $venueId = $data['venue_id'];
        $date = $data['date'];
        $excludeId = $data['exclude_reservation_id'] ?? null;

        $query = Reservation::where('venue_id', $venueId)
            ->where('date', $date)
            ->where('status', '!=', 3); // Exclude cancelled

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $conflicts = $query->get();

        return [
            'available' => $conflicts->isEmpty(),
            'conflicts' => $conflicts,
            'venue_id' => $venueId,
            'date' => $date,
        ];
    }

    /**
     * Add payment to reservation
     */
    public function addPayment(Reservation $reservation, array $data): Payment
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
     * Add invoice to reservation
     */
    public function addInvoice(Reservation $reservation, array $data): Model
    {
        $data['reservation_id'] = $reservation->id;

        $invoice = $reservation->invoices()->create($data);

        // Update reservation totals
        $reservation->updateTotalData();

        return $invoice;
    }

    /**
     * Add discount to reservation
     */
    public function addDiscount(Reservation $reservation, array $data): Model
    {
        $data['reservation_id'] = $reservation->id;

        $discount = $reservation->discounts()->create($data);

        // Update reservation totals
        $reservation->updateTotalData();

        return $discount;
    }

    /**
     * Add comment to reservation
     */
    public function addComment(Reservation $reservation, array $data, User $user): Model
    {
        // Handle both 'comment' and 'content' field names
        $commentContent = $data['comment'] ?? $data['content'] ?? '';
        
        return $reservation->comments()->create([
            'comment' => $commentContent,
            'user_id' => $user->id,
            'location_id' => $user->getCurrentLocationId(),
        ]);
    }

    /**
     * Delete comment
     */
    public function deleteComment(Reservation $reservation, int $commentId, User $user): bool
    {
        $comment = $reservation->comments()->findOrFail($commentId);

        // Only author or admin can delete
        if ($comment->user_id !== $user->id && !$user->hasRole(['admin', 'super-admin', 'system-admin'])) {
            abort(403, 'Unauthorized to delete this comment');
        }

        return $comment->delete();
    }

    /**
     * Add staff to reservation
     */
    public function addStaff(Reservation $reservation, array $data): User
    {
        $reservation->reservationStaff()->create([
            'user_id' => $data['user_id'],
            'role' => $data['role'] ?? null,
        ]);

        return User::find($data['user_id']);
    }

    /**
     * Remove staff from reservation
     */
    public function removeStaff(Reservation $reservation, int $userId): bool
    {
        return $reservation->reservationStaff()
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * Add guest to reservation
     */
    public function addGuest(Reservation $reservation, array $data): Model
    {
        return $reservation->guests()->create($data);
    }

    /**
     * Update guest
     */
    public function updateGuest(Reservation $reservation, int $guestId, array $data): Model
    {
        $guest = $reservation->guests()->findOrFail($guestId);
        $guest->update($data);
        return $guest->fresh();
    }

    /**
     * Remove guest
     */
    public function removeGuest(Reservation $reservation, int $guestId): bool
    {
        $guest = $reservation->guests()->findOrFail($guestId);
        return $guest->delete();
    }

    /**
     * Update guest status
     */
    public function updateGuestStatus(Reservation $reservation, int $guestId, string $status): Model
    {
        $guest = $reservation->guests()->findOrFail($guestId);
        $guest->update(['status' => $status]);
        return $guest->fresh();
    }

    /**
     * Update guest check-in
     */
    public function updateGuestCheckin(Reservation $reservation, int $guestId, array $data): Model
    {
        $guest = $reservation->guests()->findOrFail($guestId);
        $guest->update([
            'checked_in' => $data['checked_in'],
            'checked_in_at' => $data['checked_in'] ? ($data['checked_in_at'] ?? now()) : null,
        ]);
        return $guest->fresh();
    }

    /**
     * Generate contract
     */
    public function generateContract(Reservation $reservation, array $data): object
    {
        // Placeholder for contract generation logic
        // This would typically generate a PDF or document
        return (object) [
            'id' => uniqid(),
            'reservation_id' => $reservation->id,
            'generated_at' => now(),
        ];
    }

    /**
     * Update reservation status
     */
    public function updateStatus(Reservation $reservation, int $status, ?string $reason = null): Reservation
    {
        $updateData = ['status' => $status];

        if ($status === 1) { // Confirmed
            $updateData['confirmed_at'] = now();
        } elseif ($status === 3) { // Cancelled
            $updateData['cancelled_at'] = now();
            $updateData['cancellation_reason'] = $reason;
        }

        $reservation->update($updateData);
        $reservation->refresh();

        return $reservation;
    }

    /**
     * Confirm reservation
     */
    public function confirm(Reservation $reservation): Reservation
    {
        return $this->updateStatus($reservation, 1);
    }

    /**
     * Cancel reservation
     */
    public function cancel(Reservation $reservation, string $reason): Reservation
    {
        return $this->updateStatus($reservation, 3, $reason);
    }

    /**
     * Generate unique reservation number
     */
    protected function generateReservationNumber(): string
    {
        $prefix = 'RES';
        $year = now()->format('Y');
        $sequence = Reservation::whereYear('created_at', $year)->count() + 1;
        
        return sprintf('%s-%s-%05d', $prefix, $year, $sequence);
    }
}
