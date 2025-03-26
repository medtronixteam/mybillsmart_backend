<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client('AC34c70e7bbcc1633f1e4ea4ea0c5b8bea', '179a2dadd18e3cee0a92df2dc45d22d1');
        $this->from = 'whatsapp:+17017604097';
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
