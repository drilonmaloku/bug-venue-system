<?php namespace App\Modules\Reservations\Services;

use App\Modules\Clients\Models\Client;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Venues\Models\Venue;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Reservations\Models\Discount;
use App\Modules\Reservations\Models\PricingStatusTracking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DiscountReservationsServices
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
        $query = Discount::query();


      
        return $query;

    }

    /**
     * Get Venue by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Discount::find($id);
    }

    /**
     * Get Clients by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return Discount::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Discount
     **/

         //Discount

    public function storeDiscount($data, $reservation_id)
    {
        $discount = Discount::create([
            "reservation_id" => $reservation_id,
            "amount" => data_get($data, "discount_amount"),
            "description" => data_get($data, "discount_description"),
            "date" => data_get($data, "discount_date"),
        ]);
        $reservation = Reservation::findOrFail($reservation_id);

        $updated_momental_payment = $reservation->total_payment - $discount->amount;

        $reservation->update(['total_payment' => $updated_momental_payment]);
        if ($discount) {
            $this->logService->log([
                'message' => 'Zbritja është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_INVOICE,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $discount;
    }




    public function update($request, Discount $discount)
    {
        $previousData = $discount->attributesToArray();
        $discount->discount = $request->input('discount');
        $discount->date = $request->input('date');
        $discount->description = $request->input('description');
        $discountSaved = $discount->save();
    
        if ($discountSaved) {
            $this->logService->log([
                'message' => 'Zbritja u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_INVOICE,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
                'updated_data' => json_encode($discount)
            ]);
        }
    
        return $discountSaved;
    }
    



    
    public function delete(Discount $discount)
    {
        $previousData = $discount->attributesToArray();
        $discountDeleted = $discount->delete();


        if ($discountDeleted) {
            $this->logService->log([
                'message' => 'Zbritja është fshirë me sukses',
                'context' => Log::LOG_CONTEXT_INVOICE,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }
        return $discountDeleted;
    }
}
