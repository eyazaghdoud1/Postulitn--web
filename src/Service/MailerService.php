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
        //$to = 'eya.zaghdoud@esprit.tn';
       // $content = '<p>See Twig integration for better HTML integration!</p>';
        $email = (new Email())
            ->from('postuli.tn@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            //->text('Sending emails is fun again!')
            ->html($content);

        $this->mailer->send($email);

        // ...
    }
}