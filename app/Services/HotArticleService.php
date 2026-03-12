<?php

namespace App\Services;

use App\Models\Analytics;
use App\Models\Article;
use App\Models\Trending;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Throwable;

class HotArticleService
{
    /**
     * Track article view in Redis for near real-time counting.
     */
    public function trackView(int $articleId): void
    {
        try {
            Redis::incr($this->viewCounterKey($articleId));
            Log::info("increase view");
        } catch (Throwable $exception) {
            Log::warning('Skip tracking article view because Redis is unavailable.', [
                'article_id' => $articleId,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Flush Redis counters to analytics table.
     */
    public function flushViewCountersToDatabase(?CarbonInterface $trackedDate = null): int
    {
        $date = ($trackedDate ?: now())->toDateString();

        try {
            $keys = Redis::keys('hot:article:*:views');
        } catch (Throwable $exception) {
            Log::warning('Skip flushing hot article views because Redis is unavailable.', [
                'error' => $exception->getMessage(),
            ]);

            return 0;
        }

        if ($keys === []) {
            return 0;
        }

        $flushedCount = 0;

        foreach ($keys as $key) {
            if (!preg_match('/hot:article:(\d+):views$/', (string) $key, $matches)) {
                continue;
            }

            $articleId = (int) $matches[1];
            $views = (int) Redis::get($key);

            if ($views <= 0) {
                Redis::del($key);
                continue;
            }

            $analytics = Analytics::query()->firstOrCreate(
                [
                    'article_id' => $articleId,
                    'tracked_date' => $date,
                ],
                [
                    'views' => 0,
                    'likes' => 0,
                    'shares' => 0,
                ]
            );

            $analytics->increment('views', $views);
            Redis::del($key);
            $flushedCount += $views;
        }

        return $flushedCount;
    }

    /**
     * Calculate hot scores and persist trend rankings.
     */
    public function calculateHotScores(?CarbonInterface $trackedDate = null): int
    {
        $date = ($trackedDate ?: now())->toDateString();
        $calculatedAt = now()->startOfHour();

        $analyticsRows = Analytics::query()
            ->whereDate('tracked_date', $date)
            ->with(['article' => function ($query): void {
                $query->withCount('comments');
            }])
            ->get();

        if ($analyticsRows->isEmpty()) {
            try {
                Redis::del('hot:ranking:articles');
            } catch (Throwable $exception) {
                Log::warning('Skip clearing hot ranking cache because Redis is unavailable.', [
                    'error' => $exception->getMessage(),
                ]);
            }

            return 0;
        }

        $scoredRows = [];

        foreach ($analyticsRows as $analytics) {
            /** @var Article|null $article */
            $article = $analytics->article;
            if (!$article) {
                continue;
            }

            $viewScore = (int) $analytics->views * 1;
            $commentScore = (int) ($article->comments_count ?? 0) * 5;
            $shareScore = (int) $analytics->shares * 8;
            $freshScore = $this->freshScore($article, $calculatedAt);

            $score = $viewScore + $commentScore + $shareScore + $freshScore;

            $scoredRows[] = [
                'article_id' => $article->id,
                'score' => $score,
            ];
        }

        usort($scoredRows, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        $redisAvailable = true;
        try {
            Redis::del('hot:ranking:articles');
        } catch (Throwable $exception) {
            $redisAvailable = false;
            Log::warning('Continue calculating hot scores without Redis cache update.', [
                'error' => $exception->getMessage(),
            ]);
        }

        foreach ($scoredRows as $index => $item) {
            $rank = $index + 1;

            Trending::query()->updateOrCreate(
                [
                    'article_id' => $item['article_id'],
                    'calculated_at' => $calculatedAt,
                ],
                [
                    'score' => $item['score'],
                    'rank' => $rank,
                ]
            );

            if ($redisAvailable) {
                try {
                    Redis::zadd('hot:ranking:articles', $item['score'], (string) $item['article_id']);
                } catch (Throwable $exception) {
                    $redisAvailable = false;
                    Log::warning('Stop writing hot ranking cache because Redis became unavailable.', [
                        'article_id' => $item['article_id'],
                        'error' => $exception->getMessage(),
                    ]);
                }
            }
        }

        return count($scoredRows);
    }

    /**
     * Build the Redis key used to store the view counter for an article.
     */
    private function viewCounterKey(int $articleId): string
    {
        return sprintf('hot:article:%d:views', $articleId);
    }

    /**
     * Return a recency bonus score based on how many hours have elapsed since
     * the article was published at the time of score calculation.
     * Score: 20 (≤24 h) · 10 (≤72 h) · 0 (older).
     */
    private function freshScore(Article $article, CarbonInterface $calculatedAt): int
    {
        $publishedAt = $article->published_at;
        if (!$publishedAt) {
            return 0;
        }

        $hours = Carbon::parse($publishedAt)->diffInHours($calculatedAt);

        if ($hours <= 24) {
            return 20;
        }

        if ($hours <= 72) {
            return 10;
        }

        return 0;
    }
}
