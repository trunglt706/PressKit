<?php

namespace App\Console\Commands;

use App\Jobs\CalculateHotScoreJob;
use Illuminate\Console\Command;

class CalculateHotArticleScoreCommand extends Command
{
    protected $signature = 'hot:calculate-score';

    protected $description = 'Dispatch job to calculate hot article scores and rankings';

    public function handle(): int
    {
        CalculateHotScoreJob::dispatch();

        $this->info('Hot score calculation job dispatched.');

        return self::SUCCESS;
    }
}
