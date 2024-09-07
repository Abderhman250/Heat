<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class Invite extends Mailable
{
    use Queueable, SerializesModels;

    public $group_id;

    public function __construct($group_id)
    {
        $this->group_id = $group_id;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Invitation to Group')
            ->markdown('emails.invite', ['group_id' => $this->group_id]);
    }
}
