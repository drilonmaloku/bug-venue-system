<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class StoreInvoiceRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'in:pending,paid,cancelled'],
            'due_at' => ['nullable', 'date'],
        ];
    }
}
