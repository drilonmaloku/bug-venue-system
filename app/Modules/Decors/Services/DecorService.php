<?php

namespace App\Modules\Decors\Services;

use App\Modules\Decors\Models\Decor;
use App\Modules\Clients\Models\Client;
use App\Modules\Files\Services\AppFileService;
use App\Modules\SupportTickets\Services\FileService;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Support\Facades\Storage;

class DecorService
{
    private $logService;
    private $fileService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
        $this->fileService = app()->make(AppFileService::class);
    }

    /**
     * Gets the list of decors with optional pagination.
     */
    public function getAll(Request $request, $paginated = true)
    {
        $perPage = $request->input('per_page', 10);
        $query = Decor::query();

        if ($request->has("search") && !empty($request->input("search"))) {
            $searchTerm = '%' . $request->input("search") . '%';
            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('name', 'LIKE', $searchTerm)
                         ->orWhere('description', 'LIKE', $searchTerm);
            });
        }

        $query->orderBy('updated_at', 'desc');

         return $paginated ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Get Decor by ID.
     */
    public function getByID($id)
    {
        return Decor::find($id);
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
     * Stores new decor.
     */
    public function store(Request $request)
    {
        $imageId = null;
        if ($request->hasFile('image')) {
            $imageId = $this->fileService->store($request->file('image'))->id;
        }

        $decor = Decor::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "name" => $request->input('name'),
            "image_id" => $imageId,
            "description" => $request->input('description'),
        ]);

        if ($decor) {
            $this->logService->log([
                'message' => 'Dekori është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_DECOR,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $decor;
    }

    /**
     * Updates existing decor.
     */
    public function update(Request $request, Decor $decor)
    {
        if ($request->has('name')) {
            $decor->name = $request->input('name');
        }
        if ($request->has('description')) {
            $decor->description = $request->input('description');
        }
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($decor->image) {
                $this->fileService->delete($decor->image->id);
            }
            $decor->image_id = $this->fileService->store($request->file('image'))->id;
        }

        $decorUpdated = $decor->save();

        if ($decorUpdated) {
            $this->logService->log([
                'message' => 'Dekori është përditësuar me sukses',
                'context' => Log::LOG_CONTEXT_DECOR,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $decorUpdated;
    }

    /**
     * Deletes existing decor.
     */
    public function delete(Decor $decor)
    {
        if(count($decor->reservations) == 0) {
            return $this->forceDelete($decor);
        }
        return $this->archive($decor);
    }

    /**
     * Deletes existing decor.
     */
    public function archive(Decor $decor)
    {
        $previousData = $decor->attributesToArray();


        $decorDeleted = $decor->delete();

        if ($decorDeleted) {
            $this->logService->log([
                'message' => 'Dekori është arkivuar me sukses',
                'context' => Log::LOG_CONTEXT_DECOR,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
            ]);
        }

        return $decorDeleted;
    }

    /**
     * Deletes existing decor.
     */
    public function forceDelete(Decor $decor)
    {
        $previousData = $decor->attributesToArray();


        $decorDeleted = $decor->forceDelete();

        if ($decorDeleted) {
            $this->logService->log([
                'message' => 'Dekori është fshirë me sukses',
                'context' => Log::LOG_CONTEXT_DECOR,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
            ]);
        }

        return $decorDeleted;
    }
}
