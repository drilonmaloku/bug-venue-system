<?php

namespace App\Modules\Clients\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientExportResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "name"                      => $this->name,
            "email"                     => $this->email,
            "phone_number"              => $this->phone_number,
            "additional_phone_number"   => $this->additional_phone_number,
        ];
    }

}
