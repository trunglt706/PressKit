<?php

namespace App\Services;

use App\Models\Article;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Apply all SEO meta tags (title, description, canonical, OpenGraph, JSON-LD,
     * keywords, and OG image) for the given article page using SEOTools.
     */
    public function applyForArticle(Article $article): void
    {
        $seo = $article->seo;

        $title = $seo?->title ?: $article->title;
        $description = $seo?->description ?: ($article->excerpt ?: Str::limit(strip_tags($article->content), 160));

        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOTools::metatags()->setCanonical(route('articles.show', ['article' => $article->slug]));
        SEOTools::metatags()->setRobots('index,follow');
        SEOTools::opengraph()->setUrl(route('articles.show', ['article' => $article->slug]));
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::jsonLd()->setType('Article');
        SEOTools::jsonLd()->setTitle($title);
        SEOTools::jsonLd()->setDescription($description);
        SEOTools::jsonLd()->setUrl(route('articles.show', ['article' => $article->slug]));

        if (!empty($seo?->keywords)) {
            $keywords = array_filter(array_map('trim', explode(',', $seo->keywords)));
            SEOTools::metatags()->setKeywords($keywords);
        }

        if (!empty($seo?->og_image)) {
            SEOTools::opengraph()->addImage($seo->og_image);
            SEOTools::twitter()->setImage($seo->og_image);
            SEOTools::jsonLd()->addImage($seo->og_image);
        }
    }
}
