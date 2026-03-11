<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentRepository
{
    public function paginateByArticle(Article $article, int $perPage = 20): LengthAwarePaginator
    {
        return Comment::query()
            ->where('article_id', $article->id)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Comment
    {
        return Comment::query()->create($data);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);

        return $comment->refresh();
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
