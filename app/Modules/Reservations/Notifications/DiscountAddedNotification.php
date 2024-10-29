<?php

namespace App\Modules\Reservations\Notifications;

use App\Modules\Users\Services\UsersService;
use App\Models\User;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Models\Discount;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class DiscountAddedNotification extends Notification
{
    //use Queueable;

    public $discount;
    public $user;

    /**
     * Create a new notification instance.
     * @param Discount $reservation
     * @param User $user
     */
    public function __construct(
        Discount $discount,
        User $user
    )
    {
        $this->discount = $discount;
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
            'message' =>  'Zbritja: '.$this->discount->name.' u shtua nga perdoruesi: '.$this->user->name,
            'resource_type' => 'Discount',
            'resource_uid' =>$this->discount->id
        ];
    }
}
