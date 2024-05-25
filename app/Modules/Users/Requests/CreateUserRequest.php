<?php


namespace App\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "first_name" => "required|string",
            "last_name" => "nullable",
            "email" => "nullable|string|unique:users",
            "phone" => "nullable|string",
            "role" => "string",
        ];
        return $rules;
    }
}