<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly CommentService $commentService
    ) {
    }

    /**
     * Display comments for a specific article.
     */
    public function index(Article $article): View
    {
        $comments = $this->commentService->paginateByArticle($article);

        return view('admin.comments.index', compact('article', 'comments'));
    }

    /**
     * Show the form for creating a new comment for an article.
     */
    public function create(Article $article): View
    {
        $comment = new Comment();

        return view('admin.comments.create', compact('article', 'comment'));
    }

    /**
     * Store a newly created comment for an article.
     */
    public function store(Request $request, Article $article): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'content' => ['required', 'string'],
            'is_approved' => ['nullable', 'boolean'],
        ]);

        $data['is_approved'] = $request->boolean('is_approved');

        $this->commentService->createForArticle($article, $data);

        return redirect()->route('admin.articles.comments.index', $article)->with('status', 'Comment created.');
    }

    /**
     * Show the form for editing a comment.
     */
    public function edit(Article $article, Comment $comment): View
    {
        if ((int) $comment->article_id !== (int) $article->id) {
            abort(404);
        }

        return view('admin.comments.edit', compact('article', 'comment'));
    }

    /**
     * Update an existing comment.
     */
    public function update(Request $request, Article $article, Comment $comment): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'content' => ['required', 'string'],
            'is_approved' => ['nullable', 'boolean'],
        ]);

        $data['is_approved'] = $request->boolean('is_approved');

        try {
            $this->commentService->updateForArticle($article, $comment, $data);
        } catch (InvalidArgumentException $exception) {
            return redirect()->route('admin.articles.comments.index', $article)->with('status', $exception->getMessage());
        }

        return redirect()->route('admin.articles.comments.index', $article)->with('status', 'Comment updated.');
    }

    /**
     * Remove a comment.
     */
    public function destroy(Article $article, Comment $comment): RedirectResponse
    {
        try {
            $this->commentService->deleteForArticle($article, $comment);
        } catch (InvalidArgumentException $exception) {
            return redirect()->route('admin.articles.comments.index', $article)->with('status', $exception->getMessage());
        }

        return redirect()->route('admin.articles.comments.index', $article)->with('status', 'Comment deleted.');
    }
}
