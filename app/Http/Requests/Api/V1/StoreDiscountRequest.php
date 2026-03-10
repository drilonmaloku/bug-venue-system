<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class StoreDiscountRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required_without:percentage', 'numeric', 'min:0.01', 'max:999999.99'],
            'percentage' => ['required_without:amount', 'numeric', 'min:0.01', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'reason' => ['nullable', 'string', 'max:500'],
            'type' => ['nullable', 'in:fixed,percentage'],
        ];
    }
}
