<?php

namespace App\Modules\Venues\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class VenueExportResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "name"                           => $this->name,
            "description"                    => $this->description,
            "capacity"                       => $this->capacity,
     


        ];
    }

}
