<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TwilioSmsService;

class SmsController extends AbstractController
{
    public function sendSms(TwilioSmsService $smsService): Response
    {
        $toNumber = ''; // recipient's phone number
        $message = 'Hello, this is a test SMS message!';

        $smsService->sendSms($toNumber, $message);

        return new Response('SMS sent successfully!');
    }
}
