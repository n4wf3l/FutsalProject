<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Loi 09-08 compliance: monthly purge of rejected player applications older than 6 months.
Schedule::command('applications:purge-expired')
    ->monthlyOn(1, '03:00')
    ->timezone('Africa/Casablanca');
