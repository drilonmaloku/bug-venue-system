<?php

namespace App\Modules\LocationPayments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Location\Models\Location;
use App\Modules\LocationPayments\Models\LocationCreditDeposit;
use App\Modules\LocationPayments\Models\LocationCreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationCreditDepositController extends Controller
{
    public function create(Location $location)
    {
        return view('pages.location-payments.credit-deposits.create', ['location' => $location]);
    }

    public function show(Location $location, LocationCreditDeposit $deposit)
    {
        return view('pages.location-payments.credit-deposits.show', ['location' => $location, 'deposit' => $deposit]);
    }

    public function downloadPDF(LocationCreditDeposit $deposit)
    {
        $pdf = $deposit->generatePDF();
        return $pdf->download("credit-deposit-{$deposit->deposit_number}.pdf");
    }

    public function processTransaction(Request $request, LocationCreditDeposit $deposit)
    {
        $request->validate([
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,gift'
        ]);

        $transaction = LocationCreditTransaction::create([
            'location_id' => $deposit->location_id,
            'location_credit_deposit_id' => $deposit->id,
            'amount' => $deposit->amount,
            'credits' => $deposit->credits,
            'transaction_id' => $request->transaction_id,
            'notes' => $request->notes,
            'payment_method' => $request->payment_method
        ]);

        $transaction->processTransaction();

        return response()->json([
            'message' => 'Transaction processed successfully',
            'transaction' => $transaction,
            'deposit' => $deposit->fresh()
        ]);
    }

    public function index(Location $location = null)
    {
        if (auth()->user()->hasRole('system-admin')) {
            // For system admin, show all deposits from all locations
            $deposits = LocationCreditDeposit::with('location')
                ->latest()
                ->paginate(10);
            
            return view('pages.location-payments.credit-deposits.index', [
                'deposits' => $deposits,
                'is_system_admin' => true
            ]);
        } else {
            // For regular users, show only deposits from their location
            if (!$location) {
                $location = auth()->user()->getCurrentLocation();
                if (!$location) {
                    return redirect()->back()->with('error', 'You do not have access to any location');
                }
            }
            
            $deposits = $location->creditDeposits()
                ->latest()
                ->paginate(10);
            
            return view('pages.location-payments.credit-deposits.index', [
                'deposits' => $deposits,
                'location' => $location,
                'is_system_admin' => false
            ]);
        }
    }

    public function process(Location $location, LocationCreditDeposit $deposit)
    {
        return view('pages.location-payments.credit-deposits.process', ['location' => $location, 'deposit' => $deposit]);
    }

    public function processStore(Request $request, Location $location, LocationCreditDeposit $deposit)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $transaction = LocationCreditTransaction::create([
            'location_id' => $location->id,
            'location_credit_deposit_id' => $deposit->id,
            'amount' => $deposit->amount,
            'credits' => $deposit->credits,
            'notes' => $request->notes,
        ]);

        $transaction->processTransaction();

        return redirect()->route('location.credit-deposits.show', [$location, $deposit])
            ->with('success', 'Payment processed successfully.');
    }

    public function transactions(Location $location)
    {
        $transactions = LocationCreditTransaction::where('location_id', $location->id)
            ->with(['deposit', 'location'])
            ->latest()
            ->paginate(10);

        return view('pages.location-payments.credit-transactions.index', [
            'location' => $location,
            'transactions' => $transactions
        ]);
    }

    public function markAsCompleted(Location $location, LocationCreditDeposit $deposit)
    {
        if ($deposit->isCompleted()) {
            return redirect()->route('location.credit-deposits.show', [$location, $deposit])
                ->with('error', 'Credit deposit is already marked as completed.');
        }

        $deposit->markAsCompleted();

        return redirect()->route('location.credit-deposits.show', [$location, $deposit])
            ->with('success', 'Credit deposit marked as completed successfully.');
    }
} 