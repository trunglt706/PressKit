<?php

namespace App\Services;

use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TagService
{
    public function __construct(
        private readonly TagRepository $tagRepository
    ) {
    }

    /**
     * Paginate tags ordered by creation date descending (admin listing).
     */
    public function paginateLatest(int $perPage = 15): LengthAwarePaginator
    {
        return $this->tagRepository->paginateLatest($perPage);
    }

    /**
     * Create a new tag with a unique slug automatically derived from the name.
     */
    public function create(array $data): Tag
    {
        $name = trim((string) ($data['name'] ?? ''));
        $slug = $this->resolveUniqueSlug(Str::slug($name));

        return $this->tagRepository->create([
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    /**
     * Update the name of an existing tag.
     */
    public function update(Tag $tag, array $data): Tag
    {
        $name = trim((string) ($data['name'] ?? ''));

        return $this->tagRepository->update($tag, [
            'name' => $name,
        ]);
    }

    /**
     * Delete the given tag from the database.
     */
    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

    /**
     * Generate a unique tag slug from the base slug.
     * Falls back to "tag" when $baseSlug is empty.
     * Appends an incrementing integer suffix until the slug is unique.
     */
    private function resolveUniqueSlug(string $baseSlug): string
    {
        $slug = $baseSlug !== '' ? $baseSlug : 'tag';
        $counter = 1;

        while (Tag::query()->where('slug', $slug)->exists()) {
            $slug = sprintf('%s-%d', $baseSlug !== '' ? $baseSlug : 'tag', $counter++);
        }

        return $slug;
    }
}
