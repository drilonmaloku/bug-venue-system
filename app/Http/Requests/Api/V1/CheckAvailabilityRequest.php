<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;

class CheckAvailabilityRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'venue_id' => ['required', 'exists:venues,id'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'number_of_guests' => ['nullable', 'integer', 'min:1'],
            'exclude_reservation_id' => ['nullable', 'exists:reservations,id'],
        ];
    }
}
