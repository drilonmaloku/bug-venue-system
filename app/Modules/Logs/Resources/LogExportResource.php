<?php

namespace App\Modules\Logs\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class LogExportResource extends JsonResource
{

    protected $contextMapping = [
        1 => "Staff",
        2 => "User",
        3 => "Group",
        4 => "Clients",
    ];

    public function toArray($request)
    {
        return [
            "id"           => $this->id,
            "user"         => $this->user->name,
            "created_at"   => $this->created_at->format("Y-m-d H:i:s"),
            "message"      => $this->message,
            "context"      => $this->contextMapping[$this->context] ?? "Unknown",
            "deletes_at"   => $this->deletes_at
        ];
    }

}
