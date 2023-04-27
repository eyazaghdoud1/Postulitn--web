<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    // Ajoutez ici les méthodes de la classe MailService
    public function sendEmail(string $to, string $subject, string $message)
    {

        $email = (new Email())
            ->from('postuli.tn@gmail.com')
            ->to($to)
            ->subject($subject)
            ->text($message);

        $this->mailer->send($email);
    }
}
