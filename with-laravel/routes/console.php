<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the user inactivity check to run every 30 minutes
Schedule::command('users:check-inactivity')
    ->everyThirtyMinutes()
    ->withoutOverlapping()
    ->runInBackground();
