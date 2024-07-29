<?php

namespace App\Modules\Reservations\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationExportResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "venue_id"                       => $this->venue ? $this->venue->name : '',
            "client_id"                      => $this->client ? $this->client->name : '',
            "menager_id"                     => $this->user ? $this->user->first_name : '',
            "menu_id"                        => $this->menu ? $this->menu->name : '',
            "menu_price"                     => $this->menu_price,
            "date"                           => $this->date,
            "reservation_type"               => $this->reservation_type,
            "description"                    => $this->description,
            "current_payment"                => $this->current_payment,
            "total_payment"                  => $this->total_payment,
            "staff_expenses"                 => $this->staff_expenses,


            
        ];
    }

}
