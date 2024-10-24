<?php namespace App\Modules\Expenses\Services;

use App\Modules\Expenses\Models\Expense;
use App\Modules\Expenses\Notifications\ExpenseAddedNotification;
use App\Modules\Expenses\Notifications\ExpenseDeletedNotification;
use App\Modules\Expenses\Notifications\ExpenseUpdatedNotification;
use App\Modules\Menus\Notifications\MenuUpdatedNotification;
use App\Modules\Users\Services\UsersService;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ExpensesServices
{
    private $logService;
    private $usersService;

    public function __construct()
    {
        $this->logService = new LogService();
        $this->usersService = app()->make(UsersService::class);
    }

    /**
     * Gets the list of expenses
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

        $query->orderBy('created_at', 'desc');
        return $query->paginate($perPage);

    }

    /**
     * Get Expense by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Expense::find($id);
    }

    /**
     * Get Expenses by IDs
     * @param int|array $id
     **/
    public function getByIds($ids){
        return Expense::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Expense
     **/
    public function store($data)
    {
        $expense = Expense::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "user_id" => data_get($data, "user_id"),
            "date" => data_get($data, "date"),
            "description" => data_get($data, "description"),
            "amount" => data_get($data, "amount"),
        ]);

        if($expense){
            $this->logService->log([
                'message' => 'Shpenzimi është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
            Notification::send(
                $this->usersService->getUsersForNotifications(),
                new ExpenseAddedNotification(
                    $expense,
                    auth()->user()
                )
            );
        }

        return $expense;
    }

    /**
     * Updates existing expense
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
            Notification::send(
                $this->usersService->getUsersForNotifications(),
                new ExpenseUpdatedNotification(
                    $expense,
                    auth()->user()
                )
            );
        }

        return $expense;
    }

    /**
     * Deletes existing expense
     **/
    public function delete(Expense $expense) {
         $previousData = $expense->attributesToArray();
         $expenseDeleted = $expense->delete();

         if($expenseDeleted){
            $this->logService->log([
                'message' => 'Klienti u fshi me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
                'previous_data'=> json_encode($previousData)
            ]);
             Notification::send(
                 $this->usersService->getUsersForNotifications(),
                 new ExpenseDeletedNotification(
                     $expense,
                     auth()->user()
                 )
             );
        }

        return $expense;
    }

}
