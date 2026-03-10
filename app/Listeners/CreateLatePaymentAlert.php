<?php

namespace App\Listeners;

use App\Events\PaymentOverdue;
use App\Modules\Payments\Models\LatePaymentAlert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateLatePaymentAlert implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(PaymentOverdue $event): void
    {
        try {
            $installment = $event->installment;
            $daysOverdue = $event->daysOverdue;
            $schedule = $installment->paymentSchedule;

            // Check if an active alert already exists
            $existingAlert = LatePaymentAlert::where('installment_id', $installment->id)
                ->whereIn('status', [LatePaymentAlert::STATUS_ACTIVE, LatePaymentAlert::STATUS_ACKNOWLEDGED])
                ->first();

            if ($existingAlert) {
                // Update existing alert
                $existingAlert->update([
                    'days_overdue' => $daysOverdue,
                    'severity' => LatePaymentAlert::calculateSeverity($daysOverdue),
                    'amount_overdue' => $installment->amount_outstanding + $installment->late_fees_accrued,
                ]);

                Log::info('Late payment alert updated', [
                    'alert_id' => $existingAlert->id,
                    'days_overdue' => $daysOverdue,
                ]);
            } else {
                // Create new alert
                $alert = LatePaymentAlert::create([
                    'payment_schedule_id' => $schedule->id,
                    'installment_id' => $installment->id,
                    'client_id' => $schedule->client_id,
                    'severity' => LatePaymentAlert::calculateSeverity($daysOverdue),
                    'status' => LatePaymentAlert::STATUS_ACTIVE,
                    'days_overdue' => $daysOverdue,
                    'amount_overdue' => $installment->amount_outstanding + $installment->late_fees_accrued,
                    'alert_date' => now(),
                ]);

                Log::info('Late payment alert created', [
                    'alert_id' => $alert->id,
                    'installment_id' => $installment->id,
                    'severity' => $alert->severity,
                ]);
            }

            // Send notifications based on severity
            $this->sendNotifications($event);
        } catch (\Exception $e) {
            Log::error('Error creating late payment alert', [
                'installment_id' => $event->installment->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send notifications based on severity.
     */
    private function sendNotifications(PaymentOverdue $event): void
    {
        $severity = $event->getSeverity();
        $installment = $event->installment;
        $client = $installment->paymentSchedule->client;

        // Always notify internal staff
        try {
            \Illuminate\Support\Facades\Notification::send(
                \App\Models\User::where('role', 'admin')->get(),
                new \App\Notifications\PaymentOverdueNotification($installment, $event->daysOverdue)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send overdue notification to staff', [
                'error' => $e->getMessage(),
            ]);
        }

        // Send to client based on severity
        if ($severity === LatePaymentAlert::SEVERITY_HIGH || $severity === LatePaymentAlert::SEVERITY_CRITICAL) {
            try {
                if ($client && $client->email) {
                    \Illuminate\Support\Facades\Notification::route('mail', $client->email)
                        ->notify(new \App\Notifications\PaymentFinalNoticeNotification($installment, $event->daysOverdue));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send final notice to client', [
                    'client_id' => $client->id ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
