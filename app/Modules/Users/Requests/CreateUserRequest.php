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
            "email" => "required|string|unique:users",
            "phone" => "required",
        ];

        // Check if generate_password is set to true, skip password validation
        if ($this->input('generate_password') === true) {
            return $rules;
        }

        // If generate_password is not set or set to false, validate password and confirm_password
//        $rules["password"] = "required|string|min:8|confirmed";
//        $rules["password_confirmation"] = "required|string|min:8";

        return $rules;
    }
}