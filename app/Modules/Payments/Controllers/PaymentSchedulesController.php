<?php

namespace App\Modules\Payments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\Models\PaymentSchedule;
use App\Modules\Payments\Models\PaymentScheduleTemplate;
use App\Modules\Payments\Services\FinancialDashboardService;
use App\Modules\Payments\Services\PaymentScheduleService;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentSchedulesController extends Controller
{
    protected PaymentScheduleService $scheduleService;
    protected FinancialDashboardService $dashboardService;

    public function __construct(
        PaymentScheduleService $scheduleService,
        FinancialDashboardService $dashboardService
    ) {
        $this->scheduleService = $scheduleService;
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display a listing of payment schedules.
     */
    public function index(Request $request)
    {
        $query = PaymentSchedule::with(['client', 'reservation', 'installments']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }

        if ($request->filled('reservation_id')) {
            $query->where('reservation_id', $request->input('reservation_id'));
        }

        $schedules = $query->orderBy('created_at', 'desc')->paginate(25);

        return response()->json([
            'data' => $schedules,
        ]);
    }

    /**
     * Display the specified payment schedule.
     */
    public function show(int $id): JsonResponse
    {
        $schedule = PaymentSchedule::with([
            'client',
            'reservation',
            'installments.payments',
            'latePaymentAlerts',
            'creator',
            'approver',
        ])->findOrFail($id);

        return response()->json([
            'data' => $schedule,
        ]);
    }

    /**
     * Get payment schedule by reservation ID.
     */
    public function byReservation(int $reservationId): JsonResponse
    {
        $schedule = $this->scheduleService->getByReservationId($reservationId);

        if (!$schedule) {
            return response()->json([
                'message' => 'Payment schedule not found for this reservation',
            ], 404);
        }

        return response()->json([
            'data' => $schedule,
        ]);
    }

    /**
     * Create a new payment schedule from template.
     */
    public function storeFromTemplate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'template_type' => 'required|string|in:standard_3_tier,equal_split,full_upfront,corporate_60_day,monthly_6,custom',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        $reservation = Reservation::findOrFail($validated['reservation_id']);

        try {
            $schedule = $this->scheduleService->createFromTemplate(
                $reservation,
                $validated['template_type'],
                $validated['total_amount'] ?? null,
                auth()->id()
            );

            return response()->json([
                'message' => 'Payment schedule created successfully',
                'data' => $schedule,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create payment schedule',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a custom payment schedule.
     */
    public function storeCustom(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'installments' => 'required|array|min:1',
            'installments.*.name' => 'required|string',
            'installments.*.type' => 'required|string|in:deposit,interim,final,milestone,custom',
            'installments.*.percentage' => 'required|numeric|min:0|max:100',
            'installments.*.due_date' => 'required|date',
            'installments.*.description' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        $reservation = Reservation::findOrFail($validated['reservation_id']);

        try {
            $schedule = $this->scheduleService->createCustom(
                $reservation,
                $validated['installments'],
                $validated['total_amount'] ?? null,
                auth()->id()
            );

            return response()->json([
                'message' => 'Custom payment schedule created successfully',
                'data' => $schedule,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create payment schedule',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Activate a payment schedule.
     */
    public function activate(int $id): JsonResponse
    {
        try {
            $schedule = $this->scheduleService->activate($id, auth()->id());

            return response()->json([
                'message' => 'Payment schedule activated successfully',
                'data' => $schedule,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to activate payment schedule',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Record a payment against the schedule.
     */
    public function recordPayment(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'installment_id' => 'nullable|exists:payment_installments,id',
            'payment_method' => 'nullable|integer',
            'transaction_reference' => 'nullable|string',
            'notes' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $schedule = PaymentSchedule::findOrFail($id);

        try {
            $result = $this->scheduleService->recordPayment(
                $schedule,
                $validated['amount'],
                $validated['installment_id'] ?? null,
                [
                    'payment_method' => $validated['payment_method'] ?? 1,
                    'transaction_reference' => $validated['transaction_reference'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'date' => $validated['date'] ?? now()->format('Y-m-d'),
                ]
            );

            return response()->json([
                'message' => 'Payment recorded successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to record payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a payment schedule.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->scheduleService->delete($id);

            return response()->json([
                'message' => 'Payment schedule deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete payment schedule',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get available templates.
     */
    public function templates(): JsonResponse
    {
        $templates = PaymentScheduleTemplate::active()->get();

        return response()->json([
            'data' => $templates,
        ]);
    }

    /**
     * Get upcoming payments.
     */
    public function upcoming(Request $request): JsonResponse
    {
        $days = $request->input('days', 14);
        $clientId = $request->input('client_id');

        $upcoming = $this->scheduleService->getUpcomingInstallments($days, $clientId);

        return response()->json([
            'data' => $upcoming,
        ]);
    }

    /**
     * Get dashboard data.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $clientId = $request->input('client_id');
        $data = $this->dashboardService->getDashboardData($clientId);

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get client statistics.
     */
    public function clientStats(int $clientId): JsonResponse
    {
        $stats = $this->scheduleService->getClientStatistics($clientId);

        return response()->json([
            'data' => $stats,
        ]);
    }
}
