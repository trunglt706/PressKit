<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\SearchArticlesRequest;
use App\Http\Requests\Guest\ShowArticleRequest;
use App\Jobs\IncrementArticleViewJob;
use App\Services\GuestArticleService;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(
        private readonly GuestArticleService $guestArticleService,
        private readonly SeoService $seoService,
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
        $data = $this->guestArticleService->getListingData($filters, !$isSearching);

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
        $data = $this->guestArticleService->getShowData($article);

        IncrementArticleViewJob::dispatch($data['article']->id);
        $this->seoService->applyForArticle($data['article']);

        return view('guest.articles.show', $data);
    }

    /**
     * Display article listing by category slug.
     */
    public function byCategory(Request $request, string $slug): View
    {
        $filters = [
            'keyword' => (string) $request->query('keyword', ''),
            'tag' => (string) $request->query('tag', ''),
            'sort' => (string) $request->query('sort', 'latest'),
        ];

        $data = $this->guestArticleService->getCategoryListingData($slug, $filters);

        return view('guest.articles.index', $data);
    }

    /**
     * Display article listing by tag slug.
     */
    public function byTag(Request $request, string $slug): View
    {
        $filters = [
            'keyword' => (string) $request->query('keyword', ''),
            'category' => (string) $request->query('category', ''),
            'sort' => (string) $request->query('sort', 'latest'),
        ];

        $data = $this->guestArticleService->getTagListingData($slug, $filters);

        return view('guest.articles.index', $data);
    }
}
