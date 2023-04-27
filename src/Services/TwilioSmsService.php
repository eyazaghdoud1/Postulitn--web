<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioSmsService
{
    private $twilioClient;
    private $twilioFromNumber;

    public function __construct(string $twilioSid, string $twilioAuthToken, string $twilioFromNumber)
    {
        $this->twilioFromNumber = $twilioFromNumber;
        $this->twilioClient = new Client($twilioSid, $twilioAuthToken);
    }


    public function sendSms(string $toNumber, string $message)
    {
        $this->twilioClient->messages->create(
            $toNumber,
            [
                'from' => $this->twilioFromNumber,
                'body' => $message
            ]
        );
    }
}
