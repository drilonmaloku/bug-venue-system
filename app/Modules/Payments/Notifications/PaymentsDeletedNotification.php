<?php

namespace App\Modules\Payments\Notifications;


use App\Models\User;
use App\Modules\Payments\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class PaymentsDeletedNotification extends Notification
{
    //use Queueable;

    public $payment;
    public $user;

    /**
     * Create a new notification instance.
     * @param Payment $payments
     * @param User $user
     */
    public function __construct(
        Payment $payment,
        User $user
    )
    {
        $this->payment = $payment;
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
            'message' =>  'Pagesa me id: '.$this->payment->id.' nga perdoruesi: '.$this->user->name, 'u fshie',
        ];
    }
}
