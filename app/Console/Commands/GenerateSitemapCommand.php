<?php

namespace App\Console\Commands;

use App\Enums\ArticleStatus;
use App\Models\Article;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap.xml for public pages';

    public function handle(): int
    {
        $sitemap = Sitemap::create()
            ->add(
                Url::create(url('/'))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
            );

        Article::query()
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->latest('updated_at')
            ->get()
            ->each(function (Article $article) use ($sitemap): void {
                $sitemap->add(
                    Url::create(route('articles.show', ['article' => $article->slug]))
                        ->setLastModificationDate($article->updated_at ?? $article->created_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated: '.public_path('sitemap.xml'));

        return self::SUCCESS;
    }
}
