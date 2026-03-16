<?php

namespace Database\Seeders;

use App\Enums\ArticleStatus;
use App\Enums\CategoryStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $defaultAuthorId = User::query()->where('email', 'test@example.com')->value('id');

        $categories = collect([
            ['name' => 'Technology', 'description' => 'Tech news and tutorials'],
            ['name' => 'Business', 'description' => 'Business and startup stories'],
            ['name' => 'Marketing', 'description' => 'Marketing insights and playbooks'],
            ['name' => 'Product', 'description' => 'Product management and growth'],
        ])->map(function (array $item): Category {
            return Category::query()->firstOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'status' => CategoryStatus::ACTIVE,
                ]
            );
        });

        $tags = collect([
            'laravel',
            'php',
            'seo',
            'redis',
            'meilisearch',
            'architecture',
            'performance',
            'devops',
        ])->map(function (string $name): Tag {
            return Tag::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => Str::title($name)]
            );
        });

        $baseContent = `<h2 id="e24842147def4872470c6975f0197fd3d" style="text-align: justify;">Trấn Th&agrave;nh đ&oacute;ng phim điện ảnh H&agrave;n</h2>`;
        $descriptionContent = 'Trấn Thành tham gia buổi đọc kịch bản "Kal: Thanh kiếm của Godumakhan", cùng dàn diễn viên nổi tiếng như Park Bo Gum và Joo Won';

        for ($i = 1; $i <= 20; $i++) {
            $title = sprintf('Trấn Thành đóng phim điện ảnh Hàn %02d', $i);
            $slug = Str::slug($title);

            /** @var Category $category */
            $category = $categories->random();

            $article = Article::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'author_id' => $defaultAuthorId,
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => $descriptionContent,
                    'content' => $baseContent,
                    'status' => ArticleStatus::PUBLISHED,
                    'published_at' => now()->subDays(21 - $i),
                ]
            );

            $article->tags()->sync(
                $tags->random(rand(2, 4))->pluck('id')->all()
            );

            $article->seo()->updateOrCreate([], [
                'title' => $title,
                'description' => $descriptionContent,
                'keywords' => 'laravel,content,seo,cache,permission',
                'og_image' => null,
            ]);
        }
    }
}
