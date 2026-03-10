<?php

namespace App\Services\Api\V1;

use App\Modules\Clients\Models\Client;

class ClientService
{
    /**
     * Create a new client
     */
    public function create(array $data): Client
    {
        // Set location if not provided
        if (empty($data['location_id']) && auth()->check()) {
            $data['location_id'] = auth()->user()->getCurrentLocationId();
        }

        return Client::create($data);
    }

    /**
     * Update client
     */
    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client->fresh();
    }

    /**
     * Delete client
     */
    public function delete(Client $client): bool
    {
        return $client->delete();
    }

    /**
     * Search clients
     */
    public function search(string $query, int $limit = 10)
    {
        return Client::where(function ($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                  ->orWhere('last_name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%')
                  ->orWhere('phone_number', 'like', '%' . $query . '%');
            })
            ->limit($limit)
            ->get();
    }
}
