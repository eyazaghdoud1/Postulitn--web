<?php

namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService{

    public function __construct(private MailerInterface $mailer)
    {
        
    }
    public function sendEmail( $to, $subject,$content): void
    {
        
        $email = (new Email())
            ->from('postuli.tn@gmail.com')
            ->to($to)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);

       
    }
}