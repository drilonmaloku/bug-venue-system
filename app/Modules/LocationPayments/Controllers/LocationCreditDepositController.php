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

    public function index(Request $request, Location $location = null)
    {
        $query = LocationCreditDeposit::with('location')->latest();

        // Apply filters
        if ($request->filled('deposit_number')) {
            $query->where('deposit_number', 'like', '%' . $request->deposit_number . '%');
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

        // Check if we're accessing the "all deposits" route
        $isAllDepositsRoute = $request->route()->getName() === 'location-payments.credit-deposits.all';

        if ($isAllDepositsRoute && auth()->user()->hasRole('system-admin')) {
            // For system admin viewing all deposits
            if ($request->filled('location')) {
                $query->whereHas('location', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->location . '%');
                });
            }
            
            $deposits = $query->paginate(10);
            
            return view('pages.location-payments.credit-deposits.index', [
                'deposits' => $deposits,
                'is_on_search' => $request->hasAny(['deposit_number', 'location', 'status', 'due_date_from', 'due_date_to']),
                'is_system_admin' => true,
                'location' => $request->filled('location') ? Location::where('name', 'like', '%' . $request->location . '%')->first() : null
            ]);
        } else {
            // For location-specific deposits
            if (!$location) {
                $location = auth()->user()->getCurrentLocation();
                if (!$location) {
                    return redirect()->back()->with('error', 'You do not have access to any location');
                }
            }
            
            // Filter by the specific location
            $query->where('location_id', $location->id);
            $deposits = $query->paginate(10);
            
            return view('pages.location-payments.credit-deposits.index', [
                'deposits' => $deposits,
                'location' => $location,
                'is_on_search' => $request->hasAny(['deposit_number', 'status', 'due_date_from', 'due_date_to']),
                'is_system_admin' => auth()->user()->hasRole('system-admin')
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

    public function transactions(Request $request, Location $location = null)
    {
        $query = LocationCreditTransaction::with('location')->latest();

        // Apply filters
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Check if we're accessing the "all transactions" route
        $isAllTransactionsRoute = $request->route()->getName() === 'location-payments.credit-transactions.all';

        if ($isAllTransactionsRoute && auth()->user()->hasRole('system-admin')) {
            // For system admin viewing all transactions
            if ($request->filled('location')) {
                $query->whereHas('location', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->location . '%');
                });
            }
            
            $transactions = $query->paginate(10);
            
            return view('pages.location-payments.credit-transactions.index', [
                'transactions' => $transactions,
                'is_on_search' => $request->hasAny(['location', 'payment_method', 'date_from', 'date_to']),
                'is_system_admin' => true
            ]);
        } else {
            // For location-specific transactions
            if (!$location) {
                $location = auth()->user()->getCurrentLocation();
                if (!$location) {
                    return redirect()->back()->with('error', 'You do not have access to any location');
                }
            }
            
            // Filter by the specific location
            $query->where('location_id', $location->id);
            $transactions = $query->paginate(10);
            
            return view('pages.location-payments.credit-transactions.index', [
                'transactions' => $transactions,
                'location' => $location,
                'is_on_search' => $request->hasAny(['payment_method', 'date_from', 'date_to']),
                'is_system_admin' => auth()->user()->hasRole('system-admin')
            ]);
        }
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