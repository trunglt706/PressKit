<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TagRepository
{
    public function paginateLatest(int $perPage = 15): LengthAwarePaginator
    {
        return Tag::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Tag
    {
        return Tag::query()->create($data);
    }

    public function update(Tag $tag, array $data): Tag
    {
        $tag->update($data);

        return $tag->refresh();
    }

    public function delete(Tag $tag): void
    {
        $tag->delete();
    }
}
