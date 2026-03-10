<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Modules\Payments\Models\Installment;
use App\Modules\Payments\Models\PaymentSchedule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdatePaymentStatus implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(PaymentReceived $event): void
    {
        try {
            $payment = $event->payment;
            $installment = $event->installment;
            $schedule = $installment->paymentSchedule;

            // Update installment status
            if ($installment->isPaid()) {
                $installment->update([
                    'status' => Installment::STATUS_PAID,
                    'paid_date' => now(),
                ]);

                Log::info('Installment marked as paid', [
                    'installment_id' => $installment->id,
                    'payment_id' => $payment->id,
                ]);
            } elseif ($installment->paid_amount > 0) {
                $installment->update([
                    'status' => Installment::STATUS_PARTIAL,
                ]);
            }

            // Update payment schedule totals
            $schedule->recalculateTotals();

            // Check if schedule is fully paid
            if ($schedule->isFullyPaid()) {
                $schedule->markCompleted();
                
                Log::info('Payment schedule completed', [
                    'schedule_id' => $schedule->id,
                    'total_paid' => $schedule->paid_amount,
                ]);
            }

            // If installment was overdue, resolve any active alerts
            if ($installment->wasChanged('status') && $installment->status === Installment::STATUS_PAID) {
                $this->resolveRelatedAlerts($installment);
            }
        } catch (\Exception $e) {
            Log::error('Error updating payment status', [
                'payment_id' => $event->payment->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Resolve related alerts for a paid installment.
     */
    private function resolveRelatedAlerts(Installment $installment): void
    {
        $alerts = \App\Modules\Payments\Models\LatePaymentAlert::where('installment_id', $installment->id)
            ->whereIn('status', [
                \App\Modules\Payments\Models\LatePaymentAlert::STATUS_ACTIVE,
                \App\Modules\Payments\Models\LatePaymentAlert::STATUS_ACKNOWLEDGED,
            ])
            ->get();

        foreach ($alerts as $alert) {
            $alert->resolve('Automatically resolved - installment paid in full');
            
            Log::info('Late payment alert auto-resolved', [
                'alert_id' => $alert->id,
                'installment_id' => $installment->id,
            ]);
        }
    }
}
