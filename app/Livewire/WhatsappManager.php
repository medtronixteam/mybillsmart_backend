<?php

namespace App\Livewire;

use App\Models\Whatsapp;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
class WhatsappManager extends Component
{
    public $pingStatus= null;
     public function onChangeIn()
    {

    }
    public function callFirstApi(): void
    {
       try {
            $response = Http::get(config('services.wahaUrl').'api/ping');

            if ($response->successful()) {
                $this->pingStatus = true;
            } else {
                $this->pingStatus = false;
            }
        } catch (\Throwable $e) {
            $this->pingStatus = false;
        }
    }

    public function callSecondApi(): void
    {

        // Placeholder for the second API call (after 6s)
     //   Http::post('https://your-api.com/second-endpoint', []);
    }
    public function render()
    {

        $WhatsappSessions = Whatsapp::latest()->paginate(10);
        $WhatsappCounter = Whatsapp::latest()->count();

        return view('livewire.whatsapp-manager',compact('WhatsappSessions','WhatsappCounter'))->layout('layout.app');
    }

}
