<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(config('services.TWILIO.SID'), config('services.TWILIO.AUTH_TOKEN'));
        $this->from = config('services.TWILIO.WHATSAPP_FROM');
    }

    public function sendWhatsAppMessage($to, $message, $mediaUrl = null)
    {
        $data = [
            'from' => $this->from,
            'to' => "whatsapp:$to",
            'body' => $message,
        ];

        if ($mediaUrl) {
            $data['mediaUrl'] = [$mediaUrl];
        }

        return $this->client->messages->create($to, $data);
    }
}
