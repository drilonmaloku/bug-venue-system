<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;
use Illuminate\Validation\Rule;

class UpdateReservationRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('reservation'));
    }

    public function rules(): array
    {
        return [
            // Event Details
            'event_name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:1000'],
            'reservation_type' => ['sometimes', 'integer', 'in:1,2,3'],
            
            // Dates
            'date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'end_date' => ['sometimes', 'nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:date'],
            'start_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'end_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            
            // Venue
            'venue_id' => ['sometimes', 'exists:venues,id'],
            
            // Client
            'client_id' => ['sometimes', 'exists:clients,id'],
            
            // Guest Count
            'number_of_guests' => ['sometimes', 'integer', 'min:1', 'max:10000'],
            
            // Assignment
            'manager_id' => ['sometimes', 'nullable', 'exists:users,id'],
            
            // Menu & Decor
            'menu_id' => ['sometimes', 'nullable', 'exists:menus,id'],
            'menu_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'decor_id' => ['sometimes', 'nullable', 'exists:decors,id'],
            
            // Notes
            'notes' => ['sometimes', 'nullable', 'string', 'max:5000'],
            
            // Status
            'status' => ['sometimes', 'in:1,2,3'],
        ];
    }
}
