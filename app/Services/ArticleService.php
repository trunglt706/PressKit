<?php

namespace App\Services;

use App\Enums\ArticleStatus;
use App\Jobs\PublishArticlePipelineJob;
use App\Models\Article;
use App\Models\ArticleVersion;
use App\Repositories\ArticleRepository;
use App\Services\SeoAnalyzerService;
use InvalidArgumentException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Spatie\ResponseCache\Facades\ResponseCache;

class ArticleService
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly SeoAnalyzerService $seoAnalyzerService
    ) {
    }

    public function paginateLatest(int $perPage = 10): LengthAwarePaginator
    {
        return $this->articleRepository->paginateLatest($perPage);
    }

    public function create(array $data): Article
    {
        $seoData = $this->extractSeoData($data);
        $mediaData = $this->extractMediaData($data);

        $article = $this->articleRepository->create($data);
        $this->syncSeo($article, $seoData);
        $this->syncArticleMedia($article, $mediaData);
        $this->createVersionSnapshot($article, $data['updated_by'] ?? null, $mediaData['version_files']);
        $this->clearResponseCache();

        return $article;
    }

    public function update(Article $article, array $data): Article
    {
        $seoData = $this->extractSeoData($data);
        $mediaData = $this->extractMediaData($data);
        $oldContent = $article->content;

        $article = $this->articleRepository->update($article, $data);
        $this->syncSeo($article, $seoData);
        $this->syncArticleMedia($article, $mediaData);

        $contentChanged = isset($data['content']) && $data['content'] !== $oldContent;
        if ($contentChanged || $mediaData['version_files'] !== []) {
            $this->createVersionSnapshot($article, $data['updated_by'] ?? null, $mediaData['version_files']);
        }

        $this->clearResponseCache();

        return $article;
    }

    public function updateSlug(Article $article, string $slug): Article
    {
        $normalizedSlug = Str::slug($slug);

        if ($normalizedSlug === '') {
            throw new InvalidArgumentException('Slug is invalid.');
        }

        $uniqueSlug = $this->resolveUniqueSlug($normalizedSlug, $article->id);

        $updated = $this->articleRepository->update($article, [
            'slug' => $uniqueSlug,
        ]);

        $this->clearResponseCache();

        return $updated;
    }

    public function submitForReview(Article $article): Article
    {
        if ($article->status !== ArticleStatus::DRAFT) {
            throw new InvalidArgumentException('Only draft articles can be submitted for review.');
        }

        $updated = $this->articleRepository->update($article, [
            'workflow_status' => 'review',
            'submitted_for_review_at' => now(),
        ]);

        $this->clearResponseCache();

        return $updated;
    }

    public function approve(Article $article, ?int $reviewerId): Article
    {
        if ($article->workflow_status !== 'review') {
            throw new InvalidArgumentException('Only articles in review can be approved.');
        }

        $updated = $this->articleRepository->update($article, [
            'workflow_status' => 'approved',
            'reviewed_at' => now(),
            'approved_by' => $reviewerId,
        ]);

        $this->clearResponseCache();

        return $updated;
    }

    public function publish(Article $article, ?int $publisherId): Article
    {
        if (!in_array($article->workflow_status, ['approved', 'published'], true)) {
            throw new InvalidArgumentException('Only approved articles can be published.');
        }

        $updated = $this->articleRepository->update($article, [
            'status' => ArticleStatus::PUBLISHED,
            'workflow_status' => 'published',
            'published_at' => $article->published_at ?: now(),
            'published_by' => $publisherId,
        ]);

        PublishArticlePipelineJob::dispatch($updated->id)->afterCommit();
        $this->clearResponseCache();

        return $updated;
    }

    public function delete(Article $article): void
    {
        $this->articleRepository->delete($article);
        $this->clearResponseCache();
    }

    public function restoreFromActivity(Article $article, Activity $activity): Article
    {
        $oldValues = $activity->properties['old'] ?? [];

        if (!is_array($oldValues) || $oldValues === []) {
            throw new InvalidArgumentException('Selected activity does not contain restorable data.');
        }

        $allowed = [
            'category_id',
            'title',
            'excerpt',
            'content',
            'status',
            'published_at',
        ];

        $restoredData = array_intersect_key($oldValues, array_flip($allowed));

        if ($restoredData === []) {
            throw new InvalidArgumentException('No allowed fields found to restore.');
        }

        $updated = $this->articleRepository->update($article, $restoredData);
        $this->clearResponseCache();

        return $updated;
    }

    private function resolveUniqueSlug(string $baseSlug, int $ignoreId): string
    {
        $slug = $baseSlug;
        $counter = 1;

        while (
            Article::query()
                ->where('slug', $slug)
                ->whereKeyNot($ignoreId)
                ->exists()
        ) {
            $slug = sprintf('%s-%d', $baseSlug, $counter++);
        }

        return $slug;
    }

    private function extractSeoData(array &$data): array
    {
        $seoData = [
            'title' => $data['seo_title'] ?? null,
            'description' => $data['seo_description'] ?? null,
            'keywords' => $data['seo_keywords'] ?? null,
            'og_image' => $data['seo_og_image'] ?? null,
        ];

        unset($data['seo_title'], $data['seo_description'], $data['seo_keywords'], $data['seo_og_image']);

        return $seoData;
    }

    private function syncSeo(Article $article, array $seoData): void
    {
        $articleSeo = $article->seo()->updateOrCreate([], $seoData);

        $analysis = $this->seoAnalyzerService->analyze(
            $article->fresh()->loadMissing('seo')
        );

        $articleSeo->update([
            'score' => $analysis['score'],
            'score_breakdown' => $analysis['breakdown'],
            'warnings' => $analysis['warnings'],
            'last_analyzed_at' => now(),
        ]);
    }

    private function extractMediaData(array &$data): array
    {
        $mediaData = [
            'featured_image' => $data['featured_image'] ?? null,
            'gallery' => $data['gallery'] ?? [],
            'attachments' => $data['attachments'] ?? [],
            'version_files' => $data['version_files'] ?? [],
        ];

        unset($data['featured_image'], $data['gallery'], $data['attachments'], $data['version_files']);

        return $mediaData;
    }

    private function syncArticleMedia(Article $article, array $mediaData): void
    {
        if ($mediaData['featured_image'] instanceof UploadedFile) {
            $article->clearMediaCollection('featured_image');
            $article->addMedia($mediaData['featured_image'])->toMediaCollection('featured_image');
        }

        foreach ($mediaData['gallery'] as $image) {
            if ($image instanceof UploadedFile) {
                $article->addMedia($image)->toMediaCollection('gallery');
            }
        }

        foreach ($mediaData['attachments'] as $file) {
            if ($file instanceof UploadedFile) {
                $article->addMedia($file)->toMediaCollection('attachments');
            }
        }
    }

    private function createVersionSnapshot(Article $article, mixed $updatedBy, array $versionFiles): ArticleVersion
    {
        $nextVersionNumber = (int) $article->versions()->max('version_number') + 1;

        $version = $article->versions()->create([
            'version_number' => $nextVersionNumber,
            'content' => $article->content,
            'updated_by' => is_numeric($updatedBy) ? (int) $updatedBy : null,
        ]);

        foreach ($versionFiles as $file) {
            if ($file instanceof UploadedFile) {
                $version->addMedia($file)->toMediaCollection('version_files');
            }
        }

        return $version;
    }

    private function clearResponseCache(): void
    {
        ResponseCache::clear();
    }
}
