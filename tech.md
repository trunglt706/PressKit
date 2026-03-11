# Kiến trúc kỹ thuật
Laravel
   ↓
MySQL (data)
Redis (cache + ranking)
Meilisearch (search)
Queue + Horizon
CDN

# Danh sách model
articles
article_versions
tags
categories
comments
analytics
trending
ads

# Danh sách Feature
 ├ Article: Tạo, chỉnh sửa, xóa bài viết
 ├ Category: Tìm kiếm bài viết theo category
 ├ Tag: Tìm kiếm bài viết theo tag
 ├ SEO: Tối ưu hóa bài viết cho công cụ tìm kiếm
 ├ Analytics: Theo dõi lượt xem, tương tác của bài viết
 ├ Trending: Hiển thị các bài viết đang hot
 ├ Comment: Cho phép người dùng bình luận bài viết
 ├ Media: Quản lý hình ảnh, video cho bài viết
 ├ Ads: Quản lý quảng cáo hiển thị trên trang
 ├ AI: Tự động gợi ý nội dung, tối ưu hóa SEO
 ├ Search: Tìm kiếm bài viết theo từ khóa

 # Recommended composer packages list
 "laravel/sanctum": "^4.0",
 "spatie/laravel-permission": "^6.0",
 "spatie/laravel-activitylog": "^4.0",
 "spatie/laravel-sluggable": "^3.0",
 "spatie/laravel-medialibrary": "^11.0",
 "spatie/laravel-sitemap": "^7.0",
 "artesaos/seotools": "^1.2",
 "laravel/scout": "^10.0",
 "predis/predis": "^2.0",
 "symfony/dom-crawler": "^7.0"

 # command
 - Clear response cache: php artisan responsecache:clear