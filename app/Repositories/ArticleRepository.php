<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleRepository
{
    public function paginateLatest(int $perPage = 10): LengthAwarePaginator
    {
        return Article::query()
            ->with(['category', 'author'])
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Article
    {
        return Article::query()->create($data);
    }

    public function update(Article $article, array $data): Article
    {
        $article->update($data);

        return $article->refresh();
    }

    public function delete(Article $article): void
    {
        $article->delete();
    }
}
