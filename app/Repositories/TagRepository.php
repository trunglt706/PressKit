<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TagRepository
{
    /**
     * Paginate tags ordered by creation date descending (admin listing).
     */
    public function paginateLatest(int $perPage = 15): LengthAwarePaginator
    {
        return Tag::query()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a new tag record with the given attributes.
     */
    public function create(array $data): Tag
    {
        return Tag::query()->create($data);
    }

    /**
     * Update an existing tag with the given attributes and return the refreshed instance.
     */
    public function update(Tag $tag, array $data): Tag
    {
        $tag->update($data);

        return $tag->refresh();
    }

    /**
     * Delete the given tag from the database.
     */
    public function delete(Tag $tag): void
    {
        $tag->delete();
    }

    /**
     * Find a tag by its slug or throw a 404 exception.
     */
    public function findBySlugOrFail(string $slug): Tag
    {
        return Tag::query()->where('slug', $slug)->firstOrFail();
    }

    /**
     * Retrieve the most-used tags ordered by article count descending.
     *
     * @param  int    $limit    Maximum number of tags to return.
     * @param  array  $columns  Columns to select.
     */
    public function getPopularByArticleCount(int $limit = 10, array $columns = ['id', 'name', 'slug']): Collection
    {
        return Tag::query()
            ->withCount('articles')
            ->orderByDesc('articles_count')
            ->limit($limit)
            ->get($columns);
    }
}
