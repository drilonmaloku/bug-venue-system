<?php

namespace App\Modules\LocationPayments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Location\Models\Location;
use App\Modules\LocationPayments\Models\LocationInvoice;
use App\Modules\LocationPayments\Models\LocationPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Jobs\GenerateInvoicesForCreditDeposits;

class LocationInvoiceController extends Controller
{
    public function index(Request $request, Location $location = null)
    {
        // Check if we're accessing the "all invoices" route
        $isAllInvoicesRoute = $request->route()->getName() === 'location-payments.invoices.all';

        $query = LocationInvoice::with('location')->latest();

        // Apply filters
        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('due_date_from')) {
            $query->whereDate('due_date', '>=', $request->due_date_from);
        }

        if ($request->filled('due_date_to')) {
            $query->whereDate('due_date', '<=', $request->due_date_to);
        }

        if ($isAllInvoicesRoute && auth()->user()->hasRole('system-admin')) {
            // For system admin viewing all invoices
            $selectedLocation = null;
            if ($request->filled('location')) {
                $query->whereHas('location', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->location . '%');
                });
                
                // Try to find the exact location if the search term matches a location name
                $selectedLocation = Location::where('name', 'like', '%' . $request->location . '%')->first();
            }
            
            $invoices = $query->paginate(10);
            
            return view('pages.location-payments.invoices.index', [
                'invoices' => $invoices,
                'is_on_search' => $request->hasAny(['invoice_number', 'location', 'status', 'due_date_from', 'due_date_to']),
                'is_system_admin' => true,
                'location' => $selectedLocation
            ]);
        } else {
            // For location-specific invoices
            if (!$location) {
                $location = auth()->user()->getCurrentLocation();
                if (!$location) {
                    return redirect()->back()->with('error', 'You do not have access to any location');
                }
            }
            
            // Filter by the specific location
            $query->where('location_id', $location->id);
            $invoices = $query->paginate(10);
            
            return view('pages.location-payments.invoices.index', [
                'invoices' => $invoices,
                'location' => $location,
                'is_on_search' => $request->hasAny(['invoice_number', 'status', 'due_date_from', 'due_date_to']),
                'is_system_admin' => auth()->user()->hasRole('system-admin')
            ]);
        }
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

    public function processStore(Request $request, Location $location, LocationInvoice $invoice)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date'
        ]);

        // Create payment record
        $invoice->payments()->create([
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date
        ]);

        // If the total amount paid equals or exceeds the invoice amount, mark as paid
        $totalPaid = $invoice->payments()->sum('amount_paid');
        if ($totalPaid >= $invoice->amount) {
            $invoice->markAsPaid();
        }

        return redirect()->route('location.invoices.show', [$location, $invoice])
            ->with('success', 'Payment processed successfully.');
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

    public function generateInvoicesForCreditDeposits()
    {
        // Dispatch the job to generate invoices for credit deposits
        GenerateInvoicesForCreditDeposits::dispatch();

        return redirect()->back()->with('success', 'Invoice generation job has been queued.');
    }
} 
