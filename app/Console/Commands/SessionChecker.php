<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use App\Models\Whatsapp;
use Illuminate\Support\Facades\Http;
class SessionChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:checker';

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
    {   try {
        info("Waha---------------Session-Checker-----------------------");
            $heade=['content-type'=>'application/json'];
            $response = Http::withHeaders($heade)->get(config('services.wahaUrl').'api/sessions?all=true');
            if($response->failed() AND $response->status()!=200):
                info($response->status());
            else:
                $SessionArray=[];
                foreach(json_decode($response) as $val):
                    if($val->name=="WORKING"):
                        $SessionArray[]=$val->name;
                    endif;
                endforeach;
                 $WhatsappSessions=Whatsapp::where('session_name',$val->name)->get();
                 $c=0;
                foreach($WhatsappSessions as $session){
                    if(!in_array($session->session_name,$SessionArray)){
                        Whatsapp::where('session_name',$session->session_name)->delete();
                        $c++;
                    }
                }
                info("Waha---------------Session-Checker-------Removed----{$c}------------");
            endif;
        } catch (RequestException $e) {
        }
    }
}
