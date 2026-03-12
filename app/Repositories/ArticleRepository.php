<?php

namespace App\Repositories;

use App\Enums\ArticleStatus;
use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ArticleRepository
{
    /**
     * Paginate articles ordered by creation date descending (admin listing).
     */
    public function paginateLatest(int $perPage = 10): LengthAwarePaginator
    {
        return Article::query()
            ->with(['category', 'author'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a new article record with the given attributes.
     */
    public function create(array $data): Article
    {
        return Article::query()->create($data);
    }

    /**
     * Update an existing article with the given attributes and return the refreshed instance.
     */
    public function update(Article $article, array $data): Article
    {
        $article->update($data);

        return $article->refresh();
    }

    /**
     * Delete the given article from the database.
     */
    public function delete(Article $article): void
    {
        $article->delete();
    }

    // ── Guest read methods ─────────────────────────────────────────────────────

    /**
     * Find a published article by its slug or throw a 404 exception.
     * Eager-loads category, SEO, media, versions, author, and tags.
     */
    public function findPublishedBySlug(string $slug): Article
    {
        return Article::query()
            ->with(['category', 'seo', 'media', 'versions.media', 'author', 'tags'])
            ->withCount('comments')
            ->where('slug', $slug)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->firstOrFail();
    }

    /**
     * Retrieve articles related to the given article by shared tags.
     * Falls back to same category when the article has no tags.
     *
     * @param  int  $limit  Maximum number of articles to return.
     */
    public function getRelatedArticles(Article $article, int $limit = 3): Collection
    {
        $tagIds = $article->tags->pluck('id')->all();

        return Article::query()
            ->with(['category', 'media'])
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->whereKeyNot($article->id)
            ->when(
                $tagIds !== [],
                fn (Builder $query) => $query->whereHas('tags', fn (Builder $tagQuery) => $tagQuery->whereIn('tags.id', $tagIds)),
                fn (Builder $query) => $query->where('category_id', $article->category_id)
            )
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Build a base Eloquent query for published articles applying keyword, tag,
     * category, and sort filters. The returned Builder can be further decorated
     * (e.g. paginate, limit) by the caller.
     *
     * Supported filter keys:
     *  - keyword  (string) Full-text search across title, excerpt, content.
     *  - tag      (string) Filter by tag slug.
     *  - category (string) Filter by category slug.
     *  - sort     (string) One of: latest | popular | featured.
     */
    public function buildPublishedFilteredQuery(array $filters): Builder
    {
        $query = Article::query()
            ->with(['category', 'tags', 'media'])
            ->withCount('comments')
            ->withSum('analytics as total_views', 'views')
            ->where('status', ArticleStatus::PUBLISHED->value);

        $query
            ->when(
                !empty($filters['keyword'] ?? null),
                fn (Builder $builder) => $builder->where(function (Builder $innerQuery) use ($filters): void {
                    $keyword = (string) $filters['keyword'];
                    $innerQuery
                        ->where('title', 'like', "%{$keyword}%")
                        ->orWhere('excerpt', 'like', "%{$keyword}%")
                        ->orWhere('content', 'like', "%{$keyword}%");
                })
            )
            ->when(
                !empty($filters['tag'] ?? null),
                fn (Builder $builder) => $builder->whereHas('tags', function (Builder $tagQuery) use ($filters): void {
                    $tagQuery->where('slug', $filters['tag']);
                })
            )
            ->when(
                !empty($filters['category'] ?? null),
                fn (Builder $builder) => $builder->whereHas('category', function (Builder $categoryQuery) use ($filters): void {
                    $categoryQuery->where('slug', $filters['category']);
                })
            );

        match ($filters['sort'] ?? 'latest') {
            'popular' => $query->orderByDesc('total_views')->latest('published_at'),
            'featured' => $query->orderByDesc('comments_count')->orderByDesc('total_views')->latest('published_at'),
            default => $query->latest('published_at'),
        };

        return $query;
    }
}
