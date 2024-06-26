<?php namespace App\Modules\Reservations\Services;


use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Reservations\Models\Invoice;

class InvoicesServices
{
    private $logService;

    public function __construct()
    {
        $this->logService = new LogService();
    }

    public function getByID($id){
        return Invoice::find($id);
    }


    public function store($data, $reservation_id)
    {
        $invoice = Invoice::create([
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
        }
        return $invoiceDeleted;
    }



   
}
