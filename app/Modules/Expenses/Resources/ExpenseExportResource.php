<?php

namespace App\Modules\Expenses\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseExportResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "user"                      => $this->user ? $this->user->name : '',
            "date"                      => $this->date,
            "description"               => $this->description,
            "amount"                    => $this->amount,
            
        ];
    }

}
