<?php

namespace App\Modules\Venues\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required",
            "description" => "nullable|string",
            "capacity" => "nullable|string",
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
        ];
    }
}
