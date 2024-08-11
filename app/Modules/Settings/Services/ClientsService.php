<?php namespace App\Modules\Clients\Services;

use App\Modules\Clients\Models\Client;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;

class ClientsService
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
        $perPage = $request->has('per_page') ? $request->input('per_page') : 100;
        $query = Client::query();

        if ($request->has("search")) {
            $searchTerm = '%' . $request->input("search") . '%';

            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('phone_number', 'LIKE', $searchTerm)
                    ->orWhere('additional_phone_number', 'LIKE', $searchTerm);
            });
        }
        $query->orderBy('updated_at', 'desc');
        return $query->paginate($perPage);

    }

    /**
     * Get Client by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Client::find($id);
    }

    
    /**
     * Get Client
     **/
    public function getBasicList(){
        return Client::select('id', 'name')->get();
    }

    /**
     * Get Clients by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return Client::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Client
     **/
    public function store($data)
    {
        $client = Client::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "name" => data_get($data, "name"),
            "email" => data_get($data, "email"),
            "phone_number" => data_get($data, "phone_number"),
            "additional_phone_number" => data_get($data, "additional_phone_number"),
        ]);

        if($client){
            $this->logService->log([
                'message' => 'Klienti është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $client;
    }

    /**
     * Updates existing client
     **/
    public function update($request, Client $client) {
        $client->name = $request->input('name');
        $client->email = $request->input('email');
        $client->phone_number = $request->input('phone_number');
        $client->additional_phone_number = $request->input('additional_phone_number');
        $clientSaved = $client->save();

        if($clientSaved){
            $this->logService->log([
                'message' => 'Klienti u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $client;
    }

    /**
     * Deletes existing client
     **/
    public function delete(Client $client) {
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

}
