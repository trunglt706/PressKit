<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sitemap:generate')->dailyAt('01:00');
Schedule::command('hot:flush-views')->everyFiveMinutes();
Schedule::command('hot:calculate-score')->hourly();
