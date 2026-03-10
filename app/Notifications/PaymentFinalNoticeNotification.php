<?php

namespace App\Notifications;

use App\Modules\Payments\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFinalNoticeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The installment instance.
     */
    protected Installment $installment;

    /**
     * Days overdue.
     */
    protected int $daysOverdue;

    /**
     * Create a new notification instance.
     */
    public function __construct(Installment $installment, int $daysOverdue)
    {
        $this->installment = $installment;
        $this->daysOverdue = $daysOverdue;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $schedule = $this->installment->paymentSchedule;
        $client = $schedule->client;
        $totalDue = $this->installment->amount_outstanding + $this->installment->late_fees_accrued;

        return (new MailMessage)
            ->subject("FINAL NOTICE: Payment Overdue - {$this->installment->name}")
            ->greeting("Dear {$client->name},")
            ->error()
            ->line('**FINAL NOTICE - IMMEDIATE ACTION REQUIRED**')
            ->line('')
            ->line("Your payment is {$this->daysOverdue} days overdue. This is your final notice.")
            ->line('')
            ->line('**Payment Details:**')
            ->line("Payment: {$this->installment->name}")
            ->line("Original Due Date: {$this->installment->due_date->format('F j, Y')}")
            ->line("Original Amount: €" . number_format($this->installment->amount, 2))
            ->line("Late Fees Accrued: €" . number_format($this->installment->late_fees_accrued, 2))
            ->line("**TOTAL DUE: €" . number_format($totalDue, 2) . "**")
            ->line('')
            ->line('**Failure to make payment immediately may result in:**')
            ->line('- Cancellation of your reservation')
            ->line('- Additional late fees')
            ->line('- Legal action to recover the debt')
            ->line('- Reporting to credit agencies')
            ->line('')
            ->line('To avoid these consequences, please make payment within 24 hours.')
            ->action('Pay Immediately', url('/client/payments/' . $schedule->id))
            ->line('')
            ->line('**Need to discuss payment options?**')
            ->line('Contact our accounts team immediately:')
            ->line('Email: accounts@venue.com')
            ->line('Phone: +1 (555) 123-4567')
            ->line('')
            ->line('We strongly urge you to resolve this matter promptly.')
            ->salutation('Regards,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $totalDue = $this->installment->amount_outstanding + $this->installment->late_fees_accrued;

        return [
            'installment_id' => $this->installment->id,
            'schedule_id' => $this->installment->payment_schedule_id,
            'client_id' => $this->installment->paymentSchedule->client_id,
            'client_name' => $this->installment->paymentSchedule->client->name ?? 'Unknown',
            'installment_name' => $this->installment->name,
            'amount' => $this->installment->amount,
            'total_due' => $totalDue,
            'days_overdue' => $this->daysOverdue,
            'type' => 'payment_final_notice',
            'priority' => 'high',
        ];
    }
}
