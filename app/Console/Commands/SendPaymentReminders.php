<?php

namespace App\Console\Commands;

use App\Modules\Payments\Models\Installment;
use App\Modules\Payments\Models\PaymentSchedule;
use App\Notifications\PaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:send-reminders 
                            {--days=7 : Days before due date to send reminders}
                            {--client= : Send only to specific client ID}
                            {--dry-run : Show what would be sent without sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminder notifications for upcoming installments';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('📧 Starting payment reminder check...');
        $this->newLine();

        $daysBefore = (int) $this->option('days');
        $clientId = $this->option('client');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE - No emails will be sent');
            $this->newLine();
        }

        $targetDate = now()->addDays($daysBefore)->startOfDay();
        
        $this->info("Looking for installments due on: {$targetDate->format('Y-m-d')}");
        $this->newLine();

        try {
            $query = Installment::with(['paymentSchedule.reservation', 'paymentSchedule.client'])
                ->whereDate('due_date', $targetDate)
                ->whereIn('status', [Installment::STATUS_PENDING, Installment::STATUS_PARTIAL])
                ->whereHas('paymentSchedule', function ($q) {
                    $q->where('status', PaymentSchedule::STATUS_ACTIVE);
                })
                ->where(function ($q) {
                    // Don't send if already reminded today
                    $q->whereNull('reminder_sent_at')
                      ->orWhereDate('reminder_sent_at', '<', now()->startOfDay());
                });

            if ($clientId) {
                $query->whereHas('paymentSchedule', function ($q) use ($clientId) {
                    $q->where('client_id', $clientId);
                });
            }

            $installments = $query->get();

            $this->info("Found {$installments->count()} installments requiring reminders.");
            $this->newLine();

            if ($installments->isEmpty()) {
                $this->info('✅ No reminders to send.');
                return self::SUCCESS;
            }

            $sent = 0;
            $failed = 0;

            foreach ($installments as $installment) {
                try {
                    if (!$dryRun) {
                        $this->sendReminder($installment, $daysBefore);
                        
                        // Update reminder tracking
                        $installment->update([
                            'reminder_sent_at' => now(),
                            'reminder_count' => $installment->reminder_count + 1,
                        ]);
                    }

                    $sent++;

                    $this->info("✅ {$installment->paymentSchedule->client->name} - {$installment->name} (€{$installment->amount})");
                } catch (\Exception $e) {
                    $failed++;
                    $this->error("❌ Failed for {$installment->paymentSchedule->client->name}: {$e->getMessage()}");
                }
            }

            $this->newLine();
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Reminders Sent', $sent],
                    ['Failed', $failed],
                    ['Total', $sent + $failed],
                ]
            );

            $this->newLine();
            $this->info('✅ Payment reminder process completed.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error sending reminders: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    /**
     * Send reminder notification.
     */
    private function sendReminder(Installment $installment, int $daysUntilDue): void
    {
        $client = $installment->paymentSchedule->client;
        
        if (!$client || !$client->email) {
            throw new \Exception('Client has no email address');
        }

        // Send to client
        Notification::route('mail', $client->email)
            ->notify(new PaymentReminderNotification($installment, $daysUntilDue));

        // Optionally notify internal staff
        // $this->notifyInternalStaff($installment, $daysUntilDue);
    }

    /**
     * Get installments with upcoming due dates (for the current week).
     */
    public function getWeeklyUpcomingInstallments(): array
    {
        $startDate = now();
        $endDate = now()->addDays(7);

        return Installment::with(['paymentSchedule.client', 'paymentSchedule.reservation'])
            ->whereBetween('due_date', [$startDate, $endDate])
            ->whereIn('status', [Installment::STATUS_PENDING, Installment::STATUS_PARTIAL])
            ->whereHas('paymentSchedule', function ($q) {
                $q->where('status', PaymentSchedule::STATUS_ACTIVE);
            })
            ->get()
            ->groupBy(function ($installment) {
                return $installment->due_date->format('Y-m-d');
            })
            ->toArray();
    }
}
