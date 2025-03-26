<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        $this->from = env('TWILIO_WHATSAPP_FROM');
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
