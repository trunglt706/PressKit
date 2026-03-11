<?php

namespace App\Jobs;

use App\Services\HotArticleService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class IncrementArticleViewJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function __construct(
        public int $articleId
    ) {
    }

    public function handle(HotArticleService $hotArticleService): void
    {
        $hotArticleService->trackView($this->articleId);
    }
}
