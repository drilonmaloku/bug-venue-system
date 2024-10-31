<?php

namespace App\Modules\Reservations\Notifications;

use App\Models\User;
use App\Modules\Reservations\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvoicesDeletedNotification extends Notification
{
    use Queueable;

    public $invoice;
    public $user;

    /**
     * Create a new notification instance.
     * @param Invoice $invoice
     * @param User $user
     */
    public function __construct(
        Invoice $invoice,
        User $user
    ) {
        $this->invoice = $invoice;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Sherbimi me id: ' . $this->invoice->id . ' dhe emer: ' . $this->invoice->name . ' u fshi nga perdoruesi: ' . $this->user->name,
        ];
    }
}
