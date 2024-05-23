<?php

namespace App\Modules\Clients\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientListResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"                        => $this->id,
            "status"                    => (int)$this->status,
            "name"                      => $this->name,
            "email"                     => $this->email,
            "phone_number"              => $this->phone_number,
            "additional_phone_number"   => $this->additional_phone_number,
            "description"               => $this->description,
            "location"                  => $this->location,
            "payment_information"       => $this->payment_information,
            "notes"                     => $this->notes,
            "city"                      => $this->city,
            "zip"                       => $this->zip,
            "country"                   => $this->country,
            "street_address"            => $this->street_address,
            "communication_language"    => $this->communication_language,
            "categories"                => $this->categories  ? json_decode($this->categories) : [],
            "created_at"                => $this->created_at->format('Y-m-d H:i'),
            "updated_at"                => $this->updated_at->format('Y-m-d H:i'),
        ];
    }

}
