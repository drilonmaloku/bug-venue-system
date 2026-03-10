<?php

namespace App\Notifications;

use App\Modules\Payments\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentOverdueNotification extends Notification implements ShouldQueue
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

        $mail = (new MailMessage)
            ->subject("URGENT: Overdue Payment - {$this->installment->name}")
            ->greeting("Dear {$client->name},")
            ->error()
            ->line('**Your payment is overdue.**')
            ->line('')
            ->line('**Payment Details:**')
            ->line("Payment: {$this->installment->name}")
            ->line("Original Due Date: {$this->installment->due_date->format('F j, Y')}")
            ->line("Days Overdue: {$this->daysOverdue}")
            ->line("Original Amount: €" . number_format($this->installment->amount, 2));

        if ($this->installment->late_fees_accrued > 0) {
            $mail->line("Late Fees: €" . number_format($this->installment->late_fees_accrued, 2));
        }

        $mail->line("**Total Due: €" . number_format($totalDue, 2) . "**")
            ->line('')
            ->line('To avoid any disruption to your booking, please make payment immediately.')
            ->action('Pay Now', url('/client/payments/' . $schedule->id))
            ->line('')
            ->line('**Need assistance?**')
            ->line('Please contact our accounts team immediately.')
            ->salutation('Regards,');

        return $mail;
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
            'amount_outstanding' => $this->installment->amount_outstanding,
            'late_fees' => $this->installment->late_fees_accrued,
            'total_due' => $totalDue,
            'due_date' => $this->installment->due_date->format('Y-m-d'),
            'days_overdue' => $this->daysOverdue,
            'severity' => \App\Modules\Payments\Models\LatePaymentAlert::calculateSeverity($this->daysOverdue),
            'type' => 'payment_overdue',
        ];
    }
}
