<?php

declare(strict_types=1);

namespace App\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email" => "required|email|unique:users,email|max:255",
            "name" => "required|string|max:255",
            "password" => "string|min:8|max:255",
            "phone" => "required|string|unique:users,phone|max:255",
        ];
    }
}
