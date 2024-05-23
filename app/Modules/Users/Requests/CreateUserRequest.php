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
            "phone" => "nullable|string|unique:users",
            
        ];

        // Check if generate_password is set to true, skip password validation
        if ($this->input('generate_password') === true) {
            return $rules;
        }

        return $rules;
    }
}