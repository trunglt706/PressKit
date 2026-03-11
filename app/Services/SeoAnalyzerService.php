<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Str;

class SeoAnalyzerService
{
    /**
     * Analyze article SEO quality and return score, breakdown, and warnings.
     *
     * @return array{score:int,breakdown:array<string,int>,warnings:array<int,string>}
     */
    public function analyze(Article $article): array
    {
        $seo = $article->seo;
        $title = $seo?->title ?: $article->title;
        $metaDescription = $seo?->description ?: ($article->excerpt ?? '');
        $keywords = $this->parseKeywords($seo?->keywords);
        $primaryKeyword = $keywords[0] ?? '';
        $contentHtml = (string) $article->content;
        $contentText = trim(strip_tags($contentHtml));

        $breakdown = [
            'title_length' => $this->scoreTitleLength($title),
            'meta_description' => $this->scoreMetaDescription($metaDescription),
            'keyword_in_title' => $this->scoreKeywordInText($primaryKeyword, $title, 10),
            'keyword_in_meta' => $this->scoreKeywordInText($primaryKeyword, $metaDescription, 10),
            'keyword_density' => $this->scoreKeywordDensity($primaryKeyword, $contentText),
            'content_length' => $this->scoreContentLength($contentText),
            'heading_structure' => $this->scoreHeadingStructure($contentHtml, $primaryKeyword),
            'internal_links' => $this->scoreInternalLinks($contentHtml),
            'image_alt' => $this->scoreImageAlt($contentHtml, $primaryKeyword),
            'url_slug' => $this->scoreUrlSlug($article->slug, $primaryKeyword),
        ];

        $warnings = $this->buildWarnings($breakdown, $primaryKeyword);

        return [
            'score' => array_sum($breakdown),
            'breakdown' => $breakdown,
            'warnings' => $warnings,
        ];
    }

    private function scoreTitleLength(string $title): int
    {
        $len = Str::length(trim($title));

        if ($len >= 50 && $len <= 60) {
            return 10;
        }

        if ($len >= 40 && $len <= 70) {
            return 5;
        }

        return 0;
    }

    private function scoreMetaDescription(string $metaDescription): int
    {
        $len = Str::length(trim($metaDescription));

        if ($len >= 120 && $len <= 160) {
            return 15;
        }

        if ($len >= 90 && $len <= 180) {
            return 8;
        }

        return 0;
    }

    private function scoreKeywordInText(string $keyword, string $text, int $max): int
    {
        if ($keyword === '') {
            return 0;
        }

        return Str::contains(Str::lower($text), Str::lower($keyword)) ? $max : 0;
    }

