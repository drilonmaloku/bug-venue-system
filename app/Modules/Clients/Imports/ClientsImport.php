<?php

namespace App\Modules\Clients\Imports;

use App\Modules\Clients\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
     $client = Client::create ([
            'location_id' => auth()->user()->getCurrentLocationId(),
            'name' => $row['name'],
            'email' => $row['email'],
            'phone_number' => $row['phone'],
            'additional_phone_number' => $row['additional_phone'],
        ]);
        return $client;
    }
}