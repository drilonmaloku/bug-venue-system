<?php

namespace App\Modules\Logs\Resources;

use App\Models\User;
use App\Modules\Users\Resources\UserNameResource;
use App\Modules\Users\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    protected $contextMapping = [
        1 => "Staff",
        2 => "User",
        3 => "Group",
        4 => "Clients",
        6 => "Expenses",

    ];

    public function toArray($request): array
    {
        return [
            "id"                => $this->id,
            "user"              => UserNameResource::make($this->user),
            "message"           => $this->message,
            "context"           => $this->getContextText($this->context),
            "previous_data"     => json_decode($this->previous_data),
            "updated_data"      => json_decode($this->updated_data),
            "ttl"               => $this->ttl,
            "keep_alive"        => $this->keep_alive,
            "deletes_at"        => $this->deletes_at
        ];
    }

    protected function getContextText($contextValue)
    {
        return $this->contextMapping[$contextValue] ?? 'Unknown';
    }
}
