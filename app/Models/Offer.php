<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $guarded = ['id'];
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
