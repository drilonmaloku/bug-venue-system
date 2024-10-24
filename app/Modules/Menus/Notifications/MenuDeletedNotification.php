<?php

namespace App\Modules\Menus\Notifications;


use App\Models\User;
use App\Modules\Menus\Models\Menu;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class MenuDeletedNotification extends Notification
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
            'message' =>  'Menu me id: '.$this->menu->id.' dhe emer: '.$this->menu->name.' u fshi nga perdoruesi: '.$this->user->name,
        ];
    }
}
