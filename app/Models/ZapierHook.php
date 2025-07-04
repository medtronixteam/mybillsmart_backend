<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZapierHook extends Model
{
    protected $fillable = ['name', 'url', 'type'];


}
