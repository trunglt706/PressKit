<?php

namespace App\Http\Controllers\Guest;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Tag;
use App\Models\Trending;
use App\Services\ArticleFilterCacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ArticleFilterCacheService $articleFilterCacheService
    ) {
    }

    /**
     * Display the guest home page.
     */
    public function index(Request $request): View
    {
        $filters = [
            'keyword' => (string) $request->query('keyword', ''),
            'tag' => (string) $request->query('tag', ''),
            'category' => (string) $request->query('category', ''),
        ];

        $baseQuery = Article::query()
            ->with(['category', 'tags', 'media'])
            ->where('status', ArticleStatus::PUBLISHED->value);

        $this->applySearchFilters($baseQuery, $filters);

        $featuredArticle = (clone $baseQuery)
            ->latest('published_at')
            ->first();

        $articles = (clone $baseQuery)
            ->when($featuredArticle, fn (Builder $query) => $query->whereKeyNot($featuredArticle->id))
            ->latest('published_at')
            ->limit(5)
            ->get();

        $popularArticles = Trending::query()
            ->with([
                'article' => fn ($query) => $query
                    ->with('category')
                    ->withSum('analytics as total_views', 'views'),
            ])
            ->latest('calculated_at')
            ->orderBy('rank')
            ->limit(5)
            ->get()
            ->pluck('article')
            ->filter()
            ->values();

        if ($popularArticles->isEmpty()) {
            $popularArticles = Article::query()
                ->with('category')
                ->withSum('analytics as total_views', 'views')
                ->where('status', ArticleStatus::PUBLISHED->value)
                ->latest('published_at')
                ->limit(5)
                ->get();
        }

        $popularTags = Tag::query()
            ->withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(10)
            ->get(['id', 'name', 'slug']);

        $categories = $this->articleFilterCacheService->getCategories();
        $tags = $this->articleFilterCacheService->getTags();

        return view('guest.home.index', [
            'articles' => $articles,
            'featuredArticle' => $featuredArticle,
            'popularArticles' => $popularArticles,
            'popularTags' => $popularTags,
            'categories' => $categories,
            'tags' => $tags,
            'filters' => $filters,
        ]);
    }

    private function applySearchFilters(Builder $query, array $filters): void
    {
        $query
            ->when(
                $filters['keyword'] !== '',
                fn (Builder $builder) => $builder->where(function (Builder $innerQuery) use ($filters): void {
                    $keyword = $filters['keyword'];
                    $innerQuery
                        ->where('title', 'like', "%{$keyword}%")
                        ->orWhere('excerpt', 'like', "%{$keyword}%")
                        ->orWhere('content', 'like', "%{$keyword}%");
                })
            )
            ->when(
                $filters['tag'] !== '',
                fn (Builder $builder) => $builder->whereHas('tags', function (Builder $tagQuery) use ($filters): void {
                    $tagQuery->where('slug', $filters['tag']);
                })
            )
            ->when(
                $filters['category'] !== '',
                fn (Builder $builder) => $builder->whereHas('category', function (Builder $categoryQuery) use ($filters): void {
                    $categoryQuery->where('slug', $filters['category']);
                })
            );
    }
}
