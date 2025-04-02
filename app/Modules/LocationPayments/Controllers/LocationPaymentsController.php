<?php

namespace App\Modules\LocationPayments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Location\Models\Location;
use Illuminate\Http\Request;
use App\Modules\LocationPayments\Models\LocationCreditDeposit;
use App\Modules\LocationPayments\Models\LocationCreditTransaction;
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
            'notes' => 'nullable|string'
        ]);

        // Create credit deposit record
        $creditDeposit = LocationCreditDeposit::create([
            'location_id' => $location->id,
            'credits' => $validated['credits'],
            'notes' => $validated['notes'],
            'deposit_number' => 'DEP-' . strtoupper(uniqid())
        ]);

        // Create credit transaction record
        LocationCreditTransaction::create([
            'location_id' => $location->id,
            'location_credit_deposit_id' => $creditDeposit->id,
            'credits' => $validated['credits'],
            'notes' => $validated['notes']
        ]);

        // Create invoice for the credit deposit
        $invoice = $location->invoices()->create([
            'credits' => $validated['credits'],
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'status' => 'pending',
            'description' => __('dashboard.credit_deposit') . ': ' . $validated['notes'],
            'due_date' => now()->addDays(30)
        ]);

        // Update location credits
        $location->increment('credits', $validated['credits']);

        return redirect()->route('location-payments.show', ['location' => $location])
            ->with('success', 'Credits deposited successfully and invoice created.');
    }

    public function generateCreditDepositPdf($location_id)
    {
        $location = Location::findOrFail($location_id);
        $deposits = LocationCreditDeposit::where('location_id', $location_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = PDF::loadView('pdf.credit-deposit', [
            'location' => $location,
            'deposits' => $deposits
        ]);

        return $pdf->download('credit-deposits-' . $location->name . '.pdf');
    }
} 