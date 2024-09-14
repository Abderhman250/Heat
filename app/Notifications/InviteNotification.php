<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InviteNotification extends Notification
{
    use Queueable;

    public $group_id;

    public function __construct($group_id)
    {
        $this->group_id = $group_id;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can add other channels here like 'database', 'sms', etc.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Invitation to Group')
            ->markdown('emails.invite', ['group_id' => $this->group_id]);
    }
}

