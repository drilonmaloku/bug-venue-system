<?php namespace App\Modules\Expenses\Services;

use App\Modules\Expenses\Models\Expense;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Support\Facades\DB;

class ExpensesServices
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
        $query = Expense::query();


        if ($request && $request->has("search") && $request->input("search") != '') {
            $searchTerm = '%' . $request->input("search") . '%';
        
            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('description', 'LIKE', $searchTerm)
                    ->orWhere('amount', 'LIKE', $searchTerm)
                    ->orWhereHas('user', function ($q) use ($searchTerm) {
                        $q->where(function ($innerQuery) use ($searchTerm) {
                            $innerQuery->where('first_name', 'LIKE', $searchTerm)
                                       ->orWhere('last_name', 'LIKE', $searchTerm)
                                       ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $searchTerm);
                        });
                    });
            });
        }
        

       // Handle date filter
       if ($request->has('date') && $request->input('date') != '') {
        $date = $request->input('date');
        $query->where('date', $date); 
    }

        $query->orderBy('created_at', 'desc');
        return $query->paginate($perPage);

    }

    /**
     * Get Client by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Expense::find($id);
    }

    /**
     * Get Client
     **/
    public function getBasicList(){
        return Expense::select('id', 'name')->get();
    }

    /**
     * Get Clients by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return Expense::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Client
     **/
    public function store($data)
    {
        $client = Expense::create([
            "user_id" => data_get($data, "user_id"),

            "date" => data_get($data, "date"),
            "description" => data_get($data, "description"),
            "amount" => data_get($data, "amount"),
        ]);

        if($client){
            $this->logService->log([
                'message' => 'Shpenzimi është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $client;
    }

    /**
     * Updates existing client
     **/
    public function update($request, Expense $expense) {
        $expense->date = $request->input('date');
        $expense->description = $request->input('description');
        $expense->amount = $request->input('amount');
        $expenseSaved = $expense->save();

        if($expenseSaved){
            $this->logService->log([
                'message' => 'Shpenzimi u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $expense;
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
