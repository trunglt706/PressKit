<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class RegenerateSitemapJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use Queueable;

    public int $uniqueFor = 60;

    public function handle(): void
    {
        Artisan::call('sitemap:generate');
    }
}
