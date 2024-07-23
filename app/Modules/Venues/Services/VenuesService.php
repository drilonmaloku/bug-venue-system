<?php namespace App\Modules\Venues\Services;

use App\Modules\Clients\Models\Client;
use App\Modules\Venues\Models\Venue;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;

class VenuesService
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
        $query = Venue::query();
        $query->orderBy('created_at', 'desc');
        return $query->paginate($perPage);
    }

    public function getVenues(){
        return Venue::all();
    }

    /**
     * Get Venue by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Venue::find($id);
    }

    /**
     * Get Venues by IDs
     * @param int|array $id
     **/
    public function getByIds($ids){
        return Venue::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Venue
     **/
    public function store($request)
    {
        $venue = Venue::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "name" => data_get($request, "name"),
            "description" => data_get($request, "description"),
            "capacity" => data_get($request, "capacity"),
        ]);

        if($venue){
            $this->logService->log([
                'message' => 'Salla është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_VENUES,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $venue;
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
                'message' => 'Salla u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_VENUES,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $venueSaved;
    }

    /**
     * Deletes existing venue
     **/
    public function delete(Venue $venue) {
        if($venue->reservations()->count()  > 0) {
            return null;
        }
         $previousData = $venue->attributesToArray();
         $venueDeleted = $venue->delete();

         if($venueDeleted){
            $this->logService->log([
                'message' => 'Salla u fshi me sukses',
                'context' => Log::LOG_CONTEXT_VENUES,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
                'previous_data'=> json_encode($previousData)
            ]);
        }

        return $venue;
    }

}
