<?php

namespace App\Http\Controllers\Guest;

use App\Enums\ArticleStatus;
use App\Enums\CategoryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\SearchArticlesRequest;
use App\Http\Requests\Guest\ShowArticleRequest;
use App\Jobs\IncrementArticleViewJob;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Trending;
use App\Services\ArticleFilterCacheService;
use App\Services\SeoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Create a new guest article controller instance.
     */
    public function __construct(
        private readonly SeoService $seoService,
        private readonly ArticleFilterCacheService $articleFilterCacheService
    ) {
    }

    /**
     * Display article listing page and keyword search results.
     */
    public function index(SearchArticlesRequest $request): View
    {
        $filters = [
            'keyword' => (string) $request->validated('keyword', ''),
            'tag' => (string) $request->validated('tag', ''),
            'category' => (string) $request->validated('category', ''),
            'sort' => (string) $request->validated('sort', 'latest'),
        ];

        $isSearching = $filters['keyword'] !== '';
        $data = $this->buildListingData($filters, !$isSearching);

        if ($isSearching) {
            $data['searchKeyword'] = $filters['keyword'];

            return view('guest.articles.search', $data);
        }

        return view('guest.articles.index', $data);
    }

    /**
     * Display published article detail by slug from SEO-friendly .html URL.
     */
    public function show(ShowArticleRequest $request, string $article): View
    {
        $article = Article::query()
            ->with(['category', 'seo', 'media', 'versions.media', 'author', 'tags'])
            ->withCount('comments')
            ->where('slug', $article)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->firstOrFail();

        $tagIds = $article->tags->pluck('id')->all();

        $relatedArticles = Article::query()
            ->with(['category', 'media'])
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->whereKeyNot($article->id)
            ->when(
                $tagIds !== [],
                fn (Builder $query) => $query->whereHas('tags', fn (Builder $tagQuery) => $tagQuery->whereIn('tags.id', $tagIds)),
                fn (Builder $query) => $query->where('category_id', $article->category_id)
            )
            ->latest('published_at')
            ->limit(3)
            ->get();

        $trendingArticles = Trending::query()
            ->with(['article' => fn ($query) => $query->with('category')])
            ->latest('calculated_at')
            ->orderBy('rank')
            ->limit(4)
            ->get()
            ->pluck('article')
            ->filter(fn ($item) => $item && $item->id !== $article->id)
            ->values();

        $trendingTags = Tag::query()
            ->withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(6)
            ->get(['id', 'name', 'slug']);

        IncrementArticleViewJob::dispatch($article->id);
        $this->seoService->applyForArticle($article);

        return view('guest.articles.show', compact('article', 'relatedArticles', 'trendingArticles', 'trendingTags'));
    }

    /**
     * Display article listing by category slug.
     */
    public function byCategory(Request $request, string $slug): View
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('status', CategoryStatus::ACTIVE->value)
            ->firstOrFail();

        $filters = [
            'keyword' => (string) $request->query('keyword', ''),
            'tag' => (string) $request->query('tag', ''),
            'category' => $slug,
            'sort' => (string) $request->query('sort', 'latest'),
        ];

        $data = $this->buildListingData($filters);
        $data['pageTitle'] = 'Danh mục: '.$category->name;

        return view('guest.articles.index', $data);
    }

    /**
     * Display article listing by tag slug.
     */
    public function byTag(Request $request, string $slug): View
    {
        $tag = Tag::query()->where('slug', $slug)->firstOrFail();

        $filters = [
            'keyword' => (string) $request->query('keyword', ''),
            'tag' => $slug,
            'category' => (string) $request->query('category', ''),
            'sort' => (string) $request->query('sort', 'latest'),
        ];

        $data = $this->buildListingData($filters);
        $data['pageTitle'] = 'Chủ đề: '.$tag->name;

        return view('guest.articles.index', $data);
    }

    private function buildListingData(array $filters, bool $useFeaturedArticle = true): array
    {
        $normalizedFilters = [
            'keyword' => (string) ($filters['keyword'] ?? ''),
            'tag' => (string) ($filters['tag'] ?? ''),
            'category' => (string) ($filters['category'] ?? ''),
            'sort' => in_array((string) ($filters['sort'] ?? 'latest'), ['latest', 'popular', 'featured'], true)
                ? (string) $filters['sort']
                : 'latest',
        ];

        $baseQuery = Article::query()
            ->with(['category', 'tags', 'media'])
            ->withCount('comments')
            ->withSum('analytics as total_views', 'views')
            ->where('status', ArticleStatus::PUBLISHED->value);

        $this->applySearchFilters($baseQuery, $normalizedFilters);
        $this->applySort($baseQuery, $normalizedFilters['sort']);

        $featuredArticle = null;

        if ($useFeaturedArticle) {
            $featuredArticle = (clone $baseQuery)
                ->latest('published_at')
                ->first();
        }

        $articles = (clone $baseQuery)
            ->when($useFeaturedArticle && $featuredArticle, fn (Builder $query) => $query->whereKeyNot($featuredArticle->id))
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        $popularArticles = Trending::query()
            ->with(['article' => fn ($query) => $query->with('category')])
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
        $relatedCategories = Category::query()
            ->where('status', CategoryStatus::ACTIVE->value)
            ->withCount([
                'articles' => fn (Builder $query) => $query->where('status', ArticleStatus::PUBLISHED->value),
            ])
            ->orderByDesc('articles_count')
            ->limit(4)
            ->get(['id', 'name', 'slug']);

        return [
            'articles' => $articles,
            'featuredArticle' => $featuredArticle,
            'popularArticles' => $popularArticles,
            'popularTags' => $popularTags,
            'categories' => $categories,
            'relatedCategories' => $relatedCategories,
            'tags' => $tags,
            'filters' => $normalizedFilters,
            'pageTitle' => 'Tin tức mới nhất',
        ];
    }

    private function applySearchFilters(Builder $query, array $filters): void
    {
        $query
            ->when(
                !empty($filters['keyword'] ?? null),
                fn (Builder $builder) => $builder->where(function (Builder $innerQuery) use ($filters): void {
                    $keyword = (string) $filters['keyword'];
                    $innerQuery
                        ->where('title', 'like', "%{$keyword}%")
                        ->orWhere('excerpt', 'like', "%{$keyword}%")
                        ->orWhere('content', 'like', "%{$keyword}%");
                })
            )
            ->when(
                !empty($filters['tag'] ?? null),
                fn (Builder $builder) => $builder->whereHas('tags', function (Builder $tagQuery) use ($filters): void {
                    $tagQuery->where('slug', $filters['tag']);
                })
            )
            ->when(
                !empty($filters['category'] ?? null),
                fn (Builder $builder) => $builder->whereHas('category', function (Builder $categoryQuery) use ($filters): void {
                    $categoryQuery->where('slug', $filters['category']);
                })
            );
    }

    private function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'popular' => $query
                ->orderByDesc('total_views')
                ->latest('published_at'),
            'featured' => $query
                ->orderByDesc('comments_count')
                ->orderByDesc('total_views')
                ->latest('published_at'),
            default => $query->latest('published_at'),
        };
    }

}
