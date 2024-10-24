<?php

namespace App\Modules\Notifications\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         return [
            'data' => $this->data,
            'id' => $this->id,
            'created_at' => $this->created_at->format('d/m/Y'),
            'read' => $this->read(),
            'read_at' => $this->read() ? $this->read_at : null,
            'read_at_ts' => $this->read() ? $this->read_at->getTimestamp() : null,
            'type' => $this->type,
        ];

    }
}
