<?php namespace App\Modules\Reservations\Services;

use App\Modules\Clients\Models\Client;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Venues\Models\Venue;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Reservations\Models\PricingStatusTracking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationsService
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
        $query = Reservation::query();


        if ($request && $request->has("search") && $request->input("search") != '') {
            $searchTerm = '%' . $request->input("search") . '%';

            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('description', 'LIKE', $searchTerm)
                    ->orWhere('current_payment', 'LIKE', $searchTerm);
            });
        }
        $query->orderBy('created_at', 'desc');
        return $query->paginate($perPage);

    }

    /**
     * Get Venue by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Reservation::find($id);
    }

    /**
     * Get Clients by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return Client::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Reservation
     **/
    public function store($request,$clientId)
    {
        $numberOfGuests = intval($request->input('number_of_guests'));
        $menuPrice = doubleval($request->input('menu_price'));
        $totalPayment = $numberOfGuests * $menuPrice;
        $date = Carbon::createFromFormat('Y-m-d', $request->input('date'))->format('d-m-Y');
        $venueData = explode(",", $request->input('reservation'));
        $reservation = Reservation::create([
            "client_id" => $clientId,
            "venue_id" => $venueData[0],
            "menu_id" => $request->input("menu_id"),
            "menager_id" => $request->input("menager_id"),
            "menu_price" => $request->input("menu_price"),
            "reservation_type" => $venueData[1],
            "date" => $date,
            "description" => $request->input("description"),
            "number_of_guests" =>$numberOfGuests,
            "current_payment" => $request->input("initial_payment_value"),
            "total_payment" => $totalPayment,
            "menu_contents" => 'test',
        ]);
        if($reservation){
            $this->logService->log([
                'message' => 'Rezervimi është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_RESERVATIONS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $reservation;
    }

    /**
     * Updates existing Venue
     **/
    public function update($request, Reservation $reservation) {
        // Update the reservation with the new data from the request
        $reservation->number_of_guests = $request->input('number_of_guests');
        $reservation->menu_price = $request->input('menu_price');
        $reservation->menager_id = $request->input('menager_id');


        $numberOfGuests = intval($request->input('number_of_guests'));
        $menuPrice = doubleval($request->input('menu_price'));
        $totalPayment = $numberOfGuests * $menuPrice;
        // Save the updated reservation
        $reservation->total_payment = $totalPayment;
        $reservationSaved = $reservation->save();
    
        if($reservationSaved) {
            $this->logService->log([
                'message' => 'Rezervimi u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_RESERVATIONS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }
    
        return $reservationSaved;
    }

    /**
     * Deletes existing venue
     **/
    public function delete(Reservation $reservation) {
        $previousData = $reservation->attributesToArray();
        $reservationDeleted = $reservation->delete();


        if($reservationDeleted){
           $this->logService->log([
               'message' => 'Rezervimi është fshirë me sukses',
               'context' => Log::LOG_CONTEXT_CLIENTS,
               'ttl'=> Log::LOG_TTL_THREE_MONTHS,
           ]);
       }
       return $reservationDeleted;
   }




   public function storePricingTracking(Reservation $reservation, $numberOfGuests,$newMenuPrice)
   {
       try {
           return PricingStatusTracking::create([
               'user_id' => auth()->user()->id,
               'number_of_guests' => $numberOfGuests,
               'menu_price' => $newMenuPrice,
               'price' => $newMenuPrice,
               'total_price' => intval($numberOfGuests) * doubleval($newMenuPrice),
               'reservation_id' => $reservation->id,
           ]);
       } catch (\Exception $e) {
           
           Log::error('Failed to store pricing tracking: ' . $e->getMessage());
           return null; 
       }
   }

}
