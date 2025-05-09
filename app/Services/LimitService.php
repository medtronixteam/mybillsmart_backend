<?php

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Plan;
use App\Models\SubscriptionLimit;
class LimitService
{

        public function createInvoice(Subscription $subscription)
        {
            $plan = Plan::where('name', $subscription->plan_name)->first();

            SubscriptionLimit::create([
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'limit_type' => 'invoices',
                'limit_value' => 0,
                'plan_limit' => $plan->invoices_per_month,
            ]);
        }
        public function createAgents(Subscription $subscription)
        {
            $plan = Plan::where('name', $subscription->plan_name)->first();

            SubscriptionLimit::create([
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'limit_type' => 'agents',
                'limit_value' => 0,
                'plan_limit' => $plan->agents_per_month,
            ]);
        }





}
