<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('messages:send-auto')
    ->everyFiveSeconds()->runInBackground();

Schedule::command('session:checker')
    ->everyFiveSeconds();
//every 5 minutes destory the non stoped sessions except working
Schedule::command('waha:session-destoryer')
    ->everyFiveMinutes();


