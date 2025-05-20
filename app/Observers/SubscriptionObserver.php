<?php

namespace App\Observers;
use App\Models\Subscription;
use App\Models\SubscriptionLimit;
use App\Models\Plan;
class SubscriptionObserver
{
    public function created(Subscription $subscription)
    {
        $this->createSubscriptionLimits($subscription);
    }

    protected function createSubscriptionLimits(Subscription $subscription)
    {
        $plan = Plan::where('name', $subscription->plan_name)->first();
        if(in_array($plan->name, ['starter', 'pro', 'enterprise'])){
            SubscriptionLimit::create([
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'limit_type' => 'agents',
                'limit_value' =>  $plan->agents_per_month,
                'plan_limit' => $plan->agents_per_month,
            ]);
            SubscriptionLimit::create([
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'limit_type' => 'invoices',
                'limit_value' => $plan->invoices_per_month,
                'plan_limit' => $plan->invoices_per_month,
            ]);
        }elseif(in_array($plan->name, ['growth_pack', 'scale_pack','max_pack'])){
            SubscriptionLimit::create([
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'limit_type' => 'agents',
                'limit_value' => $plan->agents_per_month,
                'plan_limit' => $plan->agents_per_month,
            ]);
        }else{
            SubscriptionLimit::create([
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'limit_type' => 'invoices',
                'limit_value' =>  $plan->invoices_per_month,
                'plan_limit' => $plan->invoices_per_month,
            ]);
        }

    }
}
