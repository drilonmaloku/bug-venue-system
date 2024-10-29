<?php

namespace App\Modules\Reservations\Notifications;


use App\Models\User;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class ReservationUpdatedNotification extends Notification
{
    //use Queueable;

    public $reservation;
    public $user;

    /**
     * Create a new notification instance.
     * @param Reservation $Reservation
     * @param User $user
     */
    public function __construct(
        Reservation $reservation,
        User $user
    )
    {
        $this->reservation = $reservation;
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
            'message' =>  'Reservation me id: '.$this->reservation->id.' dhe emer:'.$this->reservation->name .' u be update nga perdoruesi: '.$this->user->name,
            'resource_type' => 'Reservation',
            'resource_uid' =>$this->reservation->id
        ];
    }
}
