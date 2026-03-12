<?php

namespace App\Repositories;

use App\Enums\ArticleStatus;
use App\Enums\CategoryStatus;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CategoryRepository
{
    /**
     * Paginate categories ordered by creation date descending (admin listing).
     */
    public function paginateLatest(int $perPage = 15): LengthAwarePaginator
    {
        return Category::query()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a new category record with the given attributes.
     */
    public function create(array $data): Category
    {
        return Category::query()->create($data);
    }

    /**
     * Update an existing category with the given attributes and return the refreshed instance.
     */
    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->refresh();
    }

    /**
     * Delete the given category from the database.
     */
    public function delete(Category $category): void
    {
        $category->delete();
    }

    /**
     * Find an active category by its slug or throw a 404 exception.
     */
    public function findActiveBySlugOrFail(string $slug): Category
    {
        return Category::query()
            ->where('slug', $slug)
            ->where('status', CategoryStatus::ACTIVE->value)
            ->firstOrFail();
    }

    /**
     * Retrieve active categories with their published article count,
     * ordered by article count descending.
     *
     * @param  int  $limit  Maximum number of categories to return.
     */
    public function getActiveWithPublishedArticleCount(int $limit = 4): Collection
    {
        return Category::query()
            ->where('status', CategoryStatus::ACTIVE->value)
            ->withCount([
                'articles' => fn (Builder $query) => $query->where('status', ArticleStatus::PUBLISHED->value),
            ])
            ->orderByDesc('articles_count')
            ->limit($limit)
            ->get(['id', 'name', 'slug']);
    }
}
