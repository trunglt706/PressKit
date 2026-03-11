@extends('guest.layouts.app')

@section('title', 'Tin tuc moi nhat')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        @if ($featuredArticle)
            <section class="mb-9">
                <div class="article-image-shell relative overflow-hidden rounded-xl bg-slate-900 group aspect-[21/9] border border-slate-200">
                    <img
                        src="{{ $featuredArticle->getFirstMediaUrl('featured_image') ?: asset('images/no-content.jpg') }}"
                        alt="{{ $featuredArticle->title }}"
                        loading="lazy"
                        decoding="async"
                        class="article-lazy-image absolute inset-0 h-full w-full object-cover opacity-75 group-hover:scale-105 transition-transform duration-700"
                    />
                    <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/55 to-black/20"></div>
                    <div class="absolute bottom-0 left-0 p-6 md:p-10 max-w-4xl">
                        <span class="inline-block px-3 py-1 mb-4 text-xs font-bold uppercase tracking-wider text-white bg-primary rounded">Nổi bật</span>
                        <h1 class="guest-title font-display font-extrabold text-white mb-3">{{ $featuredArticle->title }}</h1>
                        <p class="guest-lead text-slate-200 mb-5 line-clamp-2">{{ $featuredArticle->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($featuredArticle->content), 170) }}</p>
                        <a href="{{ route('articles.show', ['article' => $featuredArticle->slug]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">
                            Đọc ngay <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
            <section class="lg:col-span-8">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-5">
                    <h2 class="guest-subtitle font-display font-bold">Bài viết mới nhất</h2>
                    <a href="{{ route('articles.index') }}" class="text-primary text-sm font-semibold">Xem tất cả</a>
                </div>

                <div class="space-y-4">
                    @forelse ($articles as $article)
                        <article class="group flex flex-col gap-4 rounded-xl p-2 transition-colors md:flex-row md:items-start hover:bg-slate-50">
                            <a href="{{ route('articles.show', ['article' => $article->slug]) }}" class="article-image-shell w-full md:w-1/3 aspect-[4/3] overflow-hidden rounded-lg bg-slate-100 block border border-slate-200">
                                <img src="{{ $article->getFirstMediaUrl('featured_image') ?: asset('images/no-content.jpg') }}" alt="{{ $article->title }}" loading="lazy" decoding="async" class="article-lazy-image w-full h-full object-cover transition-all duration-500 group-hover:scale-110 group-hover:brightness-105" />
                            </a>
                            <div class="w-full md:w-2/3 pt-1">
                                <div class="flex items-center gap-2 mb-2 text-xs">
                                    @if ($article->category?->slug)
                                        <a href="{{ route('categories.articles', ['slug' => $article->category->slug]) }}" class="inline-flex items-center rounded-full border border-primary/40 px-2.5 py-0.5 text-primary font-bold uppercase tracking-wide">{{ $article->category->name }}</a>
                                    @else
                                        <span class="inline-flex items-center rounded-full border border-primary/40 px-2.5 py-0.5 text-primary font-bold uppercase tracking-wide">Tin tức</span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 text-slate-500">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        {{ $article->published_at?->diffForHumans() ?? 'Vừa xong' }}
                                    </span>
                                </div>
                                <h3 class="guest-card-title font-display font-bold mb-2 hover:text-primary">
                                    <a href="{{ route('articles.show', ['article' => $article->slug]) }}">{{ $article->title }}</a>
                                </h3>
                                <p class="guest-text text-slate-600 line-clamp-2">{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 170) }}</p>
                            </div>
                        </article>
                    @empty
                        <p class="text-slate-500">Không có bài viết mới.</p>
                    @endforelse
                </div>
                <div class="mt-5">
                    <a href="{{ route('articles.index') }}" class="block rounded-lg border border-slate-200 bg-white py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Xem thêm tin bài</a>
                </div>
            </section>

            <aside class="lg:col-span-4 space-y-6">
                <div>
                    <h2 class="guest-subtitle font-display font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">trending_up</span>
                        Xem nhiều nhất
                    </h2>
                    <div class="space-y-3">
                        @forelse ($popularArticles as $popular)
                            <div class="flex gap-4 items-start">
                                <span class="w-8 text-4xl leading-none font-display font-black text-slate-300">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <div class="pl-2">
                                    <a href="{{ route('articles.show', ['article' => $popular->slug]) }}" class="guest-meta font-semibold leading-snug hover:text-primary">{{ \Illuminate\Support\Str::limit($popular->title, 78) }}</a>
                                    <div class="mt-1 flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center rounded-full border border-primary/40 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-primary">
                                            {{ $popular->category?->name ?? 'Tin tức' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 guest-meta text-slate-500">
                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                            <span class="text-sm">{{ number_format((int) ($popular->total_views ?? 0), 0, ',', '.') }} lượt xem</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Chưa có dữ liệu xu hướng.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                    <h2 class="guest-subtitle font-display font-bold mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-primary">search</span>Tìm kiếm nhiều nhất</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($popularTags as $popularTag)
                            <a href="{{ route('tags.articles', ['slug' => $popularTag->slug]) }}" class="px-2.5 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md text-xs font-medium hover:border-primary hover:text-primary transition-all">
                                #{{ $popularTag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="bg-primary/5 dark:bg-primary/20 p-6 rounded-xl border border-primary/20">
                    <h3 class="guest-card-title font-display font-bold mb-2">Bản tin hàng ngày</h3>
                    <p class="guest-text text-slate-600 mb-4">Nhận những tin tức quan trọng nhất trực tiếp vào hộp thư của bạn mỗi sáng.</p>
                    <form class="space-y-2" method="POST" action="#">
                        <input type="email" placeholder="Email của bạn" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-primary focus:ring-primary" />
                        <button type="button" class="w-full rounded-lg bg-primary text-white py-2 text-sm font-bold hover:bg-primary/90 transition-colors">Đăng ký ngay</button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
@endsection
