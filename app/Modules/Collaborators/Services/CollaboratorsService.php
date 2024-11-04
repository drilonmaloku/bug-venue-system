<?php

namespace App\Modules\Collaborators\Services;

use App\Modules\Collaborators\Models\Collaborators;
use App\Modules\Clients\Models\Client;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Support\Facades\Storage;

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
    public function getAll(Request $request, $paginated = true)
    {
        $perPage = $request->input('per_page', 10);
        $query = Collaborators::query();

        if ($request->has("search") && !empty($request->input("search"))) {
            $searchTerm = '%' . $request->input("search") . '%';
            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('name', 'LIKE', $searchTerm)
                         ->orWhere('typeof', 'LIKE', $searchTerm);
            });
        }

        $query->orderBy('updated_at', 'desc');

         return $paginated ? $query->paginate($perPage) : $query->get();
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
      

        $collaborator = Collaborators::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "phone_number" => $request->input('phone_number'),
            "typeof" =>$request->input('typeof'),
        ]);

        if ($collaborator) {
            $this->logService->log([
                'message' => 'Bashkpuntori është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
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
                'context' => Log::LOG_CONTEXT_CLIENTS,
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
        $previousData = $collaborator->attributesToArray();
   

        $collaboratorDeleted = $collaborator->delete();

        if ($collaboratorDeleted) {
            $this->logService->log([
                'message' => 'Bashkpuntori është fshirë me sukses',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
            ]);
        }

        return $collaboratorDeleted;
    }
}
