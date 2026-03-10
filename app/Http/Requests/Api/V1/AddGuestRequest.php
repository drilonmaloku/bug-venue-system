<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class AddGuestRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'dietary_requirements' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'in:pending,confirmed,declined,attended,no_show'],
        ];
    }
}
