<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Clients\Models\Client;
use App\Modules\Payments\Models\Installment;
use App\Modules\Payments\Models\PaymentSchedule;
use App\Modules\Payments\Models\PaymentScheduleTemplate;
use App\Modules\Reservations\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing reservations
        $reservations = Reservation::with('client')->take(10)->get();
        
        if ($reservations->isEmpty()) {
            $this->command->warn('No reservations found. Skipping payment schedule seeding.');
            return;
        }

        $user = User::first();
        if (!$user) {
            $this->command->warn('No users found. Skipping payment schedule seeding.');
            return;
        }

        $templates = [
            PaymentScheduleTemplate::TYPE_STANDARD_3_TIER,
            PaymentScheduleTemplate::TYPE_EQUAL_SPLIT,
            PaymentScheduleTemplate::TYPE_FULL_UPFRONT,
        ];

        DB::transaction(function () use ($reservations, $templates, $user) {
            foreach ($reservations as $index => $reservation) {
                $templateType = $templates[$index % count($templates)];
                $template = PaymentScheduleTemplate::getByType($templateType);
                
                if (!$template) {
                    $template = new PaymentScheduleTemplate(
                        PaymentScheduleTemplate::getPredefinedTemplates()[$templateType]
                    );
                }

                $totalAmount = $reservation->total_payment ?? rand(5000, 50000);
                $eventDate = Carbon::parse($reservation->date ?? now()->addMonths(2));
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
                    'status' => PaymentSchedule::STATUS_ACTIVE,
                    'event_date' => $eventDate,
                    'grace_period_days' => $template->grace_period_days,
                    'late_fees_enabled' => $template->late_fees_enabled,
                    'late_fee_percentage' => $template->late_fee_percentage,
                    'created_by' => $user->id,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                ]);

                // Generate installments
                $installmentsData = $template->generateInstallments($totalAmount, $eventDate, $bookingDate);

                foreach ($installmentsData as $seq => $data) {
                    // Randomly mark some installments as paid for variety
                    $isPaid = rand(0, 100) < 40; // 40% chance of being paid
                    $paidAmount = $isPaid ? $data['amount'] : (rand(0, 100) < 20 ? $data['amount'] * 0.5 : 0);
                    $status = $paidAmount >= $data['amount'] ? Installment::STATUS_PAID :
                        ($paidAmount > 0 ? Installment::STATUS_PARTIAL : Installment::STATUS_PENDING);

                    Installment::create([
                        'payment_schedule_id' => $schedule->id,
                        'type' => $data['type'],
                        'sequence' => $seq + 1,
                        'name' => $data['name'],
                        'amount' => $data['amount'],
                        'percentage_of_total' => $data['percentage_of_total'],
                        'due_date' => $data['due_date'],
                        'status' => $status,
                        'paid_amount' => $paidAmount,
                        'amount_outstanding' => $data['amount'] - $paidAmount,
                        'paid_date' => $status === Installment::STATUS_PAID ? now()->subDays(rand(1, 30)) : null,
                    ]);
                }

                // Recalculate totals
                $schedule->recalculateTotals();
            }
        });

        $this->command->info('Payment schedules seeded successfully.');
    }
}
