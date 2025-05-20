<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class SessionDestoryer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waha:session-destoryer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            info("Waha---------------Session-Checker-----------------------");
            $heade = ['content-type' => 'application/json'];
            $response = Http::withHeaders($heade)->get(config('services.wahaUrl') . 'api/sessions?all=true');
            if ($response->failed() and $response->status() != 200):
                info($response->status());
            else:

                foreach (json_decode($response) as $val):
                    if ($val->status == "SCAN_QR_CODE"):
                        $this->destroySession($val->name);
                    endif;
                     if ($val->status == "STOPPED"):
                        $this->deleteSession($val->name);
                    endif;
                endforeach;


            endif;
        } catch (RequestException $e) {
        }
    }
    function destroySession($session_name)
    {
        try {
            $response = Http::withHeaders(['content-type' => 'application/json'])
                ->get(config('services.wahaUrl') . "api/sessions/{$session_name}/stop");

            if ($response->successful()) {
            } else {
                info("Failed to destroy session----------> " . json_encode($response->body()));
            }
        } catch (RequestException $e) {
            info("Failed to destroy session----------> " . json_encode($e->getMessage()));
        }
    }
     function deleteSession($session_name)
    {
        try {
            $response = Http::withHeaders(['content-type' => 'application/json'])
                ->delete(config('services.wahaUrl') . "api/sessions/{$session_name}");

            if ($response->successful()) {
            } else {
                info("Failed to delete session----------> " . json_encode($response->body()));
            }
        } catch (RequestException $e) {
            info("Failed to delete session-------------> " . json_encode($e->getMessage()));
        }
    }

}
