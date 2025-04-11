<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Location\Models\Location;
use App\Modules\LocationPayments\Models\LocationInvoice;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\MonthlyInvoiceGenerated;
use Illuminate\Support\Facades\Notification;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoices including a fixed charge plus any outstanding balance from previous pending invoices.';

    // Define the fixed monthly price (adjust as needed, or fetch from config/settings)
    const FIXED_MONTHLY_PRICE = 50.00; // Example: €50 fixed price

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly invoice generation (Fixed Price + Outstanding Balance)...');

        // Get all active locations (assuming deactivated_at is null for active)
        $activeLocations = Location::with('user')->whereNull('deactivated_at')->get();

        if ($activeLocations->isEmpty()) {
            $this->info('No active locations found. Exiting.');
            return 0;
        }

        $this->info("Found {$activeLocations->count()} active locations to process.");
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthYearStr = Carbon::now()->format('F Y');

        foreach ($activeLocations as $location) {
            $this->info("Processing location: {$location->name} (ID: {$location->id})");

            // 1. Calculate Total Outstanding Balance
            $totalOutstandingBalance = LocationInvoice::where('location_id', $location->id)
                ->where('status', 'pending')
                ->where('created_at', '<', $currentMonthStart)
                ->sum('credits');

            $this->info("Location {$location->name}: Outstanding balance from previous months = €{$totalOutstandingBalance}");

            // 2. Calculate New Invoice Amount
            $fixedPrice = self::FIXED_MONTHLY_PRICE;
            $newInvoiceAmount = $fixedPrice + $totalOutstandingBalance;
            $newInvoiceCredits = (int) round($newInvoiceAmount);

            // 3. Prepare Description and Due Date
            $descriptionBase = "Monthly Charge (€{$fixedPrice}) - {$currentMonthYearStr}";
            $descriptionOutstanding = '';
            if ($totalOutstandingBalance > 0) {
                $descriptionOutstanding = " + Outstanding Balance (€{$totalOutstandingBalance})";
            }
            $newInvoiceDescription = $descriptionBase . $descriptionOutstanding;

            // Example: Due date 15th of the month the invoice is generated in
            $dueDate = Carbon::now()->startOfMonth()->addDays(14);

            // 4. Check if an invoice for the current month/purpose already exists
            $currentMonthInvoiceExists = LocationInvoice::where('location_id', $location->id)
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', Carbon::now()->month)
                ->where('description', 'like', "%Monthly Charge (€{$fixedPrice})%{$currentMonthYearStr}%")
                ->exists();

            if ($currentMonthInvoiceExists) {
                $this->warn("Monthly invoice for {$currentMonthYearStr} seems to already exist for location {$location->name}. Skipping.");
                continue;
            }

            // 5. Create the new invoice if amount > 0
            if ($newInvoiceCredits > 0) {
                $newInvoice = LocationInvoice::create([
                    'location_id' => $location->id,
                    'credits' => $newInvoiceCredits,
                    'invoice_number' => 'INV-' . Str::upper(Str::random(8)),
                    'status' => 'pending',
                    'description' => $newInvoiceDescription,
                    'due_date' => $dueDate,
                    'location_credit_deposit_id' => null,
                ]);
                $this->info("Generated new monthly invoice (Total €{$newInvoiceAmount}) for location: {$location->name}");

                // 6. Send Notification to Location Owner
                if ($location->user) {
                    try {
                        Notification::send($location->user, new MonthlyInvoiceGenerated($newInvoice, $location));
                        $this->info("Notification sent to owner: {$location->user->email}");
                    } catch (\Exception $e) {
                        $this->error("Failed to send notification to {$location->user->email}: " . $e->getMessage());
                    }
                } else {
                    $this->warn("Location {$location->name} does not have an assigned owner. Cannot send notification.");
                }

            } else {
                $this->info("Skipping invoice generation for {$location->name} as total amount is zero.");
            }
        }

        $this->info('Monthly invoice generation completed successfully.');
        return 0;
    }
} 