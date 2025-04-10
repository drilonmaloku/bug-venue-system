<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Reminders\Models\Reminder;
use App\Modules\Reservations\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;

class CreateRealReminderCommand extends Command
{
    protected $signature = 'reminder:create-real';
    protected $description = 'Create a real reminder for testing';

    public function handle()
    {
        $user = User::first();
        if (!$user) {
            $this->error('No user found in the database');
            return;
        }

        $reservation = Reservation::first();
        if (!$reservation) {
            $this->error('No reservation found in the database');
            return;
        }

        $reminder = new Reminder();
        $reminder->reservation_id = $reservation->id;
        $reminder->title = 'Real Test Reminder';
        $reminder->message = 'This is a real test reminder with both notifications';
        $reminder->type = 'both'; // Set to both to ensure email is sent
        $reminder->reminder_date = Carbon::now()->addMinutes(1);
        $reminder->created_by = $user->id;
        $reminder->save();

        $this->info('Real reminder created with ID: ' . $reminder->id);
        $this->info('Creator email: ' . $user->email);
        $this->info('Reminder will be processed in 1 minute');
    }
} 