<?php

namespace App\Modules\Reservations\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationGuestListExportResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "name"          => $this->name,
            "table_number"   => $this->table_number,
            "guest_count"   => $this->guest_count,
            "phone_number"  => $this->phone_number,
            "email"         => $this->email,
            "status"        => $this->status ? "Konfirmuar" : "Ne Pritje",
        ];
    }

}
