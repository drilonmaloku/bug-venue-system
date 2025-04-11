<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Modules\LocationPayments\Models\LocationInvoice;
use App\Modules\Location\Models\Location;

class MonthlyInvoiceGenerated extends Notification implements ShouldQueue // Optional: Implement ShouldQueue for background sending
{
    use Queueable;

    public LocationInvoice $invoice;
    public Location $location;

    /**
     * Create a new notification instance.
     */
    public function __construct(LocationInvoice $invoice, Location $location)
    {
        $this->invoice = $invoice;
        $this->location = $location;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Check user preferences or default to both
        // For simplicity, we'll send both for now
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $invoiceUrl = route('location.invoices.show', ['location' => $this->location->id, 'invoice' => $this->invoice->id]);
        $amountFormatted = number_format($this->invoice->amount, 2);

        return (new MailMessage)
                    ->subject("New Monthly Invoice Generated for {$this->location->name}")
                    ->greeting("Hello {$notifiable->first_name},")
                    ->line("A new monthly invoice (Number: {$this->invoice->invoice_number}) has been generated for your location: {$this->location->name}.")
                    ->line("Amount Due: €{$amountFormatted}")
                    ->line("Description: {$this->invoice->description}")
                    ->line("Due Date: {$this->invoice->due_date->format('Y-m-d')}")
                    ->action('View Invoice', $invoiceUrl)
                    ->line('Thank you for using our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'location_id' => $this->location->id,
            'location_name' => $this->location->name,
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'amount' => $this->invoice->amount,
            'message' => "New monthly invoice #{$this->invoice->invoice_number} (€" . number_format($this->invoice->amount, 2) . ") generated for {$this->location->name}."
        ];
    }
}
