<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentRepository
{
    /**
     * Paginate comments for the given article, newest first, with the author eager-loaded.
     */
    public function paginateByArticle(Article $article, int $perPage = 20): LengthAwarePaginator
    {
        return Comment::query()
            ->where('article_id', $article->id)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a new comment record with the given attributes.
     */
    public function create(array $data): Comment
    {
        return Comment::query()->create($data);
    }

    /**
     * Update an existing comment with the given attributes and return the refreshed instance.
     */
    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);

        return $comment->refresh();
    }

    /**
     * Delete the given comment from the database.
     */
    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
