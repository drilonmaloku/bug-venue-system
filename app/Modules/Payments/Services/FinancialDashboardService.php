<?php

namespace App\Modules\Payments\Services;

use App\Modules\Payments\Models\Installment;
use App\Modules\Payments\Models\LatePaymentAlert;
use App\Modules\Payments\Models\Payment;
use App\Modules\Payments\Models\PaymentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialDashboardService
{
    /**
     * Get complete dashboard data.
     */
    public function getDashboardData(?int $clientId = null): array
    {
        return [
            'summary' => $this->getSummary($clientId),
            'payment_status' => $this->getPaymentStatusBreakdown($clientId),
            'upcoming_payments' => $this->getUpcomingPayments($clientId),
            'overdue_payments' => $this->getOverduePayments($clientId),
            'monthly_trend' => $this->getMonthlyTrend($clientId),
            'top_clients' => $this->getTopClients(),
            'late_payment_stats' => $this->getLatePaymentStats($clientId),
        ];
    }

    /**
     * Get summary metrics.
     */
    public function getSummary(?int $clientId = null): array
    {
        $scheduleQuery = PaymentSchedule::query();
        
        if ($clientId) {
            $scheduleQuery->where('client_id', $clientId);
        }

        $schedules = $scheduleQuery->where('status', PaymentSchedule::STATUS_ACTIVE)->get();

        $today = now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        // Calculate totals
        $totalOutstanding = $schedules->sum('total_outstanding');
        $totalPaid = $schedules->sum('paid_amount');
        $totalRevenue = $schedules->sum('total_amount');

        // Get overdue from alerts
        $alertQuery = LatePaymentAlert::whereIn('status', [
            LatePaymentAlert::STATUS_ACTIVE, 
            LatePaymentAlert::STATUS_ACKNOWLEDGED
        ]);
        
        if ($clientId) {
            $alertQuery->where('client_id', $clientId);
        }

        $totalOverdue = $alertQuery->sum('amount_overdue');

        // This month's payments
        $monthlyPaymentQuery = Payment::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('status', Payment::STATUS_COMPLETED);
        
        if ($clientId) {
            $monthlyPaymentQuery->where('client_id', $clientId);
        }

        $totalPaidThisMonth = $monthlyPaymentQuery->sum('value');

        // Expected revenue this month (from installments due this month)
        $expectedRevenueQuery = Installment::whereBetween('due_date', [$startOfMonth, $endOfMonth])
            ->whereHas('paymentSchedule', function ($q) {
                $q->where('status', PaymentSchedule::STATUS_ACTIVE);
            });
        
        if ($clientId) {
            $expectedRevenueQuery->whereHas('paymentSchedule', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        $expectedRevenueThisMonth = $expectedRevenueQuery->sum('amount');

        return [
            'total_outstanding' => $totalOutstanding,
            'total_overdue' => $totalOverdue,
            'total_paid' => $totalPaid,
            'total_revenue' => $totalRevenue,
            'total_paid_this_month' => $totalPaidThisMonth,
            'expected_revenue_this_month' => $expectedRevenueThisMonth,
            'collection_rate' => $totalRevenue > 0 ? round(($totalPaid / $totalRevenue) * 100, 2) : 0,
        ];
    }

    /**
     * Get payment status breakdown.
     */
    public function getPaymentStatusBreakdown(?int $clientId = null): array
    {
        $query = Installment::whereHas('paymentSchedule', function ($q) {
            $q->where('status', PaymentSchedule::STATUS_ACTIVE);
        });

        if ($clientId) {
            $query->whereHas('paymentSchedule', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        $breakdown = $query->select('status', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('status')
            ->pluck('total_amount', 'status')
            ->toArray();

        return [
            'paid' => $breakdown[Installment::STATUS_PAID] ?? 0,
            'pending' => $breakdown[Installment::STATUS_PENDING] ?? 0,
            'partial' => $breakdown[Installment::STATUS_PARTIAL] ?? 0,
            'overdue' => $breakdown[Installment::STATUS_OVERDUE] ?? 0,
        ];
    }

    /**
     * Get upcoming payments.
     */
    public function getUpcomingPayments(?int $clientId = null, int $days = 14): array
    {
        $startDate = now();
        $endDate = now()->addDays($days);

        $query = Installment::with(['paymentSchedule.reservation', 'paymentSchedule.client'])
            ->whereHas('paymentSchedule', function ($q) use ($clientId) {
                $q->where('status', PaymentSchedule::STATUS_ACTIVE);
                if ($clientId) {
                    $q->where('client_id', $clientId);
                }
            })
            ->whereBetween('due_date', [$startDate, $endDate])
            ->whereIn('status', [Installment::STATUS_PENDING, Installment::STATUS_PARTIAL])
            ->orderBy('due_date', 'asc');

        return $query->get()->map(function ($installment) {
            return [
                'id' => $installment->id,
                'name' => $installment->name,
                'type' => $installment->type,
                'amount' => $installment->amount,
                'amount_outstanding' => $installment->amount_outstanding,
                'due_date' => $installment->due_date->format('Y-m-d'),
                'days_until_due' => $installment->daysUntilDue(),
                'client' => [
                    'id' => $installment->paymentSchedule->client->id ?? null,
                    'name' => $installment->paymentSchedule->client->name ?? 'Unknown',
                ],
                'reservation' => [
                    'id' => $installment->paymentSchedule->reservation->id ?? null,
                    'date' => $installment->paymentSchedule->reservation->date ?? null,
                ],
            ];
        })->toArray();
    }

    /**
     * Get overdue payments.
     */
    public function getOverduePayments(?int $clientId = null, int $limit = 10): array
    {
        $query = LatePaymentAlert::with(['paymentSchedule.reservation', 'paymentSchedule.client', 'installment'])
            ->whereIn('status', [LatePaymentAlert::STATUS_ACTIVE, LatePaymentAlert::STATUS_ACKNOWLEDGED]);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        $alerts = $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $alerts->map(function ($alert) {
            return [
                'alert_id' => $alert->id,
                'installment_id' => $alert->installment_id,
                'name' => $alert->installment->name ?? 'Unknown',
                'amount' => $alert->amount_overdue,
                'days_overdue' => $alert->days_overdue,
                'severity' => $alert->severity,
                'severity_label' => $alert->severity_label,
                'client' => [
                    'id' => $alert->client->id ?? null,
                    'name' => $alert->client->name ?? 'Unknown',
                ],
                'alert_date' => $alert->alert_date->format('Y-m-d'),
            ];
        })->toArray();
    }

    /**
     * Get monthly trend.
     */
    public function getMonthlyTrend(?int $clientId = null, int $months = 6): array
    {
        $trend = [];
        $today = now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            // Payments received this month
            $receivedQuery = Payment::where('status', Payment::STATUS_COMPLETED)
                ->whereBetween('date', [$startOfMonth, $endOfMonth]);
            
            if ($clientId) {
                $receivedQuery->where('client_id', $clientId);
            }

            $received = $receivedQuery->sum('value');

            // Expected payments (installments due this month)
            $expectedQuery = Installment::whereBetween('due_date', [$startOfMonth, $endOfMonth])
                ->whereHas('paymentSchedule', function ($q) {
                    $q->where('status', PaymentSchedule::STATUS_ACTIVE);
                });
            
            if ($clientId) {
                $expectedQuery->whereHas('paymentSchedule', function ($q) use ($clientId) {
                    $q->where('client_id', $clientId);
                });
            }

            $expected = $expectedQuery->sum('amount');

            // Overdue created this month
            $overdueQuery = LatePaymentAlert::whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            
            if ($clientId) {
                $overdueQuery->where('client_id', $clientId);
            }

            $overdue = $overdueQuery->sum('amount_overdue');

            $trend[] = [
                'month' => $month->format('M Y'),
                'month_key' => $month->format('Y-m'),
                'received' => (float) $received,
                'expected' => (float) $expected,
                'overdue' => (float) $overdue,
            ];
        }

        return $trend;
    }

    /**
     * Get top clients with outstanding payments.
     */
    public function getTopClients(int $limit = 5): array
    {
        $clients = PaymentSchedule::select(
                'client_id',
                DB::raw('SUM(total_outstanding) as total_outstanding'),
                DB::raw('SUM(paid_amount) as total_paid')
            )
            ->where('status', PaymentSchedule::STATUS_ACTIVE)
            ->where('total_outstanding', '>', 0)
            ->groupBy('client_id')
            ->orderByDesc('total_outstanding')
            ->limit($limit)
            ->with('client')
            ->get();

        return $clients->map(function ($item) {
            $overdueAmount = LatePaymentAlert::where('client_id', $item->client_id)
                ->whereIn('status', [LatePaymentAlert::STATUS_ACTIVE, LatePaymentAlert::STATUS_ACKNOWLEDGED])
                ->sum('amount_overdue');

            return [
                'client_id' => $item->client_id,
                'client_name' => $item->client->name ?? 'Unknown',
                'total_outstanding' => (float) $item->total_outstanding,
                'total_paid' => (float) $item->total_paid,
                'overdue_amount' => (float) $overdueAmount,
            ];
        })->toArray();
    }

    /**
     * Get late payment statistics.
     */
    public function getLatePaymentStats(?int $clientId = null): array
    {
        $query = LatePaymentAlert::whereIn('status', [
            LatePaymentAlert::STATUS_ACTIVE, 
            LatePaymentAlert::STATUS_ACKNOWLEDGED
        ]);

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

        foreach ($alerts as $alert) {
            $bySeverity[$alert->severity]['count']++;
            $bySeverity[$alert->severity]['amount'] += $alert->amount_overdue;
        }

        return [
            'total_alerts' => $alerts->count(),
            'total_amount' => $alerts->sum('amount_overdue'),
            'by_severity' => $bySeverity,
            'avg_days_overdue' => $alerts->avg('days_overdue') ?? 0,
            'max_days_overdue' => $alerts->max('days_overdue') ?? 0,
        ];
    }

    /**
     * Get revenue forecast.
     */
    public function getRevenueForecast(int $monthsAhead = 3): array
    {
        $forecast = [];
        $today = now();

        for ($i = 1; $i <= $monthsAhead; $i++) {
            $month = $today->copy()->addMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $expected = Installment::whereBetween('due_date', [$startOfMonth, $endOfMonth])
                ->whereHas('paymentSchedule', function ($q) {
                    $q->where('status', PaymentSchedule::STATUS_ACTIVE);
                })
                ->sum('amount');

            $forecast[] = [
                'month' => $month->format('M Y'),
                'month_key' => $month->format('Y-m'),
                'expected_revenue' => (float) $expected,
            ];
        }

        return $forecast;
    }

    /**
     * Get payment method breakdown.
     */
    public function getPaymentMethodBreakdown(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subMonths(6);
        $endDate = $endDate ?? now();

        $breakdown = Payment::where('status', Payment::STATUS_COMPLETED)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(value) as total'))
            ->groupBy('payment_method')
            ->get();

        return $breakdown->map(function ($item) {
            $payment = new Payment(['payment_method' => $item->payment_method]);
            
            return [
                'method' => $item->payment_method,
                'method_label' => $payment->payment_method_label,
                'count' => (int) $item->count,
                'total' => (float) $item->total,
            ];
        })->toArray();
    }
}
