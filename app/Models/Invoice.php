<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded=['id'];
    protected $casts = [
        'bill_info' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }


}
