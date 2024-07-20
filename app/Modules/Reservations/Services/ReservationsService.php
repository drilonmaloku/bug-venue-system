<?php namespace App\Modules\Reservations\Services;

use App\Models\User;
use App\Modules\Clients\Models\Client;
use App\Modules\Clients\Services\ClientsService;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Venues\Models\Venue;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Reservations\Models\PricingStatusTracking;
use App\Modules\Reservations\Models\ReservationStaff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationsService
{
    private $logService;
    private $clientService;
    public function __construct()
    {
        $this->logService = new LogService();
        $this->clientService = new ClientsService();

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

           // Handle date filter
           if ($request->has('date') && $request->input('date') != '') {
            $date = $request->input('date');
            $formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
            $query->where('date', $formattedDate); // Use the 'date' column for filtering
        }
        // Handle created_at filter
        if ($request->has('created_at') && $request->input('created_at') != '') {
            $createdAt = $request->input('created_at');
            $query->whereDate('created_at', $createdAt);
        }

         // Venue filter
    if ($request->has('venue') && $request->input('venue') != '') {
        $venueId = $request->input('venue');
        $query->where('venue_id', $venueId); // Adjust 'venue_id' according to your actual column name
    }

    if ($request->has('menu') && $request->input('menu') != '') {
        $menuId = $request->input('menu');
        $query->where('menu_id', $menuId); // Adjust 'venue_id' according to your actual column name
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
        $date =  $request->input('date');
        $venueData = explode(",", $request->input('reservation'));
        $reservation = Reservation::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
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
            "staff_expenses" => 0,
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
     * Updates existing Reservation
     **/
    public function update($request, Reservation $reservation) {

        // dd($request);
        // Update the reservation with the new data from the request
        $reservation->number_of_guests = $request->input('number_of_guests');
        $reservation->menu_price = $request->input('menu_price');
        $reservation->menager_id = $request->input('menager_id');
        $reservation->staff_expenses = $request->input('staff_expenses');
        $reservation->date = $request->input('date');
        $reservation->description = $request->input('description');


        $client = $this->clientService->getByID($reservation->client->id);
        $this->clientService->update($request, $client);

        $venueData = explode(",", $request->input('reservation'));
        $reservation->venue_id = $venueData[0];
        $reservation->reservation_type = $venueData[1];
    
        // Calculate total payment
        $numberOfGuests = intval($request->input('number_of_guests'));
        $menuPrice = doubleval($request->input('menu_price'));
        $totalPayment = $numberOfGuests * $menuPrice;
        $reservation->total_payment = $totalPayment;
    
    
    
        $reservationSaved = $reservation->save();
    
        if ($reservationSaved) {
            $this->logService->log([
                'message' => 'Rezervimi u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_RESERVATIONS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }
    
        return $reservationSaved;
    }
    
    /**
     * Deletes existing Reservation
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



    public function recalculatePrice(Reservation $reservation) {
//        $reservationTotalMenuPrice = $reservation->number_of_guests * $reservation->menu_price;
//        $previousData = $reservation->attributesToArray();
//        $reservationDeleted = $reservation->delete();
//
//
//        if($reservationDeleted){
//            $this->logService->log([
//                'message' => 'Rezervimi është fshirë me sukses',
//                'context' => Log::LOG_CONTEXT_CLIENTS,
//                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
//            ]);
//        }
//        return $reservationDeleted;
    }

   public function storePricingTracking(Reservation $reservation, $numberOfGuests,$newMenuPrice,$totalInvoiceSum,$totalDiscountSum)
   {
       return PricingStatusTracking::create([
           "location_id" => auth()->user()->getCurrentLocationId(),
           'user_id' => auth()->user()->id,
           'number_of_guests' => $numberOfGuests,
           'menu_price' => $newMenuPrice,
           'price' => $newMenuPrice,
           'total_price' => ($numberOfGuests * $newMenuPrice) + $totalInvoiceSum - $totalDiscountSum,
           'total_invoice_price'=>$totalInvoiceSum,
           'total_discount_price'=>$totalDiscountSum,
           'reservation_id' => $reservation->id,
       ]);
   }

   
}
