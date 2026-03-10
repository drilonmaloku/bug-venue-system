<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class UpdateGuestStatusRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,confirmed,declined,attended,no_show'],
        ];
    }
}
