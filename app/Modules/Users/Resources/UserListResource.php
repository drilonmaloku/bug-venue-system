<?php

namespace App\Modules\Users\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
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
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'role'              => $this->getRoleName(),
            'is_enabled'        => $this->is_enabled,
            'created_at'        => $this->created_at->format("Y-m-d H:i:s"),
            'updated_at'        => $this->updated_at->format("Y-m-d H:i:s"),
            //'profile_url'       => route('users.show',$this->id), commented till route gets created
        ];

    }
}
