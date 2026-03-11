<?php

namespace App\Observers;

use App\Models\Tag;
use App\Services\ArticleFilterCacheService;

class TagObserver
{
    /**
     * Handle the Tag "saved" event.
     */
    public function saved(Tag $tag): void
    {
        ArticleFilterCacheService::clearTags();
    }

    /**
     * Handle the Tag "deleting" event.
     */
    public function deleting(Tag $tag): void
    {
        $tag->articles()->detach();
    }

    /**
     * Handle the Tag "deleted" event.
     */
    public function deleted(Tag $tag): void
    {
        ArticleFilterCacheService::clearTags();
    }
}
