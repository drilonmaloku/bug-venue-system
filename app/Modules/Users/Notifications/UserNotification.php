<?php

declare(strict_types=1);

namespace App\Modules\Users\Notifications;

use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use function Illuminate\Mail\Mailables\subject;

class UserNotification extends Notification
{
    public function via ($notifiable) {
        return ['mail'];
    }

    public function toMail()
    {
        $url = route("login");

        return (new MailMessage)
            ->line("Dear User,")
            ->line("Thank you for considering Huber’s People for your career journey.")
            ->line("Your application is valuable to us, and we will review it within one week. Your patience is appreciated.")
            ->line("If you have any questions, do not hesitate to contact us.")
            ->line("Kind regards,")
            ->line("Huber’s People")
            ->action("Log In", $url)
            ->subject("Thank you for your registration at Huber’s");
    }
}
