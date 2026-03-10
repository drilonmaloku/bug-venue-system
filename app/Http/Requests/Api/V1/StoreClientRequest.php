<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class StoreClientRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Modules\Clients\Models\Client::class);
    }

    public function rules(): array
    {
        return [
            // Individual
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            
            // Company
            'company_name' => ['nullable', 'string', 'max:255'],
            
            // Contact
            'email' => ['nullable', 'email', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'phone_secondary' => ['nullable', 'string', 'max:50'],
            
            // Address
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
        ];
    }
}
