<?php

namespace App\Modules\LocationPayments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Location\Models\Location;
use App\Modules\LocationPayments\Models\LocationInvoice;
use App\Modules\LocationPayments\Models\LocationPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationInvoiceController extends Controller
{
    public function index(Location $location)
    {
        $invoices = $location->invoices()->latest()->paginate(10);
        return view('pages.location-payments.invoices.index', ['location' => $location, 'invoices' => $invoices]);
    }

    public function create(Location $location)
    {
        return view('pages.location-payments.invoices.create', ['location' => $location]);
    }

    public function store(Request $request, Location $location)
    {
        $request->validate([
            'credits' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:today',
        ]);

        $invoice = LocationInvoice::create([
            'location_id' => $location->id,
            'credits' => $request->credits,
            'invoice_number' => 'INV-' . Str::upper(Str::random(8)),
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('location.invoices.show', [$location, $invoice])
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Location $location, LocationInvoice $invoice)
    {
        $invoice->load('payments');
        return view('pages.location-payments.invoices.show', ['location' => $location, 'invoice' => $invoice]);
    }

    public function downloadPDF(Location $location, LocationInvoice $invoice)
    {
        $pdf = $invoice->generatePDF();
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function processPayment(Request $request, Location $location, LocationInvoice $invoice)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        $payment = LocationPayment::create([
            'location_invoice_id' => $invoice->id,
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date,
        ]);

        $payment->processPayment();

        return redirect()->route('location.invoices.show', [$location, $invoice])
            ->with('success', 'Payment processed successfully.');
    }

    public function process(Location $location, LocationInvoice $invoice)
    {
        return view('pages.location-payments.invoices.process', ['location' => $location, 'invoice' => $invoice]);
    }

    public function markAsPaid(Location $location, LocationInvoice $invoice)
    {
        if ($invoice->isPaid()) {
            return redirect()->route('location.invoices.show', [$location, $invoice])
                ->with('error', 'Invoice is already marked as paid.');
        }

        $invoice->markAsPaid();

        return redirect()->route('location.invoices.show', [$location, $invoice])
            ->with('success', 'Invoice marked as paid successfully.');
    }
} 