<?php

namespace App\Jobs;

use App\Modules\LocationPayments\Models\LocationCreditDeposit;
use App\Modules\LocationPayments\Models\LocationInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class GenerateInvoicesForCreditDeposits implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all completed credit deposits that don't have an invoice yet
        $creditDeposits = LocationCreditDeposit::where('status', 'completed')
            ->whereDoesntHave('invoice')
            ->get();

        foreach ($creditDeposits as $deposit) {
            // Create an invoice for this credit deposit
            LocationInvoice::create([
                'location_id' => $deposit->location_id,
                'credits' => $deposit->credits,
                'invoice_number' => 'INV-' . Str::upper(Str::random(8)),
                'status' => 'pending',
                'description' => 'Invoice for credit deposit ' . $deposit->deposit_number,
                'due_date' => now()->addDays(30),
                'location_credit_deposit_id' => $deposit->id
            ]);
        }
    }
} 