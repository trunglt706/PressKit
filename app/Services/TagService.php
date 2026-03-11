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

    public function paginateLatest(int $perPage = 15): LengthAwarePaginator
    {
        return $this->tagRepository->paginateLatest($perPage);
    }

    public function create(array $data): Tag
    {
        $name = trim((string) ($data['name'] ?? ''));
        $slug = $this->resolveUniqueSlug(Str::slug($name));

        return $this->tagRepository->create([
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    public function update(Tag $tag, array $data): Tag
    {
        $name = trim((string) ($data['name'] ?? ''));

        return $this->tagRepository->update($tag, [
            'name' => $name,
        ]);
    }

    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

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
