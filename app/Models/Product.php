<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
   protected $guarded=['id'];
   public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    protected $casts = [
        'fixed_rate' => 'float',
        'rl1' => 'float',
        'rl2' => 'float',
        'rl3' => 'float',
        'p1' => 'float',
        'p2' => 'float',
        'p3' => 'float',
        'p4' => 'float',
        'p5' => 'float',
        'p6' => 'float',
        'meter_rental' => 'float',
        'sales_commission' => 'float',

    ];
}

