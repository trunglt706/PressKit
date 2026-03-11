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

        $baseContent = `Theo Daum, tác phẩm lấy bối cảnh sau khi vương triều Cao Câu Ly sụp đổ, xoay quanh Chilseong (Park Bo Gum), một võ sĩ mất ký ức bị đưa vào đấu trường nô lệ để giành lấy thanh kiếm huyền thoại. Trong phim, Trấn Thành vào vai In Gwi (Nhân Quý) - tổng quản phủ An Đông đô hộ của nhà Đường, nhân vật có ảnh hưởng lớn đến cục diện chính trị ở miền Bắc. Trấn Thành trong buổi đọc kịch bản phim "Kal: Thanh kiếm của Godumakhan". Ảnh: Red Ice Entertainment/Solar Partners Tại buổi họp của đoàn phim đăng tải ngày 9/3, nghệ sĩ xuất hiện với trang phục tối màu, tập trung theo dõi kịch bản và trao đổi với êkíp. Các diễn viên còn thực hiện một số động tác chiến đấu để thống nhất nhịp độ hành động.Theo Star News, vai diễn của Trấn Thành ban đầu được giao cho tài tử Cha Seung Won. Tuy nhiên, do lịch quay dự kiến tháng 8/2025 bị lùi sang năm nay, diễn viên đã rút khỏi dự án.Ngoài Trấn Thành và Park Bo Gum, tác phẩm có sự tham gia của Joo Won, Jung Jae Young và Lee Sun Bin. Joo Won hóa thân Gye Pil Hyeok, chiến binh đối đầu Chilseong, có kỹ năng sử dụng song kiếm. Jung Jae Young đảm nhận nhân vật Heuksugang - thủ lĩnh lực lượng phục hưng Cao Câu Ly, giữ vai trò trung tâm trong bối cảnh thời cuộc hỗn loạn. Lee Sun Bin đóng Maya, thành viên của lực lượng phục hưng.Dự án do đạo diễn Kim Han Min - đứng sau các tác phẩm ăn khách như Đại thủy chiến, Thủy chiến đảo Hansan: Rồng trỗi dậy và Đại hải chiến Noryang - Biển chết - thực hiện. Tác phẩm dự kiến ra mắt vào năm 2027, hướng tới phát hành tại nhiều thị trường quốc tế, trong đó có Nhật Bản và Việt Nam. &nbsp; &nbsp; Trailer 'Đại hải chiến Noryang - Biển chết' Trailer "Đại hải chiến Noryang - Biển chết" (2023), do Kim Han Min chỉ đạo. Video: Lotte Entertainment Vietnam Trấn Thành tên đầy đủ Huỳnh Trấn Thành, 39 tuổi, là diễn viên, người dẫn chương trình, nhà làm phim. Năm 2021, nghệ sĩ gây tiếng vang khi làm phim điện ảnh đầu tay Bố già, đoạt nhiều giải thưởng trong nước như Bông Sen Vàng, Cánh Diều Vàng. Năm 2023, anh phát hành Nhà bà Nữ, tác phẩm đầu tiên tự đạo diễn, đạt doanh thu cao nhất mọi thời tại phòng vé trong nước khi đó.Phim Mai của anh - ra mắt Tết Giáp Thìn 2024, dán nhãn 18+ (không dành cho khán giả dưới 18 tuổi) - từng là tác phẩm Việt ăn khách nhất phòng vé với 520 tỷ đồng, cho đến khi bị Mưa đỏ (đạo diễn Đặng Thái Huyền) phá kỷ lục. Dịp Tết Ất Tỵ 2025, Bộ tứ báo thủ thu về hơn 300 tỷ đồng, là một trong những phim Việt doanh thu cao nhất năm.Về đời tư, anh công khai yêu đương ca sĩ Hari Won vào tháng 2/2016. Cả hai kết hôn tháng 12/2016. Vợ chồng nghệ sĩ luôn sát cánh trong công việc.Cát Tiên (theo Nate, NC Press)`;
        $sharedContent = substr(str_repeat($baseContent, 12), 0, 1000);
        $descriptionContent = substr(str_repeat($baseContent, 12), 0, 200);

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
                    'content' => $sharedContent,
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
