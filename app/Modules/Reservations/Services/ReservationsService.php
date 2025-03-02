<?php namespace App\Modules\Reservations\Services;

use App\Modules\Users\Services\UsersService;
use App\Modules\Clients\Services\ClientsService;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Reservations\Models\PricingStatusTracking;
use App\Modules\Reservations\Notifications\ReservationAddedNotification;
use App\Modules\Reservations\Notifications\ReservationDeletedNotifiaction;
use App\Modules\Reservations\Notifications\ReservationUpdatedNotification;
use Illuminate\Support\Facades\Notification;




class ReservationsService
{
    private $logService;
    private $clientService;
      private $usersService;
    public function __construct()
    {
        $this->logService = new LogService();
        $this->clientService = new ClientsService();
        $this->usersService = app()->make(UsersService::class);

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

        if ($request->has('created_at') && $request->input('created_at') != '') {
            $createdAt = $request->input('created_at');
            $query->whereDate('created_at', $createdAt);
        }

        if ($request->has('venue') && $request->input('venue') != '') {
            $venueId = $request->input('venue');
            $query->where('venue_id', $venueId); // Adjust 'venue_id' according to your actual column name
        }

        if ($request->has('status') && $request->input('status') != '') {
            $venueId = $request->input('status');
            $query->where('status', $request->input('status'));
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
        return Reservation::whereIn('id', $ids)->get();
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
            "manager_id" => $request->input("manager_id"),
            "decor_id" => $request->input("decor_id"),
            "menu_price" => $request->input("menu_price"),
            "contract_date" => $request->input("contract_date"),
            "reservation_type" => $venueData[1],
            "date" => $date,
            "description" => $request->input("description"),
            "number_of_guests" =>$numberOfGuests,
            "current_payment" => $request->input("initial_payment_value"),
            "total_payment" => $totalPayment,
            "menu_contents" => $request->input("menu_contents"),
            "staff_expenses" => 0,
            "planning" => $request->input("planning") ? json_encode($request->input("planning")) : null,
        ]);
        if($reservation){
            $this->logService->log([
                'message' => 'Rezervimi është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_RESERVATIONS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
             Notification::send(
                 $this->usersService->getUsersForNotifications('reservation-added'),
                 new ReservationAddedNotification(
                     $reservation,
                     auth()->user()
                 )
             );
        }

        return $reservation;
    }

    /**
     * Updates existing Reservation
     **/
    public function update($request, Reservation $reservation) {

        
        // Update the reservation with the new data from the request
        $reservation->number_of_guests = $request->input('number_of_guests');
        $reservation->menu_price = $request->input('menu_price');
        $reservation->manager_id = $request->input('manager_id');
        $reservation->decor_id = $request->input('decor_id');
        $reservation->staff_expenses = $request->input('staff_expenses');
        $reservation->date = $request->input('date');
        $reservation->description = $request->input('description');
        $reservation->menu_contents = $request->input('menu_contents');
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
            Notification::send(
                 $this->usersService->getUsersForNotifications('reservation-updated'),
                 new ReservationUpdatedNotification(
                     $reservation,
                     auth()->user()
                 )
             );
        }
    
        return $reservationSaved;
    }

    /**
     * Updates existing Reservation status
     **/
    public function updateStatus($request, Reservation $reservation) {

        $reservation->status = $request->input('status');

        $reservationSaved = $reservation->saveQuietly();

        if ($reservationSaved) {
            $this->logService->log([
                'message' => 'Statusi i rezervimit u përditësua me sukses në të: '.$reservation->statusLabel,
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
           Notification::send(
                 $this->usersService->getUsersForNotifications('reservation-deleted'),
                 new ReservationDeletedNotifiaction(
                     $reservation,
                     auth()->user()
                 )
             );
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

    public function generateReservationContract($reservation,$contractContent){
        $placeholders = [
            '{{data}}' => $reservation->date,
            '{{klienti}}' => $reservation->client->name,
            '{{klienti_telefoni}}' => $reservation->client->phone_number,
            '{{salla}}' => $reservation->venue->name,
            '{{menu}}' => $reservation->menu->name,
            '{{id}}' => $reservation->id,
            '{{qmimi_menus}}' => $reservation->menu_price,
            '{{numri_personav}}' => $reservation->number_of_guests,
            '{{pagesa_totale}}' => $reservation->total_payment,
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $contractContent);

    }

    public function updatePlanning($request,$reservation){
        $reservation->planning = json_encode($request->input('planning'));
        $reservation->save();
        return $reservation;
    }

    public function updateNotes($request,$reservation){
        $reservation->notes = json_encode($request->input('notes'));
        $reservation->save();
        return $reservation;
    }
   
}
