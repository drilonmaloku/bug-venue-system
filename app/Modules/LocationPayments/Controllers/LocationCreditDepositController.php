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
        ]);

        $transaction = LocationCreditTransaction::create([
            'location_id' => $deposit->location_id,
            'location_credit_deposit_id' => $deposit->id,
            'amount' => $deposit->amount,
            'credits' => $deposit->credits,
            'transaction_id' => $request->transaction_id,
            'notes' => $request->notes,
        ]);

        $transaction->processTransaction();

        return response()->json([
            'message' => 'Transaction processed successfully',
            'transaction' => $transaction,
            'deposit' => $deposit->fresh()
        ]);
    }

    public function index(Location $location)
    {
        $deposits = $location->creditDeposits()->latest()->paginate(10);
        return view('pages.location-payments.credit-deposits.index', ['location' => $location, 'deposits' => $deposits]);
    }

    public function process(Location $location, LocationCreditDeposit $deposit)
    {
        return view('pages.location-payments.credit-deposits.process', ['location' => $location, 'deposit' => $deposit]);
    }
} 