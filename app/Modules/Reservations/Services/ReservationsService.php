<?php namespace App\Modules\Reservations\Services;

use App\Modules\Clients\Models\Client;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Venues\Models\Venue;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
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
        return $query->paginate($perPage);

    }

    /**
     * Get Venue by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Venue::find($id);
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
        $reservation = Reservation::create([
            "client_id" => $clientId,
            "venue_id" => data_get($request, "venue_id"),
            "date" => data_get($request, "date"),
            "description" => data_get($request, "description"),
            "number_of_guests" => data_get($request, "number_of_guests"),
            "current_payment" => data_get($request, "initial_payment_value"),
            "total_payment" => data_get($request, "total_payment_value"),
        ]);
        if($reservation){
            $this->logService->log([
                'message' => 'Reservation is created successfully',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $reservation;
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
                'message' => 'Venue was updated successfully',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $venueSaved;
    }

    /**
     * Deletes existing venue
     **/
    public function delete(Venue $venue) {
         $previousData = $venue->attributesToArray();
         $venueDeleted = $venue->delete();

         if($venueDeleted){
            $this->logService->log([
                'message' => 'Venue was deleted successfully',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
                'previous_data'=> json_encode($previousData)
            ]);
        }

        return $venue;
    }

}
