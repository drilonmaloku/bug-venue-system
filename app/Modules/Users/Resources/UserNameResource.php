<?php

namespace App\Modules\Users\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class UserNameResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            //'profile_url'       => route('users.show',$this->id), commented till route gets created
        ];

    }
}
