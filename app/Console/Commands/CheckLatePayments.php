<?php

namespace App\Console\Commands;

use App\Modules\Payments\Services\LatePaymentService;
use Illuminate\Console\Command;

class CheckLatePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-late 
                            {--client= : Check only for specific client ID}
                            {--dry-run : Run without creating alerts}
                            {--auto-resolve : Auto-resolve alerts for paid installments}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for late payments and generate alerts';

    /**
     * The late payment service instance.
     */
    protected LatePaymentService $latePaymentService;

    /**
     * Create a new command instance.
     */
    public function __construct(LatePaymentService $latePaymentService)
    {
        parent::__construct();
        $this->latePaymentService = $latePaymentService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Starting late payment check...');
        $this->newLine();

        $dryRun = $this->option('dry-run');
        $clientId = $this->option('client');
        $autoResolve = $this->option('auto-resolve');

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE - No alerts will be created');
            $this->newLine();
        }

        if ($clientId) {
            $this->info("📋 Checking for client ID: {$clientId}");
        }

        try {
            // Run the check
            if (!$dryRun) {
                $result = $this->latePaymentService->checkForLatePayments();
            } else {
                // Dry run simulation - just count what would be found
                $result = $this->simulateCheck($clientId);
            }

            // Auto-resolve if requested
            $resolvedCount = 0;
            if (!$dryRun && $autoResolve) {
                $resolvedCount = $this->latePaymentService->autoResolvePaidAlerts();
            }

            // Display results
            $this->displayResults($result, $resolvedCount);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error checking for late payments: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    /**
     * Simulate a check for dry run.
     */
    private function simulateCheck(?int $clientId): array
    {
        $overdueInstallments = \App\Modules\Payments\Models\Installment::with(['paymentSchedule'])
            ->where('status', \App\Modules\Payments\Models\Installment::STATUS_PENDING)
            ->whereDate('due_date', '<', now()->startOfDay())
            ->whereHas('paymentSchedule', function ($q) {
                $q->where('status', \App\Modules\Payments\Models\PaymentSchedule::STATUS_ACTIVE);
            })
            ->when($clientId, function ($q) use ($clientId) {
                $q->whereHas('paymentSchedule', function ($sq) use ($clientId) {
                    $sq->where('client_id', $clientId);
                });
            })
            ->get();

        $newlyOverdue = 0;
        foreach ($overdueInstallments as $installment) {
            $daysOverdue = $installment->due_date->diffInDays(now());
            $gracePeriodDays = $installment->paymentSchedule->grace_period_days ?? 0;
            
            if ($daysOverdue > $gracePeriodDays) {
                $newlyOverdue++;
            }
        }

        return [
            'checked' => $overdueInstallments->count(),
            'newly_overdue' => $newlyOverdue,
            'alerts_created' => 0, // Would be created in real run
            'alerts_updated' => 0,
        ];
    }

    /**
     * Display the results table.
     */
    private function displayResults(array $result, int $resolvedCount): void
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['Installments Checked', $result['checked']],
                ['Newly Overdue', $result['newly_overdue']],
                ['Alerts Created', $result['alerts_created'] ?? 0],
                ['Alerts Updated', $result['alerts_updated'] ?? 0],
                ['Auto-Resolved', $resolvedCount],
            ]
        );

        $this->newLine();

        if ($result['alerts_created'] > 0) {
            $this->warn("⚠️  Created {$result['alerts_created']} new late payment alerts!");
        }

        if ($result['alerts_updated'] > 0) {
            $this->info("📝 Updated {$result['alerts_updated']} existing alerts.");
        }

        if ($resolvedCount > 0) {
            $this->info("✅ Auto-resolved {$resolvedCount} alerts (paid installments).");
        }

        if ($result['newly_overdue'] === 0) {
            $this->info('✅ No new late payments detected.');
        }

        $this->newLine();
        $this->info('✅ Late payment check completed.');
    }
}
