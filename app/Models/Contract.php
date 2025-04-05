<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
   protected $guarded=['id'];
   public function offer()
   {
       return $this->belongsTo(Offer::class,'offer_id');
   }
}
