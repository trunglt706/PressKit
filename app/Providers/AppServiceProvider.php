<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\ContactSubmission;
use App\Models\Tag;
use App\Observers\ArticleObserver;
use App\Observers\CategoryObserver;
use App\Observers\ContactSubmissionObserver;
use App\Observers\TagObserver;
use App\Services\ArticleFilterCacheService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Keep compatibility for older MySQL/MariaDB index length limits.
        Schema::defaultStringLength(191);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
        }

        Article::observe(ArticleObserver::class);
        Category::observe(CategoryObserver::class);
        ContactSubmission::observe(ContactSubmissionObserver::class);
        Tag::observe(TagObserver::class);

        View::composer('guest.*', function ($view): void {
            $categories = app(ArticleFilterCacheService::class)->getCategories();

            $view->with('guestNavCategories', $categories->take(5)->values());
            $view->with('guestFooterCategories', $categories->take(4)->values());
        });
    }
}
