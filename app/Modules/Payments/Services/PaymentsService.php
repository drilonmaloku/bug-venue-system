<?php

namespace App\Modules\Payments\Services;

use App\Modules\Payments\Notifications\PaymentsDeletedNotification;
use App\Modules\Payments\Notifications\PaymentAddedNotification;
use App\Modules\Payments\Models\Payment;
use App\Modules\Payments\Notifications\PaymentUpdatedNotification;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Users\Services\UsersService;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Support\Facades\Notification;


class PaymentsService
{
    private $logService;
    private $usersService;

    public function __construct()
    {
        $this->logService = app()->make(LogService::class);
        $this->usersService = app()->make(UsersService::class);
    }

    /**
     * Gets the list of payments
     **/
    public function getAll(Request $request)
    {
        $perPage = $request->has('per_page') ? $request->input('per_page') : 25;
        $query = Payment::query();


        if ($request && $request->has("search") && $request->input("search") != '') {
            $searchTerm = '%' . $request->input("search") . '%';

            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('value', 'LIKE', $searchTerm)
                    ->orWhere('notes', 'LIKE', $searchTerm);
            });

            $query->orWhereHas('client', function ($clientQuery) use ($searchTerm) {
                $clientQuery->where('name', 'LIKE', $searchTerm);
            });
        }

        // Modified payment method filter
        if ($request->has('payment_method') && $request->input('payment_method') != '') {
            if ($request->input('payment_method') == '1') {
                // For Cash payments, check for both 1 and null
                $query->where(function($q) {
                    $q->where('payment_method', 1)
                      ->orWhereNull('payment_method');
                });
            } else {
                // For Bank payments
                $query->where('payment_method', $request->input('payment_method'));
            }
        }

        if ($request->filled('start_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            if ($request->filled('end_date')) {
                $query->whereDate('date', '>=', $startDate)
                      ->whereDate('date', '<=', $endDate);
            } else {
                $query->whereDate('date', '=', $startDate);
            }
        }

        // Handle date filter
        if ($request->has('date') && $request->input('date') != '') {
            $date = $request->input('date');
            $formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
            $query->where('date', $formattedDate); // Use the 'date' column for filtering
        }

        $query->orderBy('created_at', 'desc');
        return $query->paginate($perPage);
    }

    /**
     * Get Payment by ID
     * @param int|array $id
     **/
    public function getByID($id)
    {
        return Payment::find($id);
    }

    /**
     * Get Payments by ID
     * @param int|array $id
     **/
    public function getByIds($ids)
    {
        return Payment::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Payment
     **/
    public function store($data, $reservation_id, $client_id)
    {
        $payment = Payment::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "reservation_id" => $reservation_id,
            "client_id" => $client_id,
            "value" => data_get($data, "initial_payment_value"),
            "notes" => data_get($data, "payment_notes"),
            "payment_method" => data_get($data, "payment_method"),
            "date" => data_get($data, "payment_date"),
        ]);

        if ($payment) {
            $this->logService->log([
                'message' => 'Pagesa është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_PAYMENTS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $payment;
    }

    public function storePayment($data, $reservation_id, $client_id)
    {
        $payment = Payment::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "reservation_id" => $reservation_id,
            "client_id" => $client_id,
            "value" => data_get($data, "initial_payment_value"),
            "notes" => data_get($data, "payment_notes"),
            "payment_method" => data_get($data, "payment_method"),
            "date" => data_get($data, "payment_date"),
        ]);
        $reservation = Reservation::findOrFail($reservation_id);

        $updated_momental_payment = $reservation->current_payment + $payment->value;

        $reservation->update(['current_payment' => $updated_momental_payment]);
        if ($payment) {
            $this->logService->log([
                'message' => 'Pagesa është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_PAYMENTS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
             Notification::send(
                 $this->usersService->getUsersForNotifications('payment-added'),
                 new PaymentAddedNotification(
                     $payment,
                     auth()->user()
                 )
             );
        }

        return $payment;
    }

    /**
     * Updates existing Venue
     **/
    public function update($request, Payment $payment)
    {
        $previousData = $payment->attributesToArray();
        $payment->value = $request->input('value');
        $payment->date = $request->input('date');
        $payment->notes = $request->input('notes');
        $payment->payment_method = $request->input('payment_method');
        $paymentSaved = $payment->save();

        if ($paymentSaved) {
            $this->logService->log([
                'message' => 'Pagesa u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
                'updated_data' => json_encode($payment)
            ]);
            Notification::send(
                $this->usersService->getUsersForNotifications('payment-updated'),
                new PaymentUpdatedNotification(
                    $payment,
                    auth()->user()
                )
            );
        }

        return $paymentSaved;
    }

    public function getByReservationID($reservationId)
{
    return Payment::where('reservation_id', $reservationId)->get();
}


    public function delete(Payment $payment)
    {
        $previousData = $payment->attributesToArray();
        $paymentDeleted = $payment->delete();


        if ($paymentDeleted) {
            $this->logService->log([
                'message' => 'Pagesa është fshirë me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
            Notification::send(
                 $this->usersService->getUsersForNotifications('payment-deleted'),
                 new PaymentsDeletedNotification(
                     $payment,
                     auth()->user()
                 )
             );
        }
        return $paymentDeleted;
    }
}
