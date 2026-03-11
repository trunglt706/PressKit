<?php

namespace App\Jobs;

use App\Enums\ArticleStatus;
use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Spatie\ResponseCache\Facades\ResponseCache;

class PublishArticlePipelineJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function __construct(
        public int $articleId
    ) {
    }

    public function handle(): void
    {
        $article = Article::query()->find($this->articleId);

        if (!$article || $article->status !== ArticleStatus::PUBLISHED) {
            return;
        }

        $staticPath = sprintf('static/articles/%s.html', $article->slug);
        $ampPath = sprintf('static/articles/%s.amp.html', $article->slug);

        Storage::disk('public')->put($staticPath, $this->renderStaticHtml($article));
        Storage::disk('public')->put($ampPath, $this->renderAmpHtml($article));

        Redis::set(
            sprintf('article:published:%d', $article->id),
            json_encode([
                'id' => $article->id,
                'slug' => $article->slug,
                'title' => $article->title,
                'excerpt' => $article->excerpt,
                'published_at' => optional($article->published_at)->toDateTimeString(),
            ], JSON_UNESCAPED_UNICODE)
        );

        $this->purgeCdn($article);

        Artisan::call('sitemap:generate');

        $sitemapUrl = url('/sitemap.xml');
        Http::timeout(5)->get('https://www.google.com/ping', ['sitemap' => $sitemapUrl]);
        Http::timeout(5)->get('https://www.bing.com/ping', ['sitemap' => $sitemapUrl]);

        $article->update([
            'static_html_path' => $staticPath,
            'amp_html_path' => $ampPath,
            'last_published_job_at' => now(),
        ]);

        ResponseCache::clear();
    }

    private function renderStaticHtml(Article $article): string
    {
        return sprintf(
            "<html><head><title>%s</title></head><body><h1>%s</h1><p>%s</p></body></html>",
            e($article->title),
            e($article->title),
            nl2br(e((string) $article->content))
        );
    }

    private function renderAmpHtml(Article $article): string
    {
        return sprintf(
            "<html amp><head><meta charset=\"utf-8\"><title>%s</title></head><body><h1>%s</h1><p>%s</p></body></html>",
            e($article->title),
            e($article->title),
            nl2br(e((string) $article->content))
        );
    }

    private function purgeCdn(Article $article): void
    {
        // Placeholder for CDN purge integration.
        Log::info('CDN purge requested for article.', [
            'article_id' => $article->id,
            'slug' => $article->slug,
        ]);
    }
}
