<?php

namespace App\Modules\SeatingPlans\Services;

use App\Modules\SeatingPlans\Models\SeatingPlan;
use App\Modules\Clients\Models\Client;
use App\Modules\Files\Services\AppFileService;
use App\Modules\SupportTickets\Services\FileService;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Support\Facades\Storage;

class SeatingPlanService
{
    private $logService;
    private $fileService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
        $this->fileService = app()->make(AppFileService::class);
    }

    /**
     * Gets the list of seating plans with optional pagination.
     */
    public function getAll(Request $request, $paginated = true)
    {
        $perPage = $request->input('per_page', 10);
        $query = SeatingPlan::query();

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
     * Get SeatingPlan by ID.
     */
    public function getByID($id)
    {
        return SeatingPlan::find($id);
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
     * Stores new seating plan.
     */
    public function store(Request $request)
    {
        $imageId = null;
        if ($request->hasFile('image')) {
            $imageId = $this->fileService->store($request->file('image'))->id;
        }

        $seatingPlan = SeatingPlan::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "name" => $request->input('name'),
            "image_id" => $imageId,
            "description" => $request->input('description'),
        ]);

        if ($seatingPlan) {
            $this->logService->log([
                'message' => 'Plani i ulseve është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_SEATING_PLAN,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $seatingPlan;
    }

    /**
     * Updates existing seating plan.
     */
    public function update(Request $request, SeatingPlan $seatingPlan)
    {
        if ($request->has('name')) {
            $seatingPlan->name = $request->input('name');
        }
        if ($request->has('description')) {
            $seatingPlan->description = $request->input('description');
        }
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($seatingPlan->image) {
                $this->fileService->delete($seatingPlan->image->id);
            }
            $seatingPlan->image_id = $this->fileService->store($request->file('image'))->id;
        }

        $seatingPlanUpdated = $seatingPlan->save();

        if ($seatingPlanUpdated) {
            $this->logService->log([
                'message' => 'Plani i ulseve është përditësuar me sukses',
                'context' => Log::LOG_CONTEXT_SEATING_PLAN,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $seatingPlanUpdated;
    }

    /**
     * Deletes existing seating plan.
     */
    public function delete(SeatingPlan $seatingPlan)
    {
        if(count($seatingPlan->reservations) == 0) {
            return $this->forceDelete($seatingPlan);
        }
        return $this->archive($seatingPlan);
    }

    /**
     * Archives existing seating plan.
     */
    public function archive(SeatingPlan $seatingPlan)
    {
        $previousData = $seatingPlan->attributesToArray();

        $seatingPlanDeleted = $seatingPlan->delete();

        if ($seatingPlanDeleted) {
            $this->logService->log([
                'message' => 'Plani i ulseve është arkivuar me sukses',
                'context' => Log::LOG_CONTEXT_SEATING_PLAN,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
            ]);
        }

        return $seatingPlanDeleted;
    }

    /**
     * Force deletes existing seating plan.
     */
    public function forceDelete(SeatingPlan $seatingPlan)
    {
        $previousData = $seatingPlan->attributesToArray();

        $seatingPlanDeleted = $seatingPlan->forceDelete();

        if ($seatingPlanDeleted) {
            $this->logService->log([
                'message' => 'Plani i ulseve është fshirë me sukses',
                'context' => Log::LOG_CONTEXT_SEATING_PLAN,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
            ]);
        }

        return $seatingPlanDeleted;
    }
} 