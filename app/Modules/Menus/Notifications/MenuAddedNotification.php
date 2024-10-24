<?php

namespace App\Modules\Menus\Notifications;


use App\Models\User;
use App\Modules\Menus\Models\Menu;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class MenuAddedNotification extends Notification
{
    //use Queueable;

    public $menu;
    public $user;

    /**
     * Create a new notification instance.
     * @param Menu $menu
     * @param User $user
     */
    public function __construct(
        Menu $menu,
        User $user
    )
    {
        $this->menu = $menu;
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
            'message' =>  'Menu: '.$this->menu->name.' u shtua nga perdoruesi: '.$this->user->name,
            'resource_type' => 'menu',
            'resource_uid' =>$this->menu->id
        ];
    }
}
