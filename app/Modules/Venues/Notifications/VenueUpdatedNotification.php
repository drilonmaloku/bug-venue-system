<?php

namespace App\Modules\Venues\Notifications;


use App\Models\User;
use App\Modules\Venues\Models\Venue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class VenueUpdatedNotification extends Notification
{
    //use Queueable;

    public $venue;
    public $user;

    /**
     * Create a new notification instance.
     * @param Venue $venue
     * @param User $user
     */
    public function __construct(
        Venue $venue,
        User $user
    )
    {
        $this->venue = $venue;
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
            'message' =>  'Salla me id: '.$this->venue->id.' u be update nga perdoruesi: '.$this->user->name,
            'resource_type' => 'venue',
            'resource_uid' => $this->venue->id
        ];
    }
}
