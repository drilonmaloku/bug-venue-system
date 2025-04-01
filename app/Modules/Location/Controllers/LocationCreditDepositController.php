<?php

namespace App\Modules\Location\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Modules\Location\Models\LocationCreditDeposit;
use App\Modules\Location\Models\LocationCreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationCreditDepositController extends Controller
{
    public function index(Location $location)
    {
        $deposits = $location->creditDeposits()
            ->with('transactions')
            ->latest()
            ->paginate(10);

        return view('location-payments.credit-deposits.index', compact('location', 'deposits'));
    }

    public function create(Location $location)
    {
        return view('location-payments.credit-deposits.create', compact('location'));
    }

    public function store(Request $request, Location $location)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'credits' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:today',
        ]);

        $deposit = LocationCreditDeposit::create([
            'location_id' => $location->id,
            'amount' => $request->amount,
            'credits' => $request->credits,
            'deposit_number' => 'DEP-' . Str::upper(Str::random(8)),
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('location.credit-deposits.show', [$location, $deposit])
            ->with('success', 'Credit deposit created successfully.');
    }

    public function show(Location $location, LocationCreditDeposit $deposit)
    {
        $deposit->load('transactions');
        return view('location-payments.credit-deposits.show', compact('location', 'deposit'));
    }

    public function downloadPDF(Location $location, LocationCreditDeposit $deposit)
    {
        $pdf = $deposit->generatePDF();
        return $pdf->download("credit-deposit-{$deposit->deposit_number}.pdf");
    }

    public function process(Location $location, LocationCreditDeposit $deposit)
    {
        return view('location-payments.credit-deposits.process', compact('location', 'deposit'));
    }

    public function processTransaction(Request $request, Location $location, LocationCreditDeposit $deposit)
    {
        $request->validate([
            'transaction_id' => 'nullable|string',
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
} 