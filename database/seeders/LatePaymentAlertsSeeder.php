<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Payments\Models\Installment;
use App\Modules\Payments\Models\LatePaymentAlert;
use App\Modules\Payments\Models\PaymentSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LatePaymentAlertsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get schedules with pending/overdue installments
        $schedules = PaymentSchedule::with(['installments', 'client'])
            ->where('status', PaymentSchedule::STATUS_ACTIVE)
            ->take(5)
            ->get();

        if ($schedules->isEmpty()) {
            $this->command->warn('No active payment schedules found. Run PaymentSchedulesSeeder first.');
            return;
        }

        $user = User::first();

        DB::transaction(function () use ($schedules, $user) {
            foreach ($schedules as $schedule) {
                // Get pending installments that should be overdue
                $installments = $schedule->installments
                    ->where('status', Installment::STATUS_PENDING)
                    ->where('due_date', '<', now());

                foreach ($installments->take(2) as $installment) {
                    $daysOverdue = Carbon::parse($installment->due_date)->diffInDays(now());
                    
                    // Skip if within grace period
                    if ($daysOverdue <= $schedule->grace_period_days) {
                        continue;
                    }

                    // Mark installment as overdue
                    $installment->update([
                        'status' => Installment::STATUS_OVERDUE,
                        'overdue_since' => now()->subDays($daysOverdue),
                    ]);

                    // Calculate late fees
                    $lateFees = 0;
                    if ($schedule->late_fees_enabled) {
                        $periods = floor($daysOverdue / 30) + 1;
                        $lateFees = $installment->amount_outstanding * $schedule->late_fee_percentage * $periods;
                        
                        if ($schedule->late_fee_cap && $lateFees > $schedule->late_fee_cap) {
                            $lateFees = $schedule->late_fee_cap;
                        }
                        
                        $lateFees = round($lateFees, 2);
                        
                        $installment->update(['late_fees_accrued' => $lateFees]);
                    }

                    $totalDue = $installment->amount_outstanding + $lateFees;

                    // Create alert
                    LatePaymentAlert::create([
                        'payment_schedule_id' => $schedule->id,
                        'installment_id' => $installment->id,
                        'client_id' => $schedule->client_id,
                        'severity' => LatePaymentAlert::calculateSeverity($daysOverdue),
                        'status' => rand(0, 100) < 70 ? LatePaymentAlert::STATUS_ACTIVE : LatePaymentAlert::STATUS_ACKNOWLEDGED,
                        'days_overdue' => $daysOverdue,
                        'amount_overdue' => $totalDue,
                        'alert_date' => now()->subDays(rand(1, min($daysOverdue, 10))),
                        'acknowledged_by' => rand(0, 100) < 30 ? $user?->id : null,
                        'acknowledged_at' => rand(0, 100) < 30 ? now()->subDays(rand(1, 5)) : null,
                    ]);
                }
            }
        });

        $this->command->info('Late payment alerts seeded successfully.');
    }
}
