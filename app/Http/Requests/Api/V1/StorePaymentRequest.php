<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class StorePaymentRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Modules\Payments\Models\Payment::class);
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'amount' => ['nullable', 'numeric', 'min:0.01', 'max:999999.99'],
            'payment_method' => ['required', 'in:1,2'],
            'method' => ['nullable', 'in:1,2,cash,bank'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'date' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
        ];
    }
}
