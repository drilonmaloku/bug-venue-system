<?php

declare(strict_types=1);

namespace App\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "first_name" => "nullable|string|max:255",
            "last_name" => "nullable|string|max:255",
            "email" => "nullable|email|unique:users,email",
            "phone" => "nullable|string|max:255",
            "attributes" => "nullable|json"
        ];
    }
}
