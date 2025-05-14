<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AutoMessage;
use App\Models\Whatsapp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Log;

class SendAutoMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:send-auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send auto messages based on the schedule';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         Log::info('Waha------Campaign--Running ----->  ');
        // Fetch all messages that are scheduled to be sent and have a status of 1
        $messages = AutoMessage::where('status', 0)
            ->whereDate('date_send', Carbon::today())
            ->whereTime('time_send', '<=', now()->format('H:i'))
            ->get();
         Log::info('Waha------Campaign--1------------->  ');
        foreach ($messages as $message) {
            $WhatsappFirst=Whatsapp::where('user_id',$message->user_id)->first();
             $notifcation =new NotificationController();
            if($WhatsappFirst){
            Log::info('Waha------Campaign--2----->  '.$message->user_id."-".$WhatsappFirst->session_name);
                // Assuming you have a method to send SMS or any other type of message
                $contactExists = $this->checkContactExists($message->to_number,$WhatsappFirst->session_name);
                if ($contactExists) {
                    // Send the message
                    $this->sendMessage($message,$WhatsappFirst->session_name);
                    // Update the status of the message after sending

                } else {

                    if(!$message->reason){

                        $notifcation->pushNotification($message->user_id,'Oops Contact not exists on whatapp.',"Your provided number {$message->to_number} is invalid to send campaign message.");
                    }
                    $message->update(['reason' => "Your provided number is invalid to send campaign message.",'status'=>2]);

                }

            }else{
                  if(!$message->reason){

                        $notifcation->pushNotification($message->user_id,'Oops We found your whatsapp is not linked.',"Failed to send campaign Message. Your Whatsapp is not linked.");
                    }
                        $message->update(['reason' => "Failed to send campaign Message. Your Whatsapp is not linked.",'status'=>2]);

            }

        }

        return Command::SUCCESS;
    }

    /**
     * Send the message to the specified number.
     *
     * @param string $toNumber
     * @param string $message
     * @return void
     */
    private function checkContactExists(string $toNumber,$session): bool
    {
        try {
           $toNumber= str_replace('+','',$toNumber);
            $response = Http::get(config('services.wahaUrl')."api/contacts/check-exists?phone={$toNumber}&session={$session}");
            if ($response->successful()) {
               return true;
            } else {
                Log::info('Waha------Campaign- Failded to Check-----Number----> : ' .json_encode($response->json()));
                return false;
            }

        } catch (RequestException $e) {
            Log::info('Waha------Campaign--Contact Exists-----> : ' .$e->getMessage());
            return false;
        }
    }

    /**
     * Send the message to the specified number using the API.
     *
     * @return void
     */
    private function sendMessage($message,$session_name)
    {
        try {
            $payload = [
                "chatId" => $message->to_number . "@c.us",
                "reply_to" => null,
                "text" => $message->message,
                "linkPreview" => true,
                "linkPreviewHighQuality" => false,
                "session" => $session_name,
            ];

            $response = Http::post(config('services.wahaUrl')."api/sendText", $payload);

            if ($response->successful()) {
                $notifcation =new NotificationController();
                $notifcation->pushNotification($message->user_id,'Campaign message has been sent',"Campaign message has been sent to {$message->to_number}");
                $message->update(['status'=>1]);
            } else {
                 $message->update(['status'=>0]);
                Log::info('Waha------Campaign- Failded to send-----SMS----> : ' .json_encode($response->json()));
            }
        } catch (RequestException $e) {
            Log::info('Waha------Campaign- Send--SMS----> : ' .$e->getMessage());

        }
    }

}
