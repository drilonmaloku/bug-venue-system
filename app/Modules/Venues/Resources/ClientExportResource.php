<?php

namespace App\Modules\Clients\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientExportResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "name"                      => $this->name,
            "status"                    => $this->status == 1 ? 'Active' : 'Inactive',
            "email"                     => $this->email,
            "categories"                => $this->categories !== null ? (
                is_array($this->categories) && count($this->categories) > 1 ?
                    implode(', ', $this->categories) :
                    (is_array($this->categories) ? $this->categories[0] : $this->categories)
            ) : '',
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
        ];
    }

}
