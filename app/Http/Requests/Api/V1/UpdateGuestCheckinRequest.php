<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class UpdateGuestCheckinRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'checked_in' => ['required', 'boolean'],
            'checked_in_at' => ['nullable', 'date'],
        ];
    }
}
