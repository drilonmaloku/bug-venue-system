<?php

namespace App\Modules\Payments\Services;

use App\Modules\Payments\Models\Installment;
use App\Modules\Payments\Models\PaymentSchedule;
use App\Modules\Payments\Models\PaymentScheduleTemplate;
use App\Modules\Reservations\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentScheduleService
{
    /**
     * Create a new payment schedule from a template.
     */
    public function createFromTemplate(
        Reservation $reservation,
        string $templateType,
        ?float $totalAmount = null,
        ?int $createdBy = null
    ): PaymentSchedule {
        try {
            DB::beginTransaction();

            $template = PaymentScheduleTemplate::getByType($templateType);
            
            if (!$template) {
                // Fall back to standard template
                $templateData = PaymentScheduleTemplate::getPredefinedTemplates()[$templateType] ?? 
                    PaymentScheduleTemplate::getPredefinedTemplates()[PaymentScheduleTemplate::TYPE_STANDARD_3_TIER];
                
                $template = new PaymentScheduleTemplate($templateData);
            }

            $totalAmount = $totalAmount ?? $reservation->total_payment ?? 0;
            $eventDate = Carbon::parse($reservation->date ?? now()->addMonths(3));
            $bookingDate = Carbon::parse($reservation->created_at ?? now());

            // Create payment schedule
            $schedule = PaymentSchedule::create([
                'reservation_id' => $reservation->id,
                'client_id' => $reservation->client_id,
                'template_type' => $templateType,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'total_outstanding' => $totalAmount,
                'currency' => 'EUR',
                'status' => PaymentSchedule::STATUS_DRAFT,
                'event_date' => $eventDate,
                'grace_period_days' => $template->grace_period_days,
                'late_fees_enabled' => $template->late_fees_enabled,
                'late_fee_percentage' => $template->late_fee_percentage,
                'created_by' => $createdBy ?? auth()->id(),
            ]);

            // Generate installments from template
            $installmentsData = $template->generateInstallments($totalAmount, $eventDate, $bookingDate);
            
            foreach ($installmentsData as $index => $data) {
                $data['payment_schedule_id'] = $schedule->id;
                Installment::create($data);
            }

            DB::commit();

            Log::info('Payment schedule created', [
                'schedule_id' => $schedule->id,
                'reservation_id' => $reservation->id,
                'template_type' => $templateType,
            ]);

            return $schedule->load('installments');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create payment schedule', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a custom payment schedule.
     */
    public function createCustom(
        Reservation $reservation,
        array $installmentData,
        ?float $totalAmount = null,
        ?int $createdBy = null
    ): PaymentSchedule {
        try {
            DB::beginTransaction();

            $totalAmount = $totalAmount ?? $reservation->total_payment ?? 0;
            
            // Validate installment percentages sum to 100
            $totalPercentage = collect($installmentData)->sum('percentage');
            if (abs($totalPercentage - 100) > 0.01) {
                throw new \InvalidArgumentException('Installment percentages must sum to 100%');
            }

            $eventDate = Carbon::parse($reservation->date ?? now()->addMonths(3));

            // Create payment schedule
            $schedule = PaymentSchedule::create([
                'reservation_id' => $reservation->id,
                'client_id' => $reservation->client_id,
                'template_type' => PaymentScheduleTemplate::TYPE_CUSTOM,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'total_outstanding' => $totalAmount,
                'currency' => 'EUR',
                'status' => PaymentSchedule::STATUS_DRAFT,
                'event_date' => $eventDate,
                'created_by' => $createdBy ?? auth()->id(),
            ]);

            // Create custom installments
            foreach ($installmentData as $index => $data) {
                $amount = round(($totalAmount * $data['percentage']) / 100, 2);
                
                Installment::create([
                    'payment_schedule_id' => $schedule->id,
                    'type' => $data['type'],
                    'sequence' => $index + 1,
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'amount' => $amount,
                    'percentage_of_total' => $data['percentage'],
                    'due_date' => $data['due_date'],
                    'status' => Installment::STATUS_PENDING,
                    'paid_amount' => 0,
                    'amount_outstanding' => $amount,
                ]);
            }

            DB::commit();

            return $schedule->load('installments');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Activate a payment schedule.
     */
    public function activate(int $scheduleId, int $approvedBy): PaymentSchedule
    {
        $schedule = PaymentSchedule::with('installments')->findOrFail($scheduleId);

        if ($schedule->status !== PaymentSchedule::STATUS_DRAFT) {
            throw new \InvalidArgumentException('Only draft schedules can be activated');
        }

        $schedule->activate($approvedBy);

        return $schedule;
    }

    /**
     * Get payment schedule by ID.
     */
    public function getById(int $id, array $with = []): ?PaymentSchedule
    {
        return PaymentSchedule::with($with)->find($id);
    }

    /**
     * Get payment schedule by reservation ID.
     */
    public function getByReservationId(int $reservationId): ?PaymentSchedule
    {
        return PaymentSchedule::with(['installments', 'latePaymentAlerts'])
            ->where('reservation_id', $reservationId)
            ->first();
    }

    /**
     * Get all schedules for a client.
     */
    public function getByClientId(int $clientId, array $filters = [])
    {
        $query = PaymentSchedule::with(['installments', 'reservation'])
            ->where('client_id', $clientId);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get upcoming installments.
     */
    public function getUpcomingInstallments(int $daysAhead = 14, ?int $clientId = null)
    {
        $query = Installment::with(['paymentSchedule.reservation', 'paymentSchedule.client'])
            ->whereHas('paymentSchedule', function ($q) {
                $q->where('status', PaymentSchedule::STATUS_ACTIVE);
            })
            ->whereIn('status', [Installment::STATUS_PENDING, Installment::STATUS_PARTIAL])
            ->whereBetween('due_date', [now(), now()->addDays($daysAhead)]);

        if ($clientId) {
            $query->whereHas('paymentSchedule', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        return $query->orderBy('due_date', 'asc')->get();
    }

    /**
     * Record a payment against a schedule.
     */
    public function recordPayment(
        PaymentSchedule $schedule,
        float $amount,
        ?int $installmentId = null,
        array $paymentData = []
    ): array {
        try {
            DB::beginTransaction();

            $remainingAmount = $amount;
            $payments = [];

            // If specific installment specified, pay that first
            if ($installmentId) {
                $installment = Installment::where('payment_schedule_id', $schedule->id)
                    ->findOrFail($installmentId);

                $payAmount = min($remainingAmount, $installment->amount_outstanding);
                $installment->recordPayment($payAmount);
                
                $payment = $this->createPaymentRecord($schedule, $installment, $payAmount, $paymentData);
                $payments[] = $payment;
                
                $remainingAmount -= $payAmount;
            }

            // Distribute remaining to other pending installments
            if ($remainingAmount > 0) {
                $pendingInstallments = Installment::where('payment_schedule_id', $schedule->id)
                    ->whereIn('status', [Installment::STATUS_PENDING, Installment::STATUS_PARTIAL])
                    ->orderBy('sequence', 'asc')
                    ->get();

                foreach ($pendingInstallments as $installment) {
                    if ($remainingAmount <= 0) {
                        break;
                    }

                    if (isset($installmentId) && $installment->id === $installmentId) {
                        continue;
                    }

                    $payAmount = min($remainingAmount, $installment->amount_outstanding);
                    $installment->recordPayment($payAmount);
                    
                    $payment = $this->createPaymentRecord($schedule, $installment, $payAmount, $paymentData);
                    $payments[] = $payment;
                    
                    $remainingAmount -= $payAmount;
                }
            }

            // Update schedule totals
            $schedule->recalculateTotals();

            // Check if fully paid
            if ($schedule->isFullyPaid()) {
                $schedule->markCompleted();
            }

            DB::commit();

            return [
                'schedule' => $schedule->fresh(['installments']),
                'payments' => $payments,
                'remaining_amount' => max(0, $remainingAmount),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create a payment record.
     */
    private function createPaymentRecord(
        PaymentSchedule $schedule,
        Installment $installment,
        float $amount,
        array $data
    ): Payment {
        return Payment::create([
            'location_id' => $data['location_id'] ?? auth()->user()->getCurrentLocationId(),
            'reservation_id' => $schedule->reservation_id,
            'client_id' => $schedule->client_id,
            'installment_id' => $installment->id,
            'value' => $amount,
            'payment_method' => $data['payment_method'] ?? Payment::METHOD_CASH,
            'status' => Payment::STATUS_COMPLETED,
            'date' => $data['date'] ?? now()->format('Y-m-d'),
            'transaction_reference' => $data['transaction_reference'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Delete a payment schedule.
     */
    public function delete(int $id): bool
    {
        $schedule = PaymentSchedule::findOrFail($id);
        
        if ($schedule->status === PaymentSchedule::STATUS_COMPLETED) {
            throw new \InvalidArgumentException('Cannot delete a completed schedule');
        }

        return $schedule->delete();
    }

    /**
     * Get all active schedules.
     */
    public function getActiveSchedules(?int $clientId = null)
    {
        $query = PaymentSchedule::with(['installments', 'client', 'reservation'])
            ->where('status', PaymentSchedule::STATUS_ACTIVE);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        return $query->orderBy('event_date', 'asc')->get();
    }

    /**
     * Get payment statistics for a client.
     */
    public function getClientStatistics(int $clientId): array
    {
        $schedules = PaymentSchedule::where('client_id', $clientId)->get();

        $totalAmount = $schedules->sum('total_amount');
        $totalPaid = $schedules->sum('paid_amount');
        $totalOutstanding = $schedules->sum('total_outstanding');

        $activeSchedules = $schedules->where('status', PaymentSchedule::STATUS_ACTIVE);
        $completedSchedules = $schedules->where('status', PaymentSchedule::STATUS_COMPLETED);

        return [
            'total_schedules' => $schedules->count(),
            'active_schedules' => $activeSchedules->count(),
            'completed_schedules' => $completedSchedules->count(),
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_outstanding' => $totalOutstanding,
            'payment_rate' => $totalAmount > 0 ? round(($totalPaid / $totalAmount) * 100, 2) : 0,
        ];
    }
}
