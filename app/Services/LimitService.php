<?php

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Plan;
use App\Models\SubscriptionLimit;
class LimitService
{

        public function createInvoice()
        {
            $now =Carbon::now();
           $planed= Subscription::where(function ($query) use ($now) {
                $query->whereDate('start_date', '<=', $now)
                      ->whereDate('end_date', '>=', $now)->whereNot('type', 'plan');
            });

        }






}
