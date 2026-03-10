<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\{
    StoreReservationRequest,
    UpdateReservationRequest,
    CheckAvailabilityRequest,
    StorePaymentRequest,
    StoreInvoiceRequest,
    StoreDiscountRequest,
    StoreCommentRequest,
    AddGuestRequest,
    UpdateGuestStatusRequest,
    UpdateGuestCheckinRequest
};
use App\Http\Resources\Api\V1\{
    ReservationResource,
    PaymentResource,
    InvoiceResource,
    DiscountResource,
    CommentResource,
    GuestResource,
    UserResource,
    DocumentResource
};
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Models\ReservationGuest;
use App\Modules\Clients\Models\Client;
use App\Services\Api\V1\ReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationsController extends BaseApiController
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    /**
     * List all reservations with filtering
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'status' => 'nullable|integer|in:1,2,3',
            'venue_id' => 'nullable|integer',
            'client_id' => 'nullable|integer',
            'manager_id' => 'nullable|integer',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Reservation::with(['client', 'venue', 'user'])
            ->orderBy('date', 'desc');

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['venue_id'])) {
            $query->where('venue_id', $filters['venue_id']);
        }
        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }
        if (!empty($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('description', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('reservation_number', 'like', '%' . $filters['search'] . '%');
            });
        }

        $reservations = $query->paginate($filters['per_page'] ?? 15);

        return $this->paginatedResponse(
            ReservationResource::collection($reservations)
        );
    }

    /**
     * Create a new reservation
     */
    public function store(StoreReservationRequest $request): JsonResponse
    {
        $reservation = $this->reservationService->create($request->validated());

        return $this->createdResponse(
            new ReservationResource($reservation->load(['client', 'venue', 'user'])),
            'Reservation created successfully'
        );
    }

    /**
     * Get a single reservation
     */
    public function show(Reservation $reservation): JsonResponse
    {
        return $this->successResponse(
            new ReservationResource($reservation->load([
                'client', 'venue', 'user', 'menu', 'decor',
                'payments', 'invoices', 'guests', 'comments'
            ]))
        );
    }

    /**
     * Update a reservation
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation): JsonResponse
    {
        $reservation = $this->reservationService->update($reservation, $request->validated());

        return $this->successResponse(
            new ReservationResource($reservation->fresh()->load(['client', 'venue', 'user'])),
            'Reservation updated successfully'
        );
    }

    /**
     * Delete a reservation
     */
    public function destroy(Reservation $reservation): JsonResponse
    {
        $this->reservationService->delete($reservation);

        return $this->noContentResponse();
    }

    /**
     * Check venue availability
     */
    public function checkAvailability(CheckAvailabilityRequest $request): JsonResponse
    {
        $result = $this->reservationService->checkAvailability($request->validated());

        return $this->successResponse($result);
    }

    //=======================================================================
    // PAYMENTS
    //=======================================================================

    /**
     * List payments for reservation
     */
    public function listPayments(Reservation $reservation): JsonResponse
    {
        return $this->successResponse([
            'payments' => PaymentResource::collection($reservation->payments),
            'summary' => [
                'total' => $reservation->total_payment,
                'paid' => $reservation->current_payment,
                'balance' => ($reservation->total_payment ?? 0) - ($reservation->current_payment ?? 0),
            ],
        ]);
    }

    /**
     * Add payment to reservation
     */
    public function storePayment(StorePaymentRequest $request, Reservation $reservation): JsonResponse
    {
        $payment = $this->reservationService->addPayment($reservation, $request->validated());

        return $this->createdResponse(
            new PaymentResource($payment),
            'Payment added successfully'
        );
    }

    //=======================================================================
    // INVOICES
    //=======================================================================

    /**
     * List invoices for reservation
     */
    public function listInvoices(Reservation $reservation): JsonResponse
    {
        return $this->successResponse([
            'invoices' => InvoiceResource::collection($reservation->invoices),
            'total' => $reservation->totalInvoiceAmount,
        ]);
    }

    /**
     * Create invoice for reservation
     */
    public function storeInvoice(StoreInvoiceRequest $request, Reservation $reservation): JsonResponse
    {
        $invoice = $this->reservationService->addInvoice($reservation, $request->validated());

        return $this->createdResponse(
            new InvoiceResource($invoice),
            'Invoice created successfully'
        );
    }

    //=======================================================================
    // DISCOUNTS
    //=======================================================================

    /**
     * List discounts for reservation
     */
    public function listDiscounts(Reservation $reservation): JsonResponse
    {
        return $this->successResponse([
            'discounts' => DiscountResource::collection($reservation->discounts),
            'total' => $reservation->totalDiscountAmount,
        ]);
    }

    /**
     * Add discount to reservation
     */
    public function storeDiscount(StoreDiscountRequest $request, Reservation $reservation): JsonResponse
    {
        $discount = $this->reservationService->addDiscount($reservation, $request->validated());

        return $this->createdResponse(
            new DiscountResource($discount),
            'Discount applied successfully'
        );
    }

    //=======================================================================
    // COMMENTS
    //=======================================================================

    /**
     * List comments for reservation
     */
    public function listComments(Reservation $reservation): JsonResponse
    {
        return $this->successResponse(
            CommentResource::collection($reservation->comments()->with('user')->get())
        );
    }

    /**
     * Add comment to reservation
     */
    public function storeComment(StoreCommentRequest $request, Reservation $reservation): JsonResponse
    {
        $comment = $this->reservationService->addComment(
            $reservation,
            $request->validated(),
            $request->user()
        );

        return $this->createdResponse(
            new CommentResource($comment->load('user')),
            'Comment added successfully'
        );
    }

    /**
     * Delete comment from reservation
     */
    public function deleteComment(Reservation $reservation, int $commentId): JsonResponse
    {
        $this->reservationService->deleteComment($reservation, $commentId, request()->user());

        return $this->noContentResponse();
    }

    //=======================================================================
    // STAFF
    //=======================================================================

    /**
     * List staff for reservation
     */
    public function listStaff(Reservation $reservation): JsonResponse
    {
        return $this->successResponse(
            UserResource::collection($reservation->reservationStaff()->with('user')->get()->pluck('user'))
        );
    }

    /**
     * Add staff to reservation
     */
    public function addStaff(Request $request, Reservation $reservation): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['nullable', 'string', 'max:100'],
        ]);

        $staff = $this->reservationService->addStaff($reservation, $validated);

        return $this->createdResponse(
            new UserResource($staff),
            'Staff member added successfully'
        );
    }

    /**
     * Remove staff from reservation
     */
    public function deleteStaff(Reservation $reservation, int $userId): JsonResponse
    {
        $this->reservationService->removeStaff($reservation, $userId);

        return $this->noContentResponse();
    }

    //=======================================================================
    // GUESTS
    //=======================================================================

    /**
     * List guests for reservation
     */
    public function listGuests(Reservation $reservation, Request $request): JsonResponse
    {
        $guests = $reservation->guests()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate($request->per_page ?? 50);

        return $this->paginatedResponse(GuestResource::collection($guests));
    }

    /**
     * Add guest to reservation
     */
    public function addGuest(AddGuestRequest $request, Reservation $reservation): JsonResponse
    {
        $guest = $this->reservationService->addGuest($reservation, $request->validated());

        return $this->createdResponse(
            new GuestResource($guest),
            'Guest added successfully'
        );
    }

    /**
     * Update guest
     */
    public function updateGuest(Request $request, Reservation $reservation, int $guestId): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|nullable|email|max:255',
            'phone' => 'sometimes|nullable|string|max:50',
            'dietary_requirements' => 'sometimes|nullable|string|max:500',
        ]);

        $guest = $this->reservationService->updateGuest($reservation, $guestId, $validated);

        return $this->successResponse(
            new GuestResource($guest),
            'Guest updated successfully'
        );
    }

    /**
     * Delete guest
     */
    public function deleteGuest(Reservation $reservation, int $guestId): JsonResponse
    {
        $this->reservationService->removeGuest($reservation, $guestId);

        return $this->noContentResponse();
    }

    /**
     * Update guest status
     */
    public function updateGuestStatus(UpdateGuestStatusRequest $request, Reservation $reservation, int $guestId): JsonResponse
    {
        $guest = $this->reservationService->updateGuestStatus(
            $reservation,
            $guestId,
            $request->validated()['status']
        );

        return $this->successResponse(
            new GuestResource($guest),
            'Guest status updated'
        );
    }

    /**
     * Update guest check-in
     */
    public function updateGuestCheckin(UpdateGuestCheckinRequest $request, Reservation $reservation, int $guestId): JsonResponse
    {
        $guest = $this->reservationService->updateGuestCheckin(
            $reservation,
            $guestId,
            $request->validated()
        );

        return $this->successResponse(
            new GuestResource($guest),
            'Guest check-in updated'
        );
    }

    //=======================================================================
    // CONTRACT
    //=======================================================================

    /**
     * Generate contract for reservation
     */
    public function generateContract(Request $request, Reservation $reservation): JsonResponse
    {
        $contract = $this->reservationService->generateContract($reservation, $request->all());

        return $this->createdResponse([
            'contract_id' => $contract->id ?? null,
            'download_url' => route('api.v1.reservations.contract.download', [$reservation->id]),
        ], 'Contract generated successfully');
    }

    /**
     * Download contract
     */
    public function downloadContract(Reservation $reservation)
    {
        // This would typically return a PDF download
        // For now, return a placeholder response
        return response()->json([
            'message' => 'Contract download endpoint - implement PDF generation'
        ]);
    }

    //=======================================================================
    // DOCUMENTS
    //=======================================================================

    /**
     * List documents for reservation
     */
    public function listDocuments(Reservation $reservation): JsonResponse
    {
        return $this->successResponse(
            DocumentResource::collection($reservation->documents ?? [])
        );
    }

    //=======================================================================
    // STATUS OPERATIONS
    //=======================================================================

    /**
     * Update reservation status
     */
    public function updateStatus(Request $request, Reservation $reservation): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:1,2,3',
            'reason' => 'nullable|string|max:500',
        ]);

        $reservation = $this->reservationService->updateStatus(
            $reservation,
            $validated['status'],
            $validated['reason'] ?? null
        );

        return $this->successResponse(
            new ReservationResource($reservation),
            'Status updated successfully'
        );
    }

    /**
     * Confirm reservation
     */
    public function confirm(Reservation $reservation): JsonResponse
    {
        $reservation = $this->reservationService->confirm($reservation);

        return $this->successResponse(
            new ReservationResource($reservation),
            'Reservation confirmed successfully'
        );
    }

    /**
     * Cancel reservation
     */
    public function cancel(Request $request, Reservation $reservation): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $reservation = $this->reservationService->cancel(
            $reservation,
            $validated['reason']
        );

        return $this->successResponse(
            new ReservationResource($reservation),
            'Reservation cancelled successfully'
        );
    }
}
