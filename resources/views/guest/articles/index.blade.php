@extends('guest.layouts.app')

@section('title', $pageTitle ?? 'Tin tức mới nhất')

@section('content')
@php
    $heading = str_replace(['Danh muc: ', 'Chu de: '], '', $pageTitle ?? 'Tin tức mới nhất');
    $currentSort = $filters['sort'] ?? 'latest';
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <section class="mb-8 border-b border-slate-200 pb-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="guest-title font-display font-extrabold text-slate-900 dark:text-white">{{ $heading }}</h1>
                <p class="guest-lead mt-3 max-w-3xl text-slate-600 dark:text-slate-300">Cập nhật tin tức cuộc tế mới nhất, phân tích sâu sắc về chính trị, kinh tế và những biến động toàn cầu.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ url()->current() }}" class="inline-flex items-center gap-1 rounded-lg px-4 py-2 text-sm font-semibold {{ $currentSort === 'latest' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200' }}">
                    <span class="material-symbols-outlined text-sm">schedule</span>
                    Mới nhất
                </a>
                <a href="{{ url()->current() }}?{{ http_build_query(['sort' => 'popular']) }}" class="inline-flex items-center gap-1 rounded-lg px-4 py-2 text-sm font-semibold {{ $currentSort === 'popular' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200' }}">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    Phổ biến
                </a>
                <a href="{{ url()->current() }}?{{ http_build_query(['sort' => 'featured']) }}" class="inline-flex items-center gap-1 rounded-lg px-4 py-2 text-sm font-semibold {{ $currentSort === 'featured' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200' }}">
                    <span class="material-symbols-outlined text-sm">star</span>
                    Nổi bật
                </a>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <section class="lg:col-span-8">
            @if ($featuredArticle)
                <article class="group">
                    <a href="{{ route('articles.show', ['article' => $featuredArticle->slug]) }}" class="article-image-shell relative block aspect-[21/9] overflow-hidden rounded-xl border border-slate-200 bg-slate-900">
                        <img src="{{ $featuredArticle->getFirstMediaUrl('featured_image') ?: asset('images/no-content.jpg') }}" alt="{{ $featuredArticle->title }}" loading="lazy" decoding="async" class="article-lazy-image h-full w-full object-cover opacity-70 transition-transform duration-700 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent"></div>
                        <span class="absolute left-4 top-4 inline-block rounded-full bg-primary px-3 py-1 text-xs font-bold uppercase tracking-wider text-white">Tiêu điểm</span>
                    </a>

                    <h2 class="guest-subtitle mt-4 font-display font-extrabold text-slate-900 dark:text-white">
                        <a href="{{ route('articles.show', ['article' => $featuredArticle->slug]) }}" class="hover:text-primary">{{ $featuredArticle->title }}</a>
                    </h2>
                    <p class="guest-lead mt-3 text-slate-600 dark:text-slate-300">{{ $featuredArticle->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($featuredArticle->content), 190) }}</p>
                    <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-slate-500">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $featuredArticle->author?->name ?? config('app.name') }}</span>
                        <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-base">calendar_today</span>{{ optional($featuredArticle->published_at)->format('d/m/Y') ?? 'Mới cập nhật' }}</span>
                        <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-base">chat_bubble</span>{{ $featuredArticle->comments_count ?? 24 }}</span>
                    </div>
                </article>
            @endif

            <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                @forelse ($articles as $article)
                    <article class="group">
                        <a href="{{ route('articles.show', ['article' => $article->slug]) }}" class="article-image-shell block aspect-video overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                            <img src="{{ $article->getFirstMediaUrl('featured_image') ?: asset('images/no-content.jpg') }}" alt="{{ $article->title }}" loading="lazy" decoding="async" class="article-lazy-image h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        </a>
                        <h3 class="guest-card-title mt-4 font-display font-bold text-slate-900 dark:text-white">
                            <a href="{{ route('articles.show', ['article' => $article->slug]) }}" class="hover:text-primary">{{ $article->title }}</a>
                        </h3>
                        <p class="mt-2 text-base leading-7 text-slate-600 dark:text-slate-300">{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 140) }}</p>
                        <p class="mt-3 text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $article->author?->name ?? config('app.name') }} · {{ optional($article->published_at)->format('d/m/Y') }}</p>
                    </article>
                @empty
                    <p class="text-slate-500">Không có bài viết phù hợp.</p>
                @endforelse
            </div>

            @if ($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-10 flex items-center justify-center gap-2">
                    <a href="{{ $articles->previousPageUrl() ?: '#' }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 {{ $articles->onFirstPage() ? 'pointer-events-none opacity-40' : 'hover:bg-slate-100' }}">
                        <span class="material-symbols-outlined text-base">chevron_left</span>
                    </a>

                    @for ($i = 1; $i <= $articles->lastPage(); $i++)
                        @if ($i <= 3 || $i === $articles->lastPage() || abs($i - $articles->currentPage()) <= 1)
                            <a href="{{ $articles->url($i) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border {{ $i === $articles->currentPage() ? 'border-primary bg-primary text-white' : 'border-slate-200 text-slate-700 hover:bg-slate-100' }}">{{ $i }}</a>
                        @elseif ($i === 4)
                            <span class="px-1 text-slate-400">...</span>
                        @endif
                    @endfor

                    <a href="{{ $articles->nextPageUrl() ?: '#' }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 {{ $articles->hasMorePages() ? 'hover:bg-slate-100' : 'pointer-events-none opacity-40' }}">
                        <span class="material-symbols-outlined text-base">chevron_right</span>
                    </a>
                </div>
            @endif
        </section>

        <aside class="lg:col-span-4 space-y-8">
            <section>
                <h3 class="guest-subtitle mb-4 font-display font-bold">Chủ đề phổ biến</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($popularTags as $popularTag)
                        <a href="{{ route('tags.articles', ['slug' => $popularTag->slug]) }}" class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-primary/10 hover:text-primary dark:bg-slate-800 dark:text-slate-200">#{{ $popularTag->name }}</a>
                    @endforeach
                </div>
            </section>

            <section>
                <h3 class="guest-subtitle mb-4 font-display font-bold">Đang thịnh hành</h3>
                <div class="space-y-4">
                    @forelse ($popularArticles as $popular)
                        <div class="flex gap-4">
                            <span class="w-8 text-4xl font-display font-black leading-none text-slate-300">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="pl-2">
                                <a href="{{ route('articles.show', ['article' => $popular->slug]) }}" class="text-sm font-semibold leading-snug text-slate-800 hover:text-primary dark:text-slate-100">{{ \Illuminate\Support\Str::limit($popular->title, 70) }}</a>
                                <p class="mt-1">
                                    <span class="inline-flex items-center rounded-full border border-primary/40 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-primary">{{ $popular->category?->name ?? 'Tin tức' }}</span>
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Chưa có dữ liệu xu hướng.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-xl border border-primary/20 bg-primary/5 p-6 dark:bg-primary/10">
                <h3 class="guest-subtitle font-display font-bold text-primary">Nhận tin hằng ngày</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Tổng hợp những tin tức quan trọng nhất gửi trực tiếp đến email của bạn mỗi sáng.</p>
                <form class="mt-4 space-y-3" method="POST" action="#">
                    <input type="email" placeholder="Email của bạn" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-primary focus:ring-primary" />
                    <button type="button" class="w-full rounded-lg bg-primary py-2 text-sm font-bold text-white hover:bg-blue-700">Tham gia ngay</button>
                </form>
            </section>
        </aside>
    </div>
</div>
@endsection
