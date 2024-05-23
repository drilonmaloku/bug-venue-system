<?php

namespace App\Modules\Logs\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class LogListResource extends JsonResource
{

    protected $contextMapping = [
        1 => "Staff",
        2 => "User",
        3 => "Group",
        4 => "Clients",
        5 => "Expenses",
    ];

    public function toArray($request)
    {

        return [
            "id"           => $this->id,
            "user_name"    => $this->user ? $this->user->name : '',
            "message"      => $this->message,
            "context"      => $this->getContextText($this->context),
            "ttl"          => $this->ttl,
            "keep_alive"   => $this->keep_alive,
            "deletes_at"   => $this->deletes_at,
            "created_at"   => $this->created_at->format("Y-m-d H:i:s"),
            "previous_data" =>$this->previous_data,
            "updated_data"  =>$this->updated_data,

        ];
    }

    protected function getContextText($contextValue)
    {
        return $this->contextMapping[$contextValue] ?? "Unknown";
    }
}
