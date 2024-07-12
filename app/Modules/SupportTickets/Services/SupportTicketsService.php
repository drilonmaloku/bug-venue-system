<?php

namespace App\Modules\SupportTickets\Services;

use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;

use App\Modules\SupportTickets\Models\SupportTicket;

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
        return SupportTicket::all();
    }

    /**
     * Get Venue by ID
     * @param int|array $id
     **/
    public function getByID($id)
    {
        return SupportTicket::find($id);
    }

    /**
     * Get Payments by ID
     * @param int|array $id
     **/
    public function getByIds($ids)
    {
        return SupportTicket::whereIn('id', $ids)->get();
    }





    public function store($data)
    {
        $ticket = SupportTicket::create([
            "location_id" => auth()->user()->getCurrentLocationID(),
            "user_id" => data_get($data, "user_id"),
            "title" => data_get($data, "title"),
            "description" => data_get($data, "description"),
            "attachment" => data_get($data, "attachment"),

        ]);

        if ($ticket) {
            $this->logService->log([
                'message' => 'Tiketa është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $ticket;
    }


    
    public function update($request, SupportTicket $ticket)
    {
        $ticket->status = 3;  // Assuming 3 is the status code for "closed"
        $ticketSaved = $ticket->save();

        if ($ticketSaved) {
            $this->logService->log([
                'message' => 'Statusi u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_RESERVATIONS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $ticketSaved;
    }



    public function updateStatusOpen($request, SupportTicket $ticket)
    {
        $ticket->status = 2;  // Assuming 3 is the status code for "closed"
        $ticketSaved = $ticket->save();

        if ($ticketSaved) {
            $this->logService->log([
                'message' => 'Statusi u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_RESERVATIONS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $ticketSaved;
    }
}
