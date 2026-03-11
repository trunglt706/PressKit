<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Http\Requests\Admin\UpdateArticleSlugRequest;
use App\Http\Requests\Admin\SubmitArticleForReviewRequest;
use App\Http\Requests\Admin\ApproveArticleRequest;
use App\Http\Requests\Admin\PublishArticleRequest;
use App\Models\Article;
use App\Services\ArticleFilterCacheService;
use App\Services\ArticleService;
use InvalidArgumentException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ArticleController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly ArticleService $articleService,
        private readonly ArticleFilterCacheService $articleFilterCacheService
    ) {
    }

    /**
     * Display a paginated list of articles for administrators.
     */
    public function index(): View
    {
        $articles = $this->articleService->paginateLatest();

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the article creation form.
     */
    public function create(): View
    {
        $categories = $this->articleFilterCacheService->getCategories();
        $article = new Article();

        return view('admin.articles.create', compact('categories', 'article'));
    }

    /**
     * Store a new article in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['updated_by'] = auth()->id();
        $data['author_id'] = auth()->id();

        $this->articleService->create($data);

        return redirect()->route('admin.articles.index')->with('status', 'Article created.');
    }

    /**
     * Display details of a specific article in the admin area.
     */
    public function show(Article $article): View
    {
        $article->loadMissing(['category', 'author', 'seo', 'approver', 'publisher']);

        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the article edit form.
     */
    public function edit(Article $article): View
    {
        $categories = $this->articleFilterCacheService->getCategories();

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update an existing article.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $data = $request->validated();

        $data['updated_by'] = auth()->id();

        $this->articleService->update($article, $data);

        return redirect()->route('admin.articles.index')->with('status', 'Article updated.');
    }

    /**
     * Remove an article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        $this->articleService->delete($article);

        return redirect()->route('admin.articles.index')->with('status', 'Article deleted.');
    }

    /**
     * Display article activity logs for comparison and auditing.
     */
    public function history(Article $article): View
    {
        $activities = Activity::query()
            ->where('subject_type', Article::class)
            ->where('subject_id', $article->id)
            ->latest()
            ->paginate(20);

        return view('admin.articles.history', compact('article', 'activities'));
    }

    /**
     * Restore article data from a selected activity log entry.
     */
    public function restore(Article $article, Activity $activity): RedirectResponse
    {
        if ($activity->subject_type !== Article::class || (int) $activity->subject_id !== (int) $article->id) {
            abort(404);
        }

        try {
            $this->articleService->restoreFromActivity($article, $activity);
        } catch (InvalidArgumentException $exception) {
            return redirect()
                ->route('admin.articles.history', $article)
                ->with('status', $exception->getMessage());
        }

        return redirect()
            ->route('admin.articles.history', $article)
            ->with('status', 'Article has been restored from selected log entry.');
    }

    /**
     * Update the article slug through a dedicated admin action.
     */
    public function updateSlug(UpdateArticleSlugRequest $request, Article $article): RedirectResponse
    {
        $data = $request->validated();

        try {
            $this->articleService->updateSlug($article, $data['slug']);
        } catch (InvalidArgumentException $exception) {
            return redirect()
                ->route('admin.articles.show', $article)
                ->with('status', $exception->getMessage());
        }

        return redirect()
            ->route('admin.articles.show', $article)
            ->with('status', 'Slug updated successfully.');
    }

    /**
     * Move a draft article to review state.
     */
    public function submitForReview(SubmitArticleForReviewRequest $request, Article $article): RedirectResponse
    {
        try {
            $this->articleService->submitForReview($article);
        } catch (InvalidArgumentException $exception) {
            return redirect()->route('admin.articles.show', $article)->with('status', $exception->getMessage());
        }

        return redirect()->route('admin.articles.show', $article)->with('status', 'Article submitted for review.');
    }

    /**
     * Approve an article from review state.
     */
    public function approve(ApproveArticleRequest $request, Article $article): RedirectResponse
    {
        try {
            $this->articleService->approve($article, auth()->id());
        } catch (InvalidArgumentException $exception) {
            return redirect()->route('admin.articles.show', $article)->with('status', $exception->getMessage());
        }

        return redirect()->route('admin.articles.show', $article)->with('status', 'Article approved.');
    }

    /**
     * Publish an approved article and dispatch publish pipeline job.
     */
    public function publish(PublishArticleRequest $request, Article $article): RedirectResponse
    {
        try {
            $this->articleService->publish($article, auth()->id());
        } catch (InvalidArgumentException $exception) {
            return redirect()->route('admin.articles.show', $article)->with('status', $exception->getMessage());
        }

        return redirect()->route('admin.articles.show', $article)->with('status', 'Article published and pipeline dispatched.');
    }
}
