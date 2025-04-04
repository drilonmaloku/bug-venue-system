<?php

namespace App\Modules\LocationPayments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Location\Models\Location;
use Illuminate\Http\Request;
use App\Modules\LocationPayments\Models\LocationCreditDeposit;
use App\Modules\LocationPayments\Models\LocationCreditTransaction;
use App\Modules\LocationPayments\Models\LocationInvoice;
use Barryvdh\DomPDF\Facade\Pdf;

class LocationPaymentsController extends Controller
{
    public function index()
    {
        $locations = Location::with(['invoices.payments', 'creditDeposits'])->get();
        return view('pages.location-payments.index', ['locations' => $locations]);
    }

    public function show(Location $location)
    {
        $location->load(['invoices.payments', 'creditDeposits']);
        return view('pages.location-payments.show', ['location' => $location]);
    }

    public function payments(Location $location)
    {
        $location->load(['invoices.payments', 'creditDeposits.transactions']);
        return view('pages.location-payments.payments', ['location' => $location]);
    }

    public function createCreditDeposit($location_id)
    {
        $location = Location::findOrFail($location_id);
        return view('pages.location-payments.credit-deposits.create', ['location' => $location]);
    }

    public function storeCreditDeposit(Request $request, $location_id)
    {
        $location = Location::findOrFail($location_id);
        
        $validated = $request->validate([
            'credits' => 'required|integer|min:0',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,gift'
        ]);

        // Create credit deposit record
        $creditDeposit = LocationCreditDeposit::create([
            'location_id' => $location->id,
            'credits' => $validated['credits'],
            'amount' => $validated['credits'],
            'notes' => $validated['notes'],
            'deposit_number' => 'DEP-' . strtoupper(uniqid()),
            'payment_method' => $validated['payment_method'],
            'due_date' => now()->addDays(30), // Set due date to 30 days from now
            'status' => 'pending'
        ]);

        // Create credit transaction record
        LocationCreditTransaction::create([
            'location_id' => $location->id,
            'location_credit_deposit_id' => $creditDeposit->id,
            'credits' => $validated['credits'],
            'amount' => $validated['credits'],
            'notes' => $validated['notes'],
            'payment_method' => $validated['payment_method']
        ]);

        // Update location credits
        $location->increment('credits', $validated['credits']);

        return redirect()->route('location-payments.show', ['location' => $location->id])
            ->with('success', 'Credits deposited successfully.');
    }

   
} 