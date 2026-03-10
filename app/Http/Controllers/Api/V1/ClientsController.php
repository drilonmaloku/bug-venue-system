<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\{
    StoreClientRequest,
    UpdateClientRequest
};
use App\Http\Resources\Api\V1\{
    ClientResource,
    ReservationResource,
    PaymentResource
};
use App\Modules\Clients\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsController extends BaseApiController
{
    /**
     * List all clients with filtering
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Client::withCount('reservations')
            ->orderBy('first_name');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('last_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone_number', 'like', '%' . $filters['search'] . '%');
            });
        }

        $clients = $query->paginate($filters['per_page'] ?? 15);

        return $this->paginatedResponse(
            ClientResource::collection($clients)
        );
    }

    /**
     * Create a new client
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());

        return $this->createdResponse(
            new ClientResource($client),
            'Client created successfully'
        );
    }

    /**
     * Get a single client
     */
    public function show(Client $client): JsonResponse
    {
        return $this->successResponse(
            new ClientResource($client->loadCount(['reservations', 'payments']))
        );
    }

    /**
     * Update a client
     */
    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());

        return $this->successResponse(
            new ClientResource($client->fresh()),
            'Client updated successfully'
        );
    }

    /**
     * Delete a client
     */
    public function destroy(Client $client): JsonResponse
    {
        // Check if client has reservations
        if ($client->reservations()->count() > 0) {
            return $this->errorResponse(
                'Cannot delete client with existing reservations',
                422,
                'CLIENT_HAS_RESERVATIONS'
            );
        }

        $client->delete();

        return $this->noContentResponse();
    }

    /**
     * Search clients (autocomplete)
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $clients = Client::where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->q . '%')
                  ->orWhere('last_name', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->q . '%');
            })
            ->limit($request->limit ?? 10)
            ->get();

        return $this->successResponse(
            ClientResource::collection($clients)
        );
    }

    /**
     * Get client reservations
     */
    public function reservations(Client $client): JsonResponse
    {
        $reservations = $client->reservations()
            ->with(['venue', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            ReservationResource::collection($reservations)
        );
    }

    /**
     * Get client payments
     */
    public function payments(Client $client): JsonResponse
    {
        $payments = $client->payments()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            PaymentResource::collection($payments)
        );
    }
}
