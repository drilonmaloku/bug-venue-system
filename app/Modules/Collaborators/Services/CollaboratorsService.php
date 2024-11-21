<?php

namespace App\Modules\Collaborators\Services;

use App\Modules\Collaborators\Models\Collaborators;
use App\Modules\Clients\Models\Client;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;

class CollaboratorsService
{
    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Gets the list of collaborators with optional pagination.
     */
   public function getAll()
    {
        return Collaborators::all();
    }

    /**
     * Get collaborator by ID.
     */
    public function getByID($id)
    {
        return Collaborators::find($id);
    }

    /**
     * Get basic list of clients.
     */
    public function getBasicList()
    {
        return Client::select('id', 'name')->get();
    }

    /**
     * Get clients by an array of IDs.
     */
    public function getByIds(array $ids)
    {
        return Client::whereIn('id', $ids)->get();
    }

 
    /**
     * Stores new collaborator.
     */
    public function store(Request $request)
    {
      
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email',
        'phone_number' => 'nullable|string|max:20',
        'typeof' => 'nullable|string',
    ]);

    // If validation passes, proceed with creation
    $collaborator = Collaborators::create([
        "location_id" => auth()->user()->getCurrentLocationId(),
        "name" => $validatedData['name'],
        "email" => $validatedData['email'],
        "phone_number" => $validatedData['phone_number'],
        "typeof" => $validatedData['typeof'],
    ]);

    if ($collaborator) {
        $this->logService->log([
            'message' => 'Bashkpuntori është krijuar me sukses',
            'context' => Log::LOG_CONTEXT_COLLABORATORS,
            'ttl' => Log::LOG_TTL_THREE_MONTHS,
        ]);
    }

    return $collaborator;
}

    /**
     * Updates existing collaborator.
     */
    public function update(Request $request, Collaborators $collaborator)
    {
        if ($request->has('name')) {
            $collaborator->name = $request->input('name');
        }
        if ($request->has('email')) {
            $collaborator->description = $request->input('email');
        }
         if ($request->has('phone_number')) {
            $collaborator->description = $request->input('phone_number');
        }
         if ($request->has('typeof')) {
            $collaborator->description = $request->input('typeof');
        }
     

        $collaboratorUpdated = $collaborator->save();

        if ($collaboratorUpdated) {
            $this->logService->log([
                'message' => 'Bashkpuntori është përditësuar me sukses',
                'context' => Log::LOG_CONTEXT_COLLABORATORS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $collaboratorUpdated;
    }

    /**
     * Deletes existing collaborator.
     */
    public function delete(Collaborators $collaborator)
    {
        
        if(count($collaborator->reservations) == 0){
            return $this->forceDelete($collaborator);
        }

        return $this->archive($collaborator);
    }

    /**
     * Deletes existing collaborator.
     */
    public function archive(Collaborators $collaborator)
    {
        $previousData = $collaborator->attributesToArray();


        $collaboratorDeleted = $collaborator->delete();

        if ($collaboratorDeleted) {
            $this->logService->log([
                'message' => 'Bashkpuntori është arkivuar me sukses',
                'context' => Log::LOG_CONTEXT_COLLABORATORS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
            ]);
        }

        return $collaboratorDeleted;
    }

    /**
     * Deletes existing collaborator.
     */
    public function forceDelete(Collaborators $collaborator)
    {
        $previousData = $collaborator->attributesToArray();


        $collaboratorDeleted = $collaborator->delete();

        if ($collaboratorDeleted) {
            $this->logService->log([
                'message' => 'Bashkpuntori është fshirë me sukses',
                'context' => Log::LOG_CONTEXT_COLLABORATORS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
            ]);
        }

        return $collaboratorDeleted;
    }
}
