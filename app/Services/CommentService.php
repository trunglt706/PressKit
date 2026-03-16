<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class CommentService
{
    public function __construct(
        private readonly CommentRepository $commentRepository
    ) {
    }

    /**
     * Paginate comments for the given article, newest first, with the author eager-loaded.
     */
    public function paginateByArticle(Article $article, int $perPage = 20): LengthAwarePaginator
    {
        return $this->commentRepository->paginateByArticle($article, $perPage);
    }

    /**
     * Create a comment associated with the given article.
     */
    public function createForArticle(Article $article, array $data): Comment
    {
        return $this->commentRepository->create([
            'article_id' => $article->id,
            'user_id' => $data['user_id'] ?? null,
            'author_name' => $data['author_name'] ?? null,
            'author_email' => $data['author_email'] ?? null,
            'content' => $data['content'],
            'is_approved' => (bool) ($data['is_approved'] ?? false),
        ]);
    }

    /**
     * Update a comment that belongs to the given article.
     * Throws InvalidArgumentException if the comment does not belong to the article.
     */
    public function updateForArticle(Article $article, Comment $comment, array $data): Comment
    {
        $this->ensureCommentBelongsToArticle($article, $comment);

        return $this->commentRepository->update($comment, [
            'user_id' => $data['user_id'] ?? null,
            'author_name' => $data['author_name'] ?? null,
            'author_email' => $data['author_email'] ?? null,
            'content' => $data['content'],
            'is_approved' => (bool) ($data['is_approved'] ?? false),
        ]);
    }

    /**
     * Delete a comment that belongs to the given article.
     * Throws InvalidArgumentException if the comment does not belong to the article.
     */
    public function deleteForArticle(Article $article, Comment $comment): void
    {
        $this->ensureCommentBelongsToArticle($article, $comment);
        $this->commentRepository->delete($comment);
    }

    /**
     * Assert that the comment belongs to the article; throws InvalidArgumentException otherwise.
     */
    private function ensureCommentBelongsToArticle(Article $article, Comment $comment): void
    {
        if ((int) $comment->article_id !== (int) $article->id) {
            throw new InvalidArgumentException('Comment does not belong to this article.');
        }
    }
}
