<?php

namespace App\Services;

use Swift_Mailer as Mailer;
use Swift_Message as Message;

class MailerService
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(Message $message): int
    {
        return $this->mailer->send($message);
    }
}
