<?php

namespace App\Modules\Reminders\Observers;

use App\Modules\Reminders\Models\Reminder;
use Illuminate\Support\Facades\Log;

class ReminderObserver
{
    /**
     * Handle the Reminder "created" event.
     */
    public function created(Reminder $reminder): void
    {
        Log::info('Reminder created', [
            'reminder_id' => $reminder->id,
            'reservation_id' => $reminder->reservation_id,
            'created_by' => $reminder->created_by,
        ]);
    }

    /**
     * Handle the Reminder "updated" event.
     */
    public function updated(Reminder $reminder): void
    {
        Log::info('Reminder updated', [
            'reminder_id' => $reminder->id,
            'reservation_id' => $reminder->reservation_id,
            'updated_by' => $reminder->created_by,
        ]);
    }

    /**
     * Handle the Reminder "deleted" event.
     */
    public function deleted(Reminder $reminder): void
    {
        Log::info('Reminder deleted', [
            'reminder_id' => $reminder->id,
            'reservation_id' => $reminder->reservation_id,
            'deleted_by' => $reminder->created_by,
        ]);
    }
} 