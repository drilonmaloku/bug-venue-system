<?php

namespace App\Modules\Reminders\Notifications;

use App\Modules\Reminders\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Reminder $reminder
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Determine channels based on reminder type
        $channels = ['database'];
        
        // Add mail channel if reminder type is email or both
        if ($this->reminder->type === 'email' || $this->reminder->type === 'both') {
            $channels[] = 'mail';
        }
        
        \Log::info('Notification channels for reminder ' . $this->reminder->id . ': ' . implode(', ', $channels));
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: ' . $this->reminder->title)
            ->line($this->reminder->message)
            ->line('Reminder Date: ' . $this->reminder->reminder_date->format('Y-m-d H:i'))
            ->line('Reservation: ' . $this->reminder->reservation->title)
            ->action('View Reservation', route('reservations.view', ['id' => $this->reminder->reservation_id]))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reminder_id' => $this->reminder->id,
            'title' => $this->reminder->title,
            'message' => $this->reminder->message,
            'reminder_date' => $this->reminder->reminder_date,
            'reservation_id' => $this->reminder->reservation_id,
            'reservation_title' => $this->reminder->reservation->title,
        ];
    }
} 