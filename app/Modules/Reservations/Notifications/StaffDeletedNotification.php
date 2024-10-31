<?php

namespace App\Modules\Reservations\Notifications;

use App\Models\User;
use App\Modules\Reservations\Models\ReservationStaff;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StaffDeletedNotification extends Notification
{
    use Queueable;

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
    ) {
        $this->staff = $staff;
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Stafi me id: ' . $this->staff->id . ' dhe emer: ' . $this->staff->name . ' u fshie nga perdoruesi: ' . $this->user->name,
        ];
    }
}
