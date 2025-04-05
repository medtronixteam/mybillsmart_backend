<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $guarded=['id'];
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

}
