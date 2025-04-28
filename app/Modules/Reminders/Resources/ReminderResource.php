<?php

namespace App\Modules\Reminders\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reservation_id' => $this->reservation_id,
            'title' => $this->title,
            'message' => $this->message,
            'reminder_date' => $this->reminder_date,
            'type' => $this->type,
            'is_sent' => $this->is_sent,
            'sent_at' => $this->sent_at,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creator' => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
                'email' => $this->creator->email,
            ],
            'reservation' => [
                'id' => $this->reservation->id,
                'title' => $this->reservation->title,
                'date' => $this->reservation->date,
            ],
        ];
    }
}