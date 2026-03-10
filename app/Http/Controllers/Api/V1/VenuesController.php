<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\{
    VenueResource,
    ReservationResource
};
use App\Modules\Venues\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VenuesController extends BaseApiController
{
    /**
     * List all venues
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Venue::withCount('reservations')
            ->orderBy('name');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        $venues = $query->paginate($filters['per_page'] ?? 15);

        return $this->paginatedResponse(
            VenueResource::collection($venues)
        );
    }

    /**
     * Create a new venue
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:1000'],
            'has_av_system' => ['nullable', 'boolean'],
            'has_stage' => ['nullable', 'boolean'],
        ]);

        $venue = Venue::create($validated);

        return $this->createdResponse(
            new VenueResource($venue),
            'Venue created successfully'
        );
    }

    /**
     * Get a single venue
     */
    public function show(Venue $venue): JsonResponse
    {
        return $this->successResponse(
            new VenueResource($venue->loadCount('reservations'))
        );
    }

    /**
     * Update a venue
     */
    public function update(Request $request, Venue $venue): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'capacity' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'has_av_system' => ['sometimes', 'boolean'],
            'has_stage' => ['sometimes', 'boolean'],
        ]);

        $venue->update($validated);

        return $this->successResponse(
            new VenueResource($venue->fresh()),
            'Venue updated successfully'
        );
    }

    /**
     * Delete a venue
     */
    public function destroy(Venue $venue): JsonResponse
    {
        // Check if venue has reservations
        if ($venue->reservations()->count() > 0) {
            return $this->errorResponse(
                'Cannot delete venue with existing reservations',
                422,
                'VENUE_HAS_RESERVATIONS'
            );
        }

        $venue->delete();

        return $this->noContentResponse();
    }

    /**
     * Get venue availability
     */
    public function availability(Venue $venue, Request $request): JsonResponse
    {
        $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
        ]);

        $reservations = $venue->reservations()
            ->whereBetween('date', [$request->date_from, $request->date_to])
            ->get(['id', 'date', 'start_time', 'end_time', 'status']);

        return $this->successResponse([
            'venue_id' => $venue->id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'bookings' => $reservations,
        ]);
    }

    /**
     * Get venue reservations
     */
    public function reservations(Venue $venue): JsonResponse
    {
        $reservations = $venue->reservations()
            ->with(['client', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            ReservationResource::collection($reservations)
        );
    }

    /**
     * Get venue calendar events
     */
    public function calendar(Venue $venue, Request $request): JsonResponse
    {
        $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
        ]);

        $reservations = $venue->reservations()
            ->whereBetween('date', [$request->start, $request->end])
            ->with('client')
            ->get();

        $events = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'title' => $reservation->description ?? 'Reservation #' . $reservation->id,
                'start' => $reservation->date . ' ' . ($reservation->start_time ?? '00:00:00'),
                'end' => $reservation->date . ' ' . ($reservation->end_time ?? '23:59:59'),
                'status' => $reservation->status,
                'client' => $reservation->client ? $reservation->client->first_name . ' ' . $reservation->client->last_name : null,
            ];
        });

        return $this->successResponse($events);
    }
}
