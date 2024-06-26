<?php namespace App\Modules\SupportTickets\Services;

use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;

use App\Modules\SupportTickets\Models\Support;

class SupportTicketsService
{

    private $logService;

    public function __construct()
    {
        $this->logService = new LogService();
    }

    /**
     * Gets the list of venues
     **/
    public function getAll()
    {
       return Support::all();
    }

    /**
     * Get Venue by ID
     * @param int|array $id
     **/
    public function getByID($id)
    {
        return Support::find($id);
    }

    /**
     * Get Payments by ID
     * @param int|array $id
     **/
    public function getByIds($ids)
    {
        return Support::whereIn('id', $ids)->get();
    }





   public function store($data)
   {
       $ticket = Support::create([
           "user_id" => data_get($data, "user_id"),
           "title" => data_get($data, "title"),
           "description" => data_get($data, "description"),
           "attachment" => data_get($data, "attachment"),

       ]);

       if($ticket){
           $this->logService->log([
               'message' => 'Tiketa është krijuar me sukses',
               'context' => Log::LOG_CONTEXT_CLIENTS,
               'ttl'=> Log::LOG_TTL_THREE_MONTHS,
           ]);
       }

       return $ticket;
   }
}
