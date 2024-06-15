<?php namespace App\Modules\Invoices\Services;

use App\Modules\Invoices\Models\Invoice;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Reservations\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoicesServices
{
    private $logService;

    public function __construct()
    {
        $this->logService = new LogService();
    }

    /**
     * Gets the list of clients
     **/
    public function getAll(Request $request){

        $perPage = $request->has('per_page') ? $request->input('per_page') : 25;
        $query = Invoice::query();
        $query->orderBy('created_at', 'desc');
        return $query->paginate($perPage);

    }

    /**
     * Get Client by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Invoice::find($id);
    }

    
    /**
     * Get Client
     **/
    public function getBasicList(){
        return Invoice::select('id')->get();
    }

    /**
     * Get Clients by ID
     * @param int|array $idp
     **/
    public function getByIds($ids){
        return Invoice::whereIn('id', $ids)->get();
    }

    public function storeInvoice($data, $reservation_id)
    {
        $invoice = Invoice::create([
            "reservation_id" => $reservation_id,
            "amount" => data_get($data, "invoice_amount"),
            "description" => data_get($data, "invoice_description"),
            "date" => data_get($data, "invoice_date"),
        ]);
        $reservation = Reservation::findOrFail($reservation_id);

        $updated_momental_payment = $reservation->total_payment + $invoice->amount;

        $reservation->update(['total_payment' => $updated_momental_payment]);
        if ($invoice) {
            $this->logService->log([
                'message' => 'Sherbimi është krijuar me sukses',
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
            $this->logService->log([
                'message' => 'Sherbimi është fshirë me sukses',
                'context' => Log::LOG_CONTEXT_INVOICE,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }
        return $invoiceDeleted;
    }



   
}
