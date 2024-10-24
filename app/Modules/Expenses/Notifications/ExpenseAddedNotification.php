<?php

namespace App\Modules\Expenses\Notifications;


use App\Models\User;
use App\Modules\Expenses\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class ExpenseAddedNotification extends Notification
{
    //use Queueable;

    public $expense;
    public $user;

    /**
     * Create a new notification instance.
     * @param Expense $expense
     * @param User $user
     */
    public function __construct(
        Expense $expense,
        User $user
    )
    {
        $this->expense = $expense;
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
            'message' =>  'Shpenzimin me shumen: '.$this->expense->amount.' u shtua nga perdoruesi: '.$this->user->name,
            'resource_type' => 'expense',
            'resource_uid' =>$this->expense->id
        ];
    }
}
