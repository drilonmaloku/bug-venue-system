<?php

namespace App\Notifications;

use App\Modules\Payments\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The installment instance.
     */
    protected Installment $installment;

    /**
     * Days until payment is due.
     */
    protected int $daysUntilDue;

    /**
     * Create a new notification instance.
     */
    public function __construct(Installment $installment, int $daysUntilDue)
    {
        $this->installment = $installment;
        $this->daysUntilDue = $daysUntilDue;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $schedule = $this->installment->paymentSchedule;
        $client = $schedule->client;
        $reservation = $schedule->reservation;

        return (new MailMessage)
            ->subject("Reminder: Payment Due in {$this->daysUntilDue} Days - {$this->installment->name}")
            ->greeting("Hello {$client->name},")
            ->line("This is a friendly reminder that your payment is due in {$this->daysUntilDue} days.")
            ->line('')
            ->line('**Payment Details:**')
            ->line("Payment: {$this->installment->name}")
            ->line("Due Date: {$this->installment->due_date->format('F j, Y')}")
            ->line("Amount Due: €" . number_format($this->installment->amount, 2))
            ->line('')
            ->line('To ensure your booking is secured, please make your payment by the due date.')
            ->action('Make Payment', url('/client/payments/' . $schedule->id))
            ->line('')
            ->line('**Payment Methods:**')
            ->line('- Bank Transfer')
            ->line('- Credit/Debit Card')
            ->line('- Cash at our office')
            ->line('')
            ->line('If you have already made this payment, please disregard this reminder.')
            ->salutation('Thank you,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'installment_id' => $this->installment->id,
            'schedule_id' => $this->installment->payment_schedule_id,
            'client_id' => $this->installment->paymentSchedule->client_id,
            'amount' => $this->installment->amount,
            'due_date' => $this->installment->due_date->format('Y-m-d'),
            'days_until_due' => $this->daysUntilDue,
            'type' => 'payment_reminder',
        ];
    }
}
