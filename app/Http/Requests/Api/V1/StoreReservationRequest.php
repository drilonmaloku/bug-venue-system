<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseApiRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Modules\Reservations\Models\Reservation::class);
    }

    public function rules(): array
    {
        return [
            // Event Details
            'event_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'reservation_type' => ['required', 'integer', 'in:1,2,3'],
            
            // Dates
            'date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            
            // Venue
            'venue_id' => ['required', 'exists:venues,id'],
            
            // Client
            'client_id' => ['required_without:client', 'exists:clients,id'],
            'client' => ['required_without:client_id', 'array'],
            'client.first_name' => ['required_with:client', 'string', 'max:255'],
            'client.last_name' => ['required_with:client', 'string', 'max:255'],
            'client.email' => ['required_with:client', 'email', 'max:255'],
            'client.phone' => ['nullable', 'string', 'max:50'],
            
            // Guest Count
            'number_of_guests' => ['required', 'integer', 'min:1', 'max:10000'],
            
            // Assignment
            'manager_id' => ['nullable', 'exists:users,id'],
            
            // Menu & Decor
            'menu_id' => ['nullable', 'exists:menus,id'],
            'menu_price' => ['nullable', 'numeric', 'min:0'],
            'decor_id' => ['nullable', 'exists:decors,id'],
            
            // Notes
            'notes' => ['nullable', 'string', 'max:5000'],
            
            // Status
            'status' => ['nullable', 'in:1,2,3'],
        ];
    }

    public function attributes(): array
    {
        return [
            'event_name' => 'event name',
            'reservation_type' => 'reservation type',
            'date' => 'date',
            'venue_id' => 'venue',
            'client_id' => 'client',
            'number_of_guests' => 'number of guests',
            'manager_id' => 'assigned manager',
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('number_of_guests') && !$this->number_of_guests) {
            $this->merge(['number_of_guests' => 1]);
        }
    }
}
