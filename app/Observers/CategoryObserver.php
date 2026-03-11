<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\ArticleFilterCacheService;

class CategoryObserver
{
    /**
     * Handle the Category "saved" event.
     */
    public function saved(Category $category): void
    {
        ArticleFilterCacheService::clearCategories();
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        ArticleFilterCacheService::clearCategories();
    }
}
