<?php

use App\Kernel;

//use Twilio\Rest\Client;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

/*$account_sid = 'AC8ad7d8957eb7bf1ff6618633e704aeec';
$auth_token = 'd32455223143bdbbf0fd0abcd860d56e';
$twilio_number = "+16203496432";
$client = new Client($account_sid, $auth_token);*/

//require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
