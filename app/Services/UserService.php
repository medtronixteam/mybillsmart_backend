<?php

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Plan;
class UserService
{
    public function getTotalInvoiceLimit(?User $user = null): int
    {
        $user = $user ?: auth('sanctum')->user();

        if (!$user) {
            return 0;
        }
        // Base limit from active plan
        $baseLimit = $this->getPlanLimit($user);

        // Additional limit from active expansion packs
        $expansionLimit = $this->getExpansionPackLimits($user);

        return $baseLimit + $expansionLimit;
    }

    protected function getPlanLimit($user)
    {
        $activePlan = $user()->activeSubscriptions()
            ->latest()
            ->first();
        if (!$activePlan) {
            return 0; // Default or fallback
        }
        $plans = Plan::where('name', $activePlan->plan_name)->first();
        return $plans->agents ?? 0;
    }

    protected function getExpansionPackLimits($user)
    {
        return $user->activeOrtherSubscriptions()
                    ->sum(function ($sub) {
                        $plans = Plan::where('name', $sub->name)->first();
                        return $plans->agents ?? 0;
                    });
    }
}
