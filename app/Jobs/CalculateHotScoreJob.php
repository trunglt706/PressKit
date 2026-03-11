<?php

namespace App\Jobs;

use App\Services\HotArticleService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class CalculateHotScoreJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function handle(HotArticleService $hotArticleService): void
    {
        $hotArticleService->calculateHotScores();
    }
}
