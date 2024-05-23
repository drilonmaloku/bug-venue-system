<?php

declare(strict_types=1);

namespace App\Modules\Clients\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "id" => "required",
            "name" => "required",
            "email" => "email",
            "phone_number" => "nullable|string",
            "additional_phone_number" => "nullable|string",
            "notes" => "nullable|string",
            "description" => "nullable|string",
            "location" => "nullable|string",
            "payment_information" => "nullable|string",
            "status" => "nullable|integer",
            "city" => "nullable|string",
            "zip" => "nullable|string",
            "country" => "nullable|string",
            "street_address" => "nullable|string",
            "communication_language" => "nullable|string",
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Client id is required',
            'name.required' => 'The name field is required.',
            'email.email' => 'The email must be a valid email address.',
        ];
    }
}
