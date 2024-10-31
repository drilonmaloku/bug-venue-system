<?php

namespace App\Modules\Reservations\Notifications;

use App\Modules\Users\Services\UsersService;
use App\Models\User;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Models\ReservationComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class CommentAddedNotification extends Notification
{
    //use Queueable;

    public $comment;
    public $user;

    /**
     * Create a new notification instance.
     * @param ReservationComment $comment
     * @param User $user
     */
    public function __construct(
        ReservationComment $comment,
        User $user
    )
    {
        $this->comment = $comment;
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
            'message' =>  'Commenti: '.$this->comment->name.' u shtua nga perdoruesi: '.$this->user->name,
            'resource_type' => 'Comment',
            'resource_uid' =>$this->comment->id
        ];
    }
}
