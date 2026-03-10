<?php

namespace App\Modules\Payments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\Models\LatePaymentAlert;
use App\Modules\Payments\Services\LatePaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LatePaymentAlertsController extends Controller
{
    protected LatePaymentService $latePaymentService;

    public function __construct(LatePaymentService $latePaymentService)
    {
        $this->latePaymentService = $latePaymentService;
    }

    /**
     * Display a listing of late payment alerts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = LatePaymentAlert::with(['client', 'installment', 'paymentSchedule.reservation']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->input('severity'));
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }

        if ($request->filled('unresolved')) {
            $query->unresolved();
        }

        $alerts = $query->orderBy('created_at', 'desc')->paginate(25);

        return response()->json([
            'data' => $alerts,
        ]);
    }

    /**
     * Display the specified alert.
     */
    public function show(int $id): JsonResponse
    {
        $alert = LatePaymentAlert::with([
            'client',
            'installment',
            'paymentSchedule.reservation',
            'acknowledgedBy',
            'escalatedTo',
        ])->findOrFail($id);

        return response()->json([
            'data' => $alert,
        ]);
    }

    /**
     * Get active alerts.
     */
    public function active(Request $request): JsonResponse
    {
        $clientId = $request->input('client_id');
        $severity = $request->input('severity');

        $alerts = $this->latePaymentService->getActiveAlerts($clientId, $severity);

        return response()->json([
            'data' => $alerts,
        ]);
    }

    /**
     * Get high priority alerts.
     */
    public function highPriority(Request $request): JsonResponse
    {
        $clientId = $request->input('client_id');

        $alerts = $this->latePaymentService->getHighPriorityAlerts($clientId);

        return response()->json([
            'data' => $alerts,
        ]);
    }

    /**
     * Acknowledge an alert.
     */
    public function acknowledge(Request $request, int $id): JsonResponse
    {
        try {
            $alert = $this->latePaymentService->acknowledgeAlert($id, auth()->id());

            return response()->json([
                'message' => 'Alert acknowledged successfully',
                'data' => $alert,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to acknowledge alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resolve an alert.
     */
    public function resolve(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        try {
            $alert = $this->latePaymentService->resolveAlert($id, $validated['notes'] ?? null);

            return response()->json([
                'message' => 'Alert resolved successfully',
                'data' => $alert,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to resolve alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Escalate an alert.
     */
    public function escalate(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'escalated_to' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        try {
            $alert = $this->latePaymentService->escalateAlert(
                $id,
                $validated['escalated_to'],
                $validated['notes'] ?? null
            );

            return response()->json([
                'message' => 'Alert escalated successfully',
                'data' => $alert,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to escalate alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $clientId = $request->input('client_id');

        $stats = $this->latePaymentService->getStatistics($clientId);

        return response()->json([
            'data' => $stats,
        ]);
    }

    /**
     * Get overdue summary for dashboard.
     */
    public function summary(): JsonResponse
    {
        $summary = $this->latePaymentService->getOverdueSummary();

        return response()->json([
            'data' => $summary,
        ]);
    }

    /**
     * Run late payment check.
     */
    public function runCheck(): JsonResponse
    {
        try {
            $result = $this->latePaymentService->checkForLatePayments();

            return response()->json([
                'message' => 'Late payment check completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to run late payment check',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get alerts for a specific installment.
     */
    public function byInstallment(int $installmentId): JsonResponse
    {
        $alerts = LatePaymentAlert::with(['client'])
            ->where('installment_id', $installmentId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $alerts,
        ]);
    }
}
