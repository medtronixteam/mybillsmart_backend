<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HookLog extends Model
{
    protected $guarded = ['id'];

    public function hook()
    {
        return $this->belongsTo(ZapierHook::class);
    }
}
