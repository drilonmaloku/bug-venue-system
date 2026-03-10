<?php

namespace App\Modules\Payments\Services;

use App\Modules\Payments\Models\Installment;
use App\Modules\Payments\Models\LatePaymentAlert;
use App\Modules\Payments\Models\PaymentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LatePaymentService
{
    /**
     * Check for late payments and create/update alerts.
     */
    public function checkForLatePayments(): array
    {
        $today = now()->startOfDay();
        
        Log::info('Starting late payment check', ['date' => $today->toDateString()]);

        // Find all pending installments with due dates in the past
        $overdueInstallments = Installment::with(['paymentSchedule'])
            ->where('status', Installment::STATUS_PENDING)
            ->whereDate('due_date', '<', $today)
            ->whereHas('paymentSchedule', function ($q) {
                $q->where('status', PaymentSchedule::STATUS_ACTIVE);
            })
            ->get();

        $newlyOverdue = 0;
        $alertsCreated = 0;
        $alertsUpdated = 0;

        foreach ($overdueInstallments as $installment) {
            try {
                $daysOverdue = $this->calculateDaysOverdue($installment->due_date, $today);
                $gracePeriodDays = $installment->paymentSchedule->grace_period_days ?? 0;

                // Check if past grace period
                if ($daysOverdue > $gracePeriodDays) {
                    $this->markInstallmentOverdue($installment, $daysOverdue);

                    // Check if alert already exists
                    $existingAlert = LatePaymentAlert::where('installment_id', $installment->id)
                        ->whereIn('status', [LatePaymentAlert::STATUS_ACTIVE, LatePaymentAlert::STATUS_ACKNOWLEDGED])
                        ->first();

                    if (!$existingAlert) {
                        $this->createLatePaymentAlert($installment, $daysOverdue);
                        $alertsCreated++;
                    } else {
                        // Update existing alert if severity changed
                        if ($this->updateAlertSeverity($existingAlert, $daysOverdue)) {
                            $alertsUpdated++;
                        }
                    }

                    $newlyOverdue++;
                }
            } catch (\Exception $e) {
                Log::error('Error processing installment for late payment', [
                    'installment_id' => $installment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Late payment check completed', [
            'checked' => $overdueInstallments->count(),
            'newly_overdue' => $newlyOverdue,
            'alerts_created' => $alertsCreated,
            'alerts_updated' => $alertsUpdated,
        ]);

        return [
            'checked' => $overdueInstallments->count(),
            'newly_overdue' => $newlyOverdue,
            'alerts_created' => $alertsCreated,
            'alerts_updated' => $alertsUpdated,
        ];
    }

    /**
     * Mark installment as overdue and calculate late fees.
     */
    public function markInstallmentOverdue(Installment $installment, int $daysOverdue): void
    {
        $schedule = $installment->paymentSchedule;

        // Calculate late fees if enabled
        $lateFees = 0;
        if ($schedule->late_fees_enabled && $schedule->late_fee_percentage > 0) {
            $lateFees = $this->calculateLateFee(
                $installment->amount_outstanding,
                $daysOverdue,
                $schedule->late_fee_percentage,
                $schedule->late_fee_cap
            );
        }

        $installment->update([
            'status' => Installment::STATUS_OVERDUE,
            'overdue_since' => now(),
            'late_fees_accrued' => $lateFees,
        ]);

        // Dispatch event
        event(new \App\Events\PaymentOverdue($installment, $daysOverdue));
    }

    /**
     * Create a late payment alert.
     */
    public function createLatePaymentAlert(Installment $installment, int $daysOverdue): LatePaymentAlert
    {
        $totalDue = $installment->amount_outstanding + $installment->late_fees_accrued;

        $alert = LatePaymentAlert::create([
            'payment_schedule_id' => $installment->payment_schedule_id,
            'installment_id' => $installment->id,
            'client_id' => $installment->paymentSchedule->client_id,
            'severity' => LatePaymentAlert::calculateSeverity($daysOverdue),
            'status' => LatePaymentAlert::STATUS_ACTIVE,
            'days_overdue' => $daysOverdue,
            'amount_overdue' => $totalDue,
            'alert_date' => now(),
        ]);

        Log::info('Late payment alert created', [
            'alert_id' => $alert->id,
            'installment_id' => $installment->id,
            'severity' => $alert->severity,
        ]);

        return $alert;
    }

    /**
     * Update alert severity based on days overdue.
     */
    public function updateAlertSeverity(LatePaymentAlert $alert, int $daysOverdue): bool
    {
        $newSeverity = LatePaymentAlert::calculateSeverity($daysOverdue);

        if ($newSeverity === $alert->severity && $alert->days_overdue === $daysOverdue) {
            return false;
        }

        $installment = Installment::find($alert->installment_id);
        if ($installment) {
            $amountOverdue = $installment->amount_outstanding + $installment->late_fees_accrued;
        } else {
            $amountOverdue = $alert->amount_overdue;
        }

        $alert->update([
            'severity' => $newSeverity,
            'days_overdue' => $daysOverdue,
            'amount_overdue' => $amountOverdue,
        ]);

        Log::info('Late payment alert updated', [
            'alert_id' => $alert->id,
            'new_severity' => $newSeverity,
            'days_overdue' => $daysOverdue,
        ]);

        return true;
    }

    /**
     * Get active alerts.
     */
    public function getActiveAlerts(?int $clientId = null, ?string $severity = null)
    {
        $query = LatePaymentAlert::with(['paymentSchedule', 'installment', 'client'])
            ->whereIn('status', [LatePaymentAlert::STATUS_ACTIVE, LatePaymentAlert::STATUS_ACKNOWLEDGED]);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        if ($severity) {
            $query->where('severity', $severity);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get high priority alerts.
     */
    public function getHighPriorityAlerts(?int $clientId = null)
    {
        $query = LatePaymentAlert::with(['paymentSchedule', 'installment', 'client'])
            ->whereIn('status', [LatePaymentAlert::STATUS_ACTIVE, LatePaymentAlert::STATUS_ACKNOWLEDGED])
            ->whereIn('severity', [LatePaymentAlert::SEVERITY_HIGH, LatePaymentAlert::SEVERITY_CRITICAL]);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Acknowledge an alert.
     */
    public function acknowledgeAlert(int $alertId, int $userId): LatePaymentAlert
    {
        $alert = LatePaymentAlert::findOrFail($alertId);
        $alert->acknowledge($userId);

        return $alert;
    }

    /**
     * Resolve an alert.
     */
    public function resolveAlert(int $alertId, ?string $notes = null): LatePaymentAlert
    {
        $alert = LatePaymentAlert::findOrFail($alertId);
        $alert->resolve($notes);

        return $alert;
    }

    /**
     * Escalate an alert.
     */
    public function escalateAlert(int $alertId, int $escalatedTo, ?string $notes = null): LatePaymentAlert
    {
        $alert = LatePaymentAlert::findOrFail($alertId);
        $alert->escalate($escalatedTo, $notes);

        return $alert;
    }

    /**
     * Get late payment statistics.
     */
    public function getStatistics(?int $clientId = null): array
    {
        $query = LatePaymentAlert::whereIn('status', [LatePaymentAlert::STATUS_ACTIVE, LatePaymentAlert::STATUS_ACKNOWLEDGED]);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        $alerts = $query->get();

        $bySeverity = [
            LatePaymentAlert::SEVERITY_LOW => ['count' => 0, 'amount' => 0],
            LatePaymentAlert::SEVERITY_MEDIUM => ['count' => 0, 'amount' => 0],
            LatePaymentAlert::SEVERITY_HIGH => ['count' => 0, 'amount' => 0],
            LatePaymentAlert::SEVERITY_CRITICAL => ['count' => 0, 'amount' => 0],
        ];

        $totalAmountOverdue = 0;

        foreach ($alerts as $alert) {
            $bySeverity[$alert->severity]['count']++;
            $bySeverity[$alert->severity]['amount'] += $alert->amount_overdue;
            $totalAmountOverdue += $alert->amount_overdue;
        }

        // Get 30-day trend
        $recentTrend = LatePaymentAlert::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => (int) $item->count,
                ];
            });

        return [
            'total_overdue' => $alerts->count(),
            'total_amount_overdue' => $totalAmountOverdue,
            'by_severity' => $bySeverity,
            'recent_trend' => $recentTrend,
        ];
    }

    /**
     * Get overdue summary for dashboard.
     */
    public function getOverdueSummary(): array
    {
        $alerts = LatePaymentAlert::whereIn('status', [LatePaymentAlert::STATUS_ACTIVE, LatePaymentAlert::STATUS_ACKNOWLEDGED])
            ->with(['client', 'installment'])
            ->get();

        return [
            'total_alerts' => $alerts->count(),
            'critical_count' => $alerts->where('severity', LatePaymentAlert::SEVERITY_CRITICAL)->count(),
            'high_count' => $alerts->where('severity', LatePaymentAlert::SEVERITY_HIGH)->count(),
            'medium_count' => $alerts->where('severity', LatePaymentAlert::SEVERITY_MEDIUM)->count(),
            'low_count' => $alerts->where('severity', LatePaymentAlert::SEVERITY_LOW)->count(),
            'total_amount' => $alerts->sum('amount_overdue'),
            'recent_alerts' => $alerts->where('created_at', '>=', now()->subDays(7))->take(5),
        ];
    }

    /**
     * Calculate days overdue.
     */
    private function calculateDaysOverdue(Carbon $dueDate, Carbon $today): int
    {
        return (int) $today->diffInDays($dueDate);
    }

    /**
     * Calculate late fee amount.
     */
    private function calculateLateFee(
        float $amountOutstanding,
        int $daysOverdue,
        float $percentagePerPeriod,
        ?float $cap = null
    ): float {
        // Assume monthly compounding (every 30 days)
        $periods = (int) floor($daysOverdue / 30) + 1;
        $lateFee = $amountOutstanding * $percentagePerPeriod * $periods;

        if ($cap !== null && $cap > 0) {
            $lateFee = min($lateFee, $cap);
        }

        return round($lateFee, 2);
    }

    /**
     * Auto-resolve alerts for paid installments.
     */
    public function autoResolvePaidAlerts(): int
    {
        $resolvedCount = 0;

        $alerts = LatePaymentAlert::whereIn('status', [LatePaymentAlert::STATUS_ACTIVE, LatePaymentAlert::STATUS_ACKNOWLEDGED])
            ->whereHas('installment', function ($q) {
                $q->where('status', Installment::STATUS_PAID);
            })
            ->get();

        foreach ($alerts as $alert) {
            $alert->resolve('Automatically resolved - installment paid');
            $resolvedCount++;
        }

        Log::info('Auto-resolved paid alerts', ['count' => $resolvedCount]);

        return $resolvedCount;
    }
}
