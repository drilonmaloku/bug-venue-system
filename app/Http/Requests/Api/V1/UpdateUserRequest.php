<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class UpdateUserRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'super-admin', 'system-admin']);
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id ?? $this->route('user');
        
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'username' => ['sometimes', 'string', 'max:255', 'unique:users,username,' . $userId],
            'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['sometimes', 'string', 'exists:roles,name'],
            'language' => ['sometimes', 'nullable', 'string', 'in:en,al'],
        ];
    }
}
