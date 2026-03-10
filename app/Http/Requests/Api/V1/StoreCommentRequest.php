<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class StoreCommentRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'max:5000'],
            'content' => ['nullable', 'string', 'max:5000'], // Alias for comment
            'type' => ['nullable', 'string', 'in:general,internal,client'],
        ];
    }
}
