<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class UpdateClientRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('client'));
    }

    public function rules(): array
    {
        return [
            // Individual
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            
            // Company
            'company_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            
            // Contact
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'phone_secondary' => ['sometimes', 'nullable', 'string', 'max:50'],
            
            // Address
            'address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'city' => ['sometimes', 'nullable', 'string', 'max:100'],
        ];
    }
}
