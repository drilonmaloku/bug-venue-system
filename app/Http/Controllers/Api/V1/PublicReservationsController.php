<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\GuestResource;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Models\ReservationGuest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicReservationsController extends BaseApiController
{
    /**
     * Get reservation by UUID (public)
     */
    public function show(string $uuid): JsonResponse
    {
        $reservation = Reservation::where('uuid', $uuid)
            ->with(['client', 'venue'])
            ->firstOrFail();

        return $this->successResponse([
            'id' => $reservation->id,
            'uuid' => $reservation->uuid,
            'event_name' => $reservation->description,
            'date' => $reservation->date,
            'venue' => $reservation->venue?->name,
            'client_name' => $reservation->client ? 
                $reservation->client->first_name . ' ' . $reservation->client->last_name : null,
        ]);
    }

    /**
     * List guests for a reservation (public)
     */
    public function listGuests(string $uuid): JsonResponse
    {
        $reservation = Reservation::where('uuid', $uuid)->firstOrFail();

        $guests = $reservation->guests()->paginate(50);

        return $this->paginatedResponse(
            GuestResource::collection($guests)
        );
    }

    /**
     * Add guest to reservation (public)
     */
    public function addGuest(Request $request, string $uuid): JsonResponse
    {
        $reservation = Reservation::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'dietary_requirements' => ['nullable', 'string', 'max:500'],
        ]);

        $guest = $reservation->guests()->create($validated);

        return $this->createdResponse(
            new GuestResource($guest),
            'Guest added successfully'
        );
    }

    /**
     * Update guest (public)
     */
    public function updateGuest(Request $request, string $uuid, int $guestId): JsonResponse
    {
        $reservation = Reservation::where('uuid', $uuid)->firstOrFail();
        $guest = $reservation->guests()->findOrFail($guestId);

        $validated = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'dietary_requirements' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        $guest->update($validated);

        return $this->successResponse(
            new GuestResource($guest->fresh()),
            'Guest updated successfully'
        );
    }

    /**
     * Delete guest (public)
     */
    public function deleteGuest(string $uuid, int $guestId): JsonResponse
    {
        $reservation = Reservation::where('uuid', $uuid)->firstOrFail();
        $guest = $reservation->guests()->findOrFail($guestId);
        $guest->delete();

        return $this->noContentResponse();
    }

    /**
     * Update guest status (RSVP)
     */
    public function updateGuestStatus(Request $request, string $uuid, int $guestId): JsonResponse
    {
        $reservation = Reservation::where('uuid', $uuid)->firstOrFail();
        $guest = $reservation->guests()->findOrFail($guestId);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,declined,attended,no_show'],
        ]);

        $guest->update(['status' => $validated['status']]);

        return $this->successResponse(
            new GuestResource($guest->fresh()),
            'Guest status updated'
        );
    }
}
