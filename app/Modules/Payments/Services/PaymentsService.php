<?php namespace App\Modules\Payments\Services;

use App\Modules\Clients\Models\Client;
use App\Modules\Payments\Models\Payment;
use App\Modules\Venues\Models\Venue;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentsService
{
    private $logService;

    public function __construct()
    {
        $this->logService = new LogService();
    }

    /**
     * Gets the list of venues
     **/
    public function getAll(Request $request){
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

       // Handle date filter
       if ($request->has('date') && $request->input('date') != '') {
        $date = $request->input('date');
        $formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
        $query->where('date', $formattedDate); // Use the 'date' column for filtering
    }


        return $query->paginate($perPage);

    }

    /**
     * Get Venue by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Payment::find($id);
    }

    /**
     * Get Payments by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return Payment::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Venue
     **/
    public function store($data,$reservation_id,$client_id)
    {
        $payment = Payment::create([
            "reservation_id" => $reservation_id,
            "client_id" => $client_id,
            "value" => data_get($data, "initial_payment_value"),
            "notes" => data_get($data, "payment_notes"),
            "date" => Carbon::createFromFormat('Y-m-d', data_get($data, "payment_date"))->format('d-m-Y'),
        ]);

        if($payment){
            $this->logService->log([
                'message' => 'Pagesa është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_PAYMENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $payment;
    }

    /**
     * Updates existing Venue
     **/
    public function update($request, Venue $venue) {
        $venue->name = $request->input('name');
        $venue->description = $request->input('description');
        $venue->capacity = $request->input('capacity');
        $venueSaved = $venue->save();

        if($venueSaved){
            $this->logService->log([
                'message' => 'Pagesa u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $venueSaved;
    }

    /**
     * Deletes existing venue
     **/
    // public function delete(Payment $payment) {
    //      $previousData = $venue->attributesToArray();
    //      $venueDeleted = $venue->delete();

    //      if($venueDeleted){
    //         $this->logService->log([
    //             'message' => 'Pagesa u fshi me sukses',
    //             'context' => Log::LOG_CONTEXT_CLIENTS,
    //             'ttl'=> Log::LOG_TTL_THREE_MONTHS,
    //             'previous_data'=> json_encode($previousData)
    //         ]);
    //     }

    //     return $venue;
    // }



    public function delete(Payment $payment) {
        $previousData = $payment->attributesToArray();
        $paymentDeleted = $payment->delete();


        if($paymentDeleted){
           $this->logService->log([
               'message' => 'Pagesa është fshirë me sukses',
               'context' => Log::LOG_CONTEXT_CLIENTS,
               'ttl'=> Log::LOG_TTL_THREE_MONTHS,
           ]);
       }
       return $paymentDeleted;
   }

}