    private function scoreKeywordDensity(string $keyword, string $contentText): int
    {
        if ($keyword === '') {
            return 0;
        }

        $words = preg_split('/\s+/u', Str::lower($contentText), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $totalWords = count($words);

        if ($totalWords === 0) {
            return 0;
        }

        $matches = preg_match_all('/\b'.preg_quote(Str::lower($keyword), '/').'\b/u', Str::lower($contentText));
        $density = ($matches / $totalWords) * 100;

        if ($density >= 1.0 && $density <= 2.0) {
            return 10;
        }

        if ($density >= 0.5 && $density <= 3.0) {
            return 5;
        }

        return 0;
    }

    private function scoreContentLength(string $contentText): int
    {
        $wordCount = str_word_count(strip_tags($contentText));

        if ($wordCount >= 800) {
            return 10;
        }

        if ($wordCount >= 500) {
            return 5;
        }

        return 0;
    }

    private function scoreHeadingStructure(string $contentHtml, string $keyword): int
    {
        $h1Count = preg_match_all('/<h1\b[^>]*>/i', $contentHtml);
        $h2Count = preg_match_all('/<h2\b[^>]*>/i', $contentHtml);
        $h3Count = preg_match_all('/<h3\b[^>]*>/i', $contentHtml);
        $hasKeywordInH1 = $keyword !== '' && (bool) preg_match('/<h1\b[^>]*>.*'.preg_quote($keyword, '/').'.*<\/h1>/iu', $contentHtml);

        if ($h1Count === 1 && $h2Count >= 2 && $h2Count <= 3 && $h3Count >= 3 && $h3Count <= 5 && $hasKeywordInH1) {
            return 10;
        }

        if ($h1Count >= 1 && $h2Count >= 1) {
            return 5;
        }

        return 0;
    }

    private function scoreInternalLinks(string $contentHtml): int
    {
        preg_match_all('/<a\b[^>]*href=["\']([^"\']+)["\'][^>]*>/i', $contentHtml, $matches);
        $links = $matches[1] ?? [];

        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $internalCount = 0;

        foreach ($links as $link) {
            if (Str::startsWith($link, ['/'])) {
                $internalCount++;
                continue;
            }

            $host = parse_url($link, PHP_URL_HOST);
            if ($host && $appHost && Str::lower($host) === Str::lower($appHost)) {
                $internalCount++;
            }
        }

        if ($internalCount >= 2) {
            return 10;
        }

        if ($internalCount === 1) {
            return 5;
        }

        return 0;
    }

    private function scoreImageAlt(string $contentHtml, string $keyword): int
    {
        preg_match_all('/<img\b[^>]*>/i', $contentHtml, $images);
        $imageTags = $images[0] ?? [];

        if ($imageTags === []) {
            return 10;
        }

        $withValidAlt = 0;

        foreach ($imageTags as $img) {
            if (preg_match('/alt=["\']([^"\']+)["\']/i', $img, $altMatch)) {
                $alt = Str::lower($altMatch[1]);
                if ($keyword === '' || Str::contains($alt, Str::lower($keyword))) {
                    $withValidAlt++;
                }
            }
        }

        if ($withValidAlt === count($imageTags)) {
            return 10;
        }

        if ($withValidAlt >= (int) ceil(count($imageTags) / 2)) {
            return 5;
        }

        return 0;
    }

    private function scoreUrlSlug(string $slug, string $keyword): int
    {
        $parts = array_values(array_filter(explode('-', trim($slug)), fn ($part) => $part !== ''));
        $stopWords = ['the', 'a', 'an', 'in', 'on', 'at', 'for', 'of', 'to'];
        $containsStopWord = collect($parts)->contains(fn ($part) => in_array(Str::lower($part), $stopWords, true));
        $containsKeyword = $keyword !== '' && Str::contains(Str::lower($slug), Str::lower(Str::slug($keyword)));

        if (count($parts) >= 3 && count($parts) <= 8 && !$containsStopWord && $containsKeyword) {
            return 5;
        }

        if (count($parts) >= 2 && count($parts) <= 10) {
            return 3;
        }

        return 0;
    }

    /**
     * @param array<string,int> $breakdown
     * @return array<int,string>
     */
    private function buildWarnings(array $breakdown, string $primaryKeyword): array
    {
        $warnings = [];

        if ($primaryKeyword === '') {
            $warnings[] = 'Primary keyword is missing. Add SEO keywords to improve scoring.';
        }

        if (($breakdown['title_length'] ?? 0) < 10) {
            $warnings[] = 'Title should be around 50-60 characters.';
        }

        if (($breakdown['meta_description'] ?? 0) < 15) {
            $warnings[] = 'Meta description should be around 120-160 characters.';
        }

        if (($breakdown['keyword_in_title'] ?? 0) < 10) {
            $warnings[] = 'Primary keyword should appear in the title.';
        }

        if (($breakdown['keyword_in_meta'] ?? 0) < 10) {
            $warnings[] = 'Primary keyword should appear in the meta description.';
        }

        if (($breakdown['keyword_density'] ?? 0) < 10) {
            $warnings[] = 'Keyword density should be around 1%-2%.';
        }

        if (($breakdown['content_length'] ?? 0) < 10) {
            $warnings[] = 'Content should be at least 800 words.';
        }

        if (($breakdown['heading_structure'] ?? 0) < 10) {
            $warnings[] = 'Use proper heading structure: H1 once, H2 two to three times, H3 three to five times.';
        }

        if (($breakdown['internal_links'] ?? 0) < 10) {
            $warnings[] = 'Add at least two internal links.';
        }

        if (($breakdown['image_alt'] ?? 0) < 10) {
            $warnings[] = 'Add alt text with the main keyword to all images.';
        }

        if (($breakdown['url_slug'] ?? 0) < 5) {
            $warnings[] = 'Slug should be concise, keyword-focused, and avoid stop words.';
        }

        return $warnings;
    }

    /**
     * @return array<int,string>
     */
    private function parseKeywords(?string $keywords): array
    {
        if (!$keywords) {
            return [];
        }

        return array_values(array_filter(array_map(static fn ($item) => trim((string) $item), explode(',', $keywords))));
    }
}
