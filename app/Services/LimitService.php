<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Plan;
use App\Models\SubscriptionLimit;
use Illuminate\Support\Facades\DB;

class LimitService
{


    /**
     * Attempt to use a plan (main or expansion) and decrement the limit.
     * Returns true if a valid plan was found and limit was decremented.
     */
    public function useLimit(int $userId, string $limitType = 'invoices', bool $doDecreament = true,$returnCounter=false)
    {


       // $currentPlanType = User::where('id', $userId)->lockForUpdate()->first();
        // if ($currentPlanType->plan_name == 'free_trial') {

        //     // 7 days expired
        //     if (now()->greaterThan($currentPlanType->created_at->copy()->addDays(7))) {
        //         return false;
        //     }
        //     if ($limitType == "invoices") {
        //         $counterInvoice = Invoice::where('group_id', $userId)->count();
        //         $limitOfInvoice = Plan::where('name', 'free_trial')->value('invoices_per_month');
        //         if ($counterInvoice >= $limitOfInvoice) {
        //             return false;
        //         }
        //     }else{
        //          $counterAgents = User::where('group_id', $userId)->count();
        //         $limitOfAgents = Plan::where('name', 'free_trial')->value('agents_per_month');
        //         if ($counterAgents >= $limitOfAgents) {
        //             return false;
        //         }
        //     }



        //     return true;
        // }

        return DB::transaction(function () use ($userId, $limitType, $doDecreament,$returnCounter) {
            // Step 1: Try the main plan first
            $mainPlan = $this->getActivePlan($userId, $limitType);
             $limitCounter=0;
            if ($mainPlan) {
                $limit = $this->getActiveLimit($mainPlan, $limitType);

                if($returnCounter && $limit){
                      $limitCounter=$limit->limit_value;
                }else{
                    if ($limit && $limit->limit_value > 0) {
                        if (!$doDecreament) {
                            return true; // Limit available
                        }
                        return $this->decrementLimit($mainPlan, $limitType);
                        //return back
                    }
                }


            }

            // Step 2: If main plan failed, try expansions in order of purchase
            $expansionPlans = $this->getExpansionPlans($userId);

            foreach ($expansionPlans as $expansion) {
                $limit = $this->getActiveLimit($expansion, $limitType);
                if($returnCounter && $limit){
                      $limitCounter=$limitCounter+$limit->limit_value;
                }else{
                    if ($limit && $limit->limit_value > 0) {
                        if (!$doDecreament) {
                            return true; // Limit available
                        }
                        return $this->decrementLimit($expansion, $limitType);
                    }
                }

            }
            if($returnCounter){
                   return $limitCounter;
            }
            // No valid plan found
            return false;
        });
    }

    /**
     * Check if the user has an active main plan with available limit.
     */
    protected function getActivePlan(int $userId, string $limitType)
    {
        return Subscription::where('user_id', $userId)
            ->where('type', 'plan')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>', now());
            })->latest()
            ->lockForUpdate()
            ->first();
    }
    /**
     * Get all expansions for the user, ordered by oldest first.
     */
    protected function getExpansionPlans(int $userId)
    {
        return Subscription::where('user_id', $userId)
            ->where('type', 'expansion')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->get();
    }

    /**
     * Get active limit for a given subscription and limit type.
     */
    protected function getActiveLimit(Subscription $subscription, string $limitType)
    {
        return $subscription->limits()
            ->where('limit_type', $limitType)
            ->lockForUpdate()
            ->first();
    }

    /**
     * Decrease the limit value by 1 if available.
     */
    protected function decrementLimit(Subscription $subscription, string $limitType): bool
    {
        $limit = $this->getActiveLimit($subscription, $limitType);

        if (!$limit || $limit->limit_value <= 0) {
            return false;
        }

        $limit->limit_value -= 1;
        return $limit->save();
    }
}
