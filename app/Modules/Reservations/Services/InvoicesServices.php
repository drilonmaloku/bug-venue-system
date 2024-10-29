<?php namespace App\Modules\Reservations\Services;

use App\Modules\Users\Services\UsersService;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Reservations\Notifications\InvoicesAddedNotification;
use App\Modules\Reservations\Notifications\InvoicesDeletedNotification;
use App\Modules\Reservations\Models\Invoice;
use Illuminate\Support\Facades\Notification;


class InvoicesServices
{
    private $logService;
    private $usersService;
    public function __construct()
    {
        $this->logService = new LogService();
        $this->usersService = app()->make(UsersService::class);
    }

    public function getByID($id){
        return Invoice::find($id);
    }


    public function store($data, $reservation_id)
    {
        $invoice = Invoice::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "reservation_id" => $reservation_id,
            "amount" => data_get($data, "invoice_amount"),
            "description" => data_get($data, "invoice_description"),
            "date" => data_get($data, "invoice_date"),
        ]);
        if ($invoice) {
            $this->logService->log([
                'message' => 'Shërbimi është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_INVOICE,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
              Notification::send(
                 $this->usersService->getUsersForNotifications(),
                 new InvoicesAddedNotification(
                     $invoice,
                     auth()->user()
                 )
             );
        }

        return $invoice;
    }

    public function update($request, Invoice $invoice)
    {
        $previousData = $invoice->attributesToArray();
        $invoice->amount = $request->input('amount');
        $invoice->date = $request->input('date');
        $invoice->description = $request->input('description');
        $invoiceSaved = $invoice->save();

        if ($invoiceSaved) {

            $invoice->reservation->updateReservationTracking($invoice->reservation);
            $this->logService->log([
                'message' => 'Sherbimi u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_INVOICE,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
                'updated_data' => json_encode($invoice)
            ]);
        }

        return $invoiceSaved;
    }


   public function delete(Invoice $invoice)
{
    $previousData = $invoice->attributesToArray();
    $invoiceDeleted = $invoice->delete();

    if ($invoiceDeleted) {
        $invoice->reservation->updateReservationTracking($invoice->reservation);

        $this->logService->log([
            'message' => 'Sherbimi është fshirë me sukses',
            'context' => Log::LOG_CONTEXT_INVOICE,
            'ttl' => Log::LOG_TTL_THREE_MONTHS,
            'previous_data' => json_encode($previousData),
        ]);

        Notification::send(
            $this->usersService->getUsersForNotifications(),
            new InvoicesDeletedNotification(
                $invoice,
                auth()->user()
            )
        );
    }

    return $invoiceDeleted;
}




   
}
