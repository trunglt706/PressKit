<?php

namespace App\Console\Commands;

use App\Services\HotArticleService;
use Illuminate\Console\Command;

class FlushHotArticleViewsCommand extends Command
{
    protected $signature = 'hot:flush-views';

    protected $description = 'Flush hot article view counters from Redis to analytics table';

    public function handle(HotArticleService $hotArticleService): int
    {
        $flushed = $hotArticleService->flushViewCountersToDatabase();

        $this->info(sprintf('Flushed %d views to analytics table.', $flushed));

        return self::SUCCESS;
    }
}
