<?php namespace App\Modules\Location\Services;

use App\Modules\Expenses\Models\Expense;
use App\Modules\Location\Models\Location;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class LocationServices
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

      return Location::all();

    }

    /**
     * Get Client by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Location::find($id);
    }

    
    /**
     * Get Client
     **/
    public function getBasicList(){
        return Location::select('id', 'name')->get();
    }

    /**
     * Get Clients by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return Location::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Client
     **/
    public function store($data)
    {
        $location = Location::create([
            "name" => data_get($data, "name"),
            "owner_id" => data_get($data, "owner_id"),
            "slug" => data_get($data, "slug"),
        ]);

        if($location){
            $this->logService->log([
                'message' => 'Location është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $location;
    }

    /**
     * Updates existing client
     **/
    public function update($request, Location $location) {
        $location->name = $request->input('name');
        $location->slug = $request->input('slug');

        $locationSaved = $location->save();

        if($locationSaved){
            $this->logService->log([
                'message' => 'Location u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $location;
    }
    public function deactive($request, Location $location) {
        $location->deactivated_at = now();

        $locationSaved = $location->save();

        if($locationSaved){
            $this->logService->log([
                'message' => 'Location u deaktivizua me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $location;
    }



    public function active($request, Location $location) {
        $location->deactivated_at = null;

        $locationSaved = $location->save();

        if($locationSaved){
            $this->logService->log([
                'message' => 'Location u aktivizua me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $location;
    }

    /**
     * Deletes existing client
     **/
    public function delete(Expense $client) {
         $previousData = $client->attributesToArray();
         $clientDeleted = $client->delete();

         if($clientDeleted){
            $this->logService->log([
                'message' => 'Klienti u fshi me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
                'previous_data'=> json_encode($previousData)
            ]);
        }

        return $client;
    }






       /**
     * Delete a user and log the action.
     *
     * @param User $user The user instance to be deleted.
     * @return bool True if the user was deleted successfully, otherwise false.
     */
    public function destroy(Expense $expense)
    {
        $expenseDeleted = $expense->delete();

        if ($expenseDeleted) {
            $this->logService->store([
                "message" => "Shpenzimi u fshi me sukses",
                "context" => Log::LOG_CONTEXT_USERS,
                "ttl" => Log::LOG_TTL_FOREVER,
                "keep_alive" => Log::LOG_TTL_KEEP_ALIVE,
            ]);
        }

        return $expenseDeleted;
    }

}
