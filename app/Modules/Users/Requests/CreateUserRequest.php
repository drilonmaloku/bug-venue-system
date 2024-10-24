<?php


namespace App\Modules\Users\Requests;

use App\Rules\UniqueUsernameWithinLocation;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'nullable|string|unique:users',
            'phone' => 'nullable|string',
            'role' => 'string',
            'language' => 'nullable|string',
            'username' => [
                'required',
                'string',
                new UniqueUsernameWithinLocation()
            ]
        ];
    }

    public function messages()
    {
        return [
            'username.unique' => 'This username is already taken for the current location.'
        ];
    }
}