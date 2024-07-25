<?php

namespace App\Modules\Logs\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class LogExportResource extends JsonResource
{

    protected $contextMapping = [
        1 => "Rezervimet",
        2 => "Pagesat",
        3 => "Klientat",
        4 => "Sallat",
        5 => "Userat",
        6 => "Raportet",
        7 => "Menu",
        99 => "Common",
    ];

    public function toArray($request)
    {
        return [
            "id"           => $this->id,
            "user"         => $this->user->first_name.' '.$this->user->last_name,
            "created_at"   => $this->created_at->format("Y-m-d H:i:s"),
            "message"      => $this->message,
            "context"      => $this->contextMapping[$this->context] ?? "Unknown",
            "deletes_at"   => $this->deletes_at
        ];
    }

}
