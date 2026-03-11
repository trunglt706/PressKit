<?php

namespace App\Observers;

use App\Jobs\RegenerateSitemapJob;
use App\Models\Article;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Spatie\ResponseCache\Facades\ResponseCache;

class ArticleObserver
{
    /**
     * Handle the Article "saved" event.
     */
    public function saved(Article $article): void
    {
        RegenerateSitemapJob::dispatch()->afterCommit();
    }

    /**
     * Handle the Article "deleting" event.
     */
    public function deleting(Article $article): void
    {
        $article->tags()->detach();

        $disk = Storage::disk('public');

        foreach ([$article->static_html_path, $article->amp_html_path] as $path) {
            if (is_string($path) && $path !== '' && $disk->exists($path)) {
                $disk->delete($path);
            }
        }

        Redis::del(sprintf('article:published:%d', $article->id));
        Redis::del(sprintf('hot:article:%d:views', $article->id));
        Redis::zrem('hot:ranking:articles', (string) $article->id);
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        RegenerateSitemapJob::dispatch()->afterCommit();
        ResponseCache::clear();
    }
}
