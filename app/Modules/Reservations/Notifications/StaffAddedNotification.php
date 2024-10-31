<?php

namespace App\Modules\Reservations\Notifications;

use App\Modules\Users\Services\UsersService;
use App\Models\User;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Models\ReservationStaff;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class StaffAddedNotification extends Notification
{
    //use Queueable;

    public $staff;
    public $user;

    /**
     * Create a new notification instance.
     * @param ReservationStaff $staff
     * @param User $user
     */
    public function __construct(
        ReservationStaff $staff,
        User $user
    )
    {
        $this->staff = $staff;
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
            'message' =>  'Stafi: '.$this->staff->name.' u shtua nga perdoruesi: '.$this->user->name,
            'resource_type' => 'Staff',
            'resource_uid' =>$this->staff->id
        ];
    }
}
