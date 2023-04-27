<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioSmsService
{
    private $client;
    private $fromNumber;

    public function __construct(Client $client, string $fromNumber)
    {
        $this->client = $client;
        $this->fromNumber = $fromNumber;
    }

    public function sendSms(string $toNumber, string $message)
    {
        $this->client->messages->create(
            $toNumber,
            [
                'from' => $this->fromNumber,
                'body' => $message
            ]
        );
    }
}
