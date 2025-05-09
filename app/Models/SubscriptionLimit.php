<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionLimit extends Model
{
    protected $fillable = [
        'subscription_id',
        'user_id',
        'limit_type',
        'limit_value',
        'plan_limit',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
