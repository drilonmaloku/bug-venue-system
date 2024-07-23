<?php

namespace App\Modules\Menus\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuExportResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "name"                      => $this->name,
            "price"                     => $this->price,
            "description"               => $this->description,
        ];
    }

}
