<?php

namespace App\Services;

use App\Enums\CategoryStatus;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ArticleFilterCacheService
{
    public const CATEGORIES_CACHE_KEY = 'articles:filters:categories';
    public const TAGS_CACHE_KEY = 'articles:filters:tags';

    /**
     * Get ordered category options from cache.
     */
    public function getCategories(): Collection
    {
        return Cache::rememberForever(self::CATEGORIES_CACHE_KEY, function (): Collection {
            return Category::query()
                ->where('status', CategoryStatus::ACTIVE->value)
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);
        });
    }

    /**
     * Get ordered tag options from cache.
     */
    public function getTags(): Collection
    {
        return Cache::rememberForever(self::TAGS_CACHE_KEY, function (): Collection {
            return Tag::query()
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);
        });
    }

    /**
     * Clear all article filter cache keys.
     */
    public function clear(): void
    {
        self::clearCategories();
        self::clearTags();
    }

    /**
     * Clear category cache key.
     */
    public static function clearCategories(): void
    {
        Cache::forget(self::CATEGORIES_CACHE_KEY);
    }

    /**
     * Clear tag cache key.
     */
    public static function clearTags(): void
    {
        Cache::forget(self::TAGS_CACHE_KEY);
    }
}
