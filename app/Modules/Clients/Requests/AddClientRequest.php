<?php

declare(strict_types=1);

namespace App\Modules\Clients\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required",
            "email" => "email",
            "phone_number" => "nullable|string",
            "additional_phone_number" => "nullable|string",
            "notes" => "nullable|string",
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.email' => 'The email must be a valid email address.',
        ];
    }
}
