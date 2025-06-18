<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subscription;
use App\Models\SubscriptionLimit;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Http\Controllers\NotificationController;
class ManageSubscriptions extends Component
{
    public $showModal = false;
    public $userId;
    public $users = [];

    public $agents = 0;
    public $invoices = 0;
    public $start_date = '';
    public $end_date = '';
    public $payment_intent_id = 2;

    protected $rules = [
        'agents' => 'required|numeric|min:0',
        'invoices' => 'required|numeric|min:0',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
    ];


    public function startFreeTrial()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {

        $this->agents = 0;
        $this->invoices = 0;
        $this->start_date = '';
        $this->end_date = '';
    }

    public function confirmStartTrial()
    {
        $this->validate();

        // If end_date not provided, auto-calculate 7 days from start_date
        if (!$this->end_date) {
            $this->end_date = Carbon::parse($this->start_date)->addDays(7);
        }
       $subscription= Subscription::where('user_id', $this->userId)->where('plan_name', 'free_trial')->first();
        if($subscription){
            $limitInvoices=  SubscriptionLimit::where('subscription_id', $subscription->id)->where('limit_type', 'invoices')->first();
            $agentsInvoices= SubscriptionLimit::where('subscription_id', $subscription->id)->where('limit_type', 'agents')->first();

        $subscription->update([
            'payment_intent_id' => 2,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => 'active',
            'type' => "plan",
            'plan_duration' => "monthly",
        ]);

            if($limitInvoices ){
                  $limitInvoices->update(
                    [
                        'limit_value' => $this->invoices + $limitInvoices->limit_value,
                        'plan_limit' => $this->invoices + $limitInvoices->plan_limit,

                    ]
                );
            }else{
                if($this->invoices>0){
                    $limitInvoices->create(
                    [
                        'limit_type' =>'invoices',
                        'subscription_id' => $subscription->id ,
                        'user_id' => $this->userId ,
                        'limit_value' => $this->invoices ,
                        'plan_limit' => $this->invoices ,

                    ]
                );
                }


            }
             if($agentsInvoices){
                  $agentsInvoices->update(
                    [
                        'limit_value' => $this->agents + $agentsInvoices->limit_value,
                        'plan_limit' => $this->agents + $agentsInvoices->plan_limit,

                    ]
                );
            }else{
                if($this->agents>0){
                    $limitInvoices->create(
                    [
                        'limit_type' =>'agents',
                        'subscription_id' => $subscription->id ,
                        'user_id' => $this->userId ,
                        'limit_value' => $this->agents ,
                        'plan_limit' => $this->agents ,

                    ]
                );
                }


            }
            NotificationController::pushNotification($this->userId, 'Free Trial', 'myBillSmart granted you a free trial.');
        }

        session()->flash('message', 'User free trial has been extended.');

        $this->showModal = false;
    }

    public function render()
    {
        $subscriptions = Subscription::with('user')->where('user_id',$this->userId)->latest()->get(); // Example query

        return view('livewire.manage-subscriptions', compact('subscriptions'))->layout('layout.app');
    }
}
