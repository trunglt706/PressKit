<?php

namespace App\Services;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Trending;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\Collection;

class GuestArticleService
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly TagRepository $tagRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleFilterCacheService $articleFilterCacheService,
    ) {
    }

    /**
     * Fetch all data needed for the article show page.
     */
    public function getShowData(string $slug): array
    {
        $article = $this->articleRepository->findPublishedBySlug($slug);
        $relatedArticles = $this->articleRepository->getRelatedArticles($article);
        $trendingArticles = $this->getTrendingArticles($article->id);
        $trendingTags = $this->tagRepository->getPopularByArticleCount(6);

        return compact('article', 'relatedArticles', 'trendingArticles', 'trendingTags');
    }

    /**
     * Fetch all data needed for the article listing / search page.
     */
    public function getListingData(array $filters, bool $useFeaturedArticle = true): array
    {
        $normalized = $this->normalizeFilters($filters);
        $baseQuery = $this->articleRepository->buildPublishedFilteredQuery($normalized);

        $featuredArticle = null;

        if ($useFeaturedArticle) {
            $featuredArticle = (clone $baseQuery)->latest('published_at')->first();
        }

        $articles = (clone $baseQuery)
            ->when(
                $useFeaturedArticle && $featuredArticle,
                fn ($query) => $query->whereKeyNot($featuredArticle->id)
            )
            ->paginate(10)
            ->withQueryString();

        $popularArticles = $this->getPopularArticles();
        $popularTags = $this->tagRepository->getPopularByArticleCount(10);
        $categories = $this->articleFilterCacheService->getCategories();
        $tags = $this->articleFilterCacheService->getTags();
        $relatedCategories = $this->categoryRepository->getActiveWithPublishedArticleCount();

        return [
            'articles' => $articles,
            'featuredArticle' => $featuredArticle,
            'popularArticles' => $popularArticles,
            'popularTags' => $popularTags,
            'categories' => $categories,
            'relatedCategories' => $relatedCategories,
            'tags' => $tags,
            'filters' => $normalized,
            'pageTitle' => 'Tin tức mới nhất',
        ];
    }

    /**
     * Fetch listing data scoped to a category slug.
     */
    public function getCategoryListingData(string $slug, array $filters): array
    {
        $category = $this->categoryRepository->findActiveBySlugOrFail($slug);

        $data = $this->getListingData(array_merge($filters, ['category' => $slug]));
        $data['pageTitle'] = 'Danh mục: ' . $category->name;

        return $data;
    }

    /**
     * Fetch listing data scoped to a tag slug.
     */
    public function getTagListingData(string $slug, array $filters): array
    {
        $tag = $this->tagRepository->findBySlugOrFail($slug);

        $data = $this->getListingData(array_merge($filters, ['tag' => $slug]));
        $data['pageTitle'] = 'Chủ đề: ' . $tag->name;

        return $data;
    }

    /**
     * Validate and normalize filter inputs.
     */
    public function normalizeFilters(array $filters): array
    {
        return [
            'keyword' => (string) ($filters['keyword'] ?? ''),
            'tag' => (string) ($filters['tag'] ?? ''),
            'category' => (string) ($filters['category'] ?? ''),
            'sort' => in_array((string) ($filters['sort'] ?? 'latest'), ['latest', 'popular', 'featured'], true)
                ? (string) $filters['sort']
                : 'latest',
        ];
    }

    /**
     * Fetch the latest hot-score trending articles, excluding the given article ID.
     */
    private function getTrendingArticles(int $excludeArticleId): Collection
    {
        return Trending::query()
            ->with(['article' => fn ($query) => $query->with('category')])
            ->latest('calculated_at')
            ->orderBy('rank')
            ->limit(4)
            ->get()
            ->pluck('article')
            ->filter(fn ($item) => $item && $item->id !== $excludeArticleId)
            ->values();
    }

    /**
     * Fetch the most popular articles from Trending data.
     * Falls back to the five most recently published articles when no trending
     * records exist.
     */
    private function getPopularArticles(): Collection
    {
        $popular = Trending::query()
            ->with(['article' => fn ($query) => $query->with('category')])
            ->latest('calculated_at')
            ->orderBy('rank')
            ->limit(5)
            ->get()
            ->pluck('article')
            ->filter()
            ->values();

        if ($popular->isEmpty()) {
            return Article::query()
                ->with('category')
                ->where('status', ArticleStatus::PUBLISHED->value)
                ->latest('published_at')
                ->limit(5)
                ->get();
        }

        return $popular;
    }
}
