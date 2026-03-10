<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\StorePaymentRequest;
use App\Http\Resources\Api\V1\PaymentResource;
use App\Modules\Payments\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentsController extends BaseApiController
{
    /**
     * List all payments with filtering
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'client_id' => 'nullable|integer',
            'reservation_id' => 'nullable|integer',
            'payment_method' => 'nullable|integer|in:1,2',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Payment::with(['client', 'reservation'])
            ->orderBy('created_at', 'desc');

        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }
        if (!empty($filters['reservation_id'])) {
            $query->where('reservation_id', $filters['reservation_id']);
        }
        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }

        $payments = $query->paginate($filters['per_page'] ?? 15);

        return $this->paginatedResponse(
            PaymentResource::collection($payments)
        );
    }

    /**
     * Create a new payment
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = Payment::create($request->validated());
        
        // Update reservation totals if applicable
        if ($payment->reservation_id) {
            $payment->reservation->updateTotalData();
        }

        return $this->createdResponse(
            new PaymentResource($payment->load(['client', 'reservation'])),
            'Payment created successfully'
        );
    }

    /**
     * Get a single payment
     */
    public function show(Payment $payment): JsonResponse
    {
        return $this->successResponse(
            new PaymentResource($payment->load(['client', 'reservation']))
        );
    }

    /**
     * Update a payment
     */
    public function update(Request $request, Payment $payment): JsonResponse
    {
        $validated = $request->validate([
            'value' => ['sometimes', 'numeric', 'min:0.01'],
            'amount' => ['sometimes', 'numeric', 'min:0.01'],
            'payment_method' => ['sometimes', 'in:1,2'],
            'reference_number' => ['sometimes', 'nullable', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'date' => ['sometimes', 'nullable', 'date'],
        ]);

        $payment->update($validated);
        
        // Update reservation totals if applicable
        if ($payment->reservation_id) {
            $payment->reservation->updateTotalData();
        }

        return $this->successResponse(
            new PaymentResource($payment->fresh()->load(['client', 'reservation'])),
            'Payment updated successfully'
        );
    }

    /**
     * Delete a payment
     */
    public function destroy(Payment $payment): JsonResponse
    {
        $reservation = $payment->reservation;
        
        $payment->delete();
        
        // Update reservation totals if applicable
        if ($reservation) {
            $reservation->updateTotalData();
        }

        return $this->noContentResponse();
    }

    /**
     * Get payment summary statistics
     */
    public function summary(Request $request): JsonResponse
    {
        $query = Payment::query();

        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $total = $query->sum('value');
        $cash = (clone $query)->where('payment_method', 1)->sum('value');
        $bank = (clone $query)->where('payment_method', 2)->sum('value');
        $count = $query->count();

        return $this->successResponse([
            'total' => $total,
            'cash' => $cash,
            'bank' => $bank,
            'count' => $count,
        ]);
    }

    /**
     * Get overdue payments
     */
    public function overdue(Request $request): JsonResponse
    {
        // This would depend on your business logic
        // For now, return empty list
        return $this->successResponse([]);
    }
}
