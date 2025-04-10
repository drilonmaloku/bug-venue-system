<?php

namespace App\Modules\Reminders\Services;

use App\Modules\Reminders\Models\Reminder;
use App\Modules\Reservations\Models\Reservation;
use App\Models\User;
use App\Modules\Reminders\Notifications\ReminderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class RemindersService
{
    /**
     * Get all reminders for a reservation
     *
     * @param int $reservationId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByReservationId(int $reservationId)
    {
        return Reminder::where('reservation_id', $reservationId)
            ->orderBy('reminder_date', 'asc')
            ->get();
    }

    /**
     * Get a reminder by ID
     *
     * @param int $id
     * @return Reminder|null
     */
    public function getById(int $id)
    {
        return Reminder::find($id);
    }

    /**
     * Create a new reminder
     *
     * @param Request $request
     * @param int $reservationId
     * @return Reminder
     */
    public function store(Request $request, int $reservationId)
    {
        $reminder = new Reminder();
        $reminder->reservation_id = $reservationId;
        $reminder->title = $request->input('title');
        $reminder->message = $request->input('message');
        $reminder->reminder_date = Carbon::parse($request->input('reminder_date'));
        $reminder->type = $request->input('type', 'both');
        $reminder->created_by = Auth::id();
        $reminder->save();

        return $reminder;
    }

    /**
     * Update a reminder
     *
     * @param Request $request
     * @param Reminder $reminder
     * @return Reminder
     */
    public function update(Request $request, Reminder $reminder)
    {
        $reminder->title = $request->input('title', $reminder->title);
        $reminder->message = $request->input('message', $reminder->message);
        $reminder->reminder_date = $request->has('reminder_date') 
            ? Carbon::parse($request->input('reminder_date')) 
            : $reminder->reminder_date;
        $reminder->type = $request->input('type', $reminder->type);
        $reminder->save();

        return $reminder;
    }

    /**
     * Delete a reminder
     *
     * @param Reminder $reminder
     * @return bool
     */
    public function delete(Reminder $reminder)
    {
        return $reminder->delete();
    }

    /**
     * Mark a reminder as sent
     *
     * @param Reminder $reminder
     * @return Reminder
     */
    public function markAsSent(Reminder $reminder)
    {
        $reminder->is_sent = true;
        $reminder->sent_at = now();
        $reminder->save();

        return $reminder;
    }

    /**
     * Process reminders that are due
     *
     * @return void
     */
    public function processDueReminders()
    {
        $dueReminders = Reminder::where('is_sent', false)
            ->where('reminder_date', '<=', now())
            ->get();

        foreach ($dueReminders as $reminder) {
            $this->sendReminder($reminder);
        }
    }

    /**
     * Send a reminder notification
     *
     * @param Reminder $reminder
     * @return void
     */
    protected function sendReminder(Reminder $reminder)
    {
        $reservation = $reminder->reservation;
        $creator = $reminder->creator;

        // Log the reminder being processed
        \Log::info('Processing reminder: ' . $reminder->id . ' of type: ' . $reminder->type);
        \Log::info('Reminder details:', [
            'reminder_id' => $reminder->id,
            'title' => $reminder->title,
            'type' => $reminder->type,
            'creator_id' => $reminder->created_by,
            'creator_email' => $creator ? $creator->email : 'null',
            'reservation_id' => $reminder->reservation_id
        ]);

        if (!$creator) {
            \Log::error('Creator not found for reminder: ' . $reminder->id);
            return;
        }

        if (!$creator->email) {
            \Log::error('Creator has no email for reminder: ' . $reminder->id);
            return;
        }

        try {
            // Send notification based on type (only once)
            \Log::info('Sending notification for reminder: ' . $reminder->id . ' to ' . $creator->email);
            Notification::send($creator, new ReminderNotification($reminder));
            \Log::info('Notification sent successfully for reminder: ' . $reminder->id);
        } catch (\Exception $e) {
            \Log::error('Failed to send notification for reminder: ' . $reminder->id . '. Error: ' . $e->getMessage());
            \Log::error('Exception stack trace: ' . $e->getTraceAsString());
        }

        // Mark as sent
        $this->markAsSent($reminder);
        \Log::info('Reminder marked as sent: ' . $reminder->id);
    }
} 