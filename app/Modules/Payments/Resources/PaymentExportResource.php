<?php

namespace App\Modules\Payments\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentExportResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "reservation_id"                 => $this->reservation ? $this->reservation->description : '',
            "client_id"                      => $this->client ? $this->client->name : '',
            "date"                           => $this->date,
            "values"                         => $this->values,
            "notes"                          => $this->notes,

            
        ];
    }

}
