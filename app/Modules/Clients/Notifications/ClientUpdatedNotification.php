<?php

namespace App\Modules\Clients\Notifications;


use App\Models\User;
use App\Modules\Clients\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class ClientUpdatedNotification extends Notification
{
    //use Queueable;

    public $client;
    public $user;

    /**
     * Create a new notification instance.
     * @param Client $client
     * @param User $user
     */
    public function __construct(
        Client $client,
        User $user
    )
    {
        $this->client = $client;
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
            'message' =>  'Klienti: '. $this->client->full_name.' u be update nga perdoruesi: '.$this->user->name,
            'resource_type' => 'client',
            'resource_uid' =>$this->client->id
        ];
    }
}
