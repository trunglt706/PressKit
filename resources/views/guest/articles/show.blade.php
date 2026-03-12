@extends('guest.layouts.app')

@section('title', $article->seo?->title ?: $article->title)

@section('head')
    {!! \Artesaos\SEOTools\Facades\SEOTools::generate() !!}
    @vite(['resources/js/article-show.js'])
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="mb-7 flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-primary">Trang chủ</a>
        <span>›</span>
        @if ($article->category?->slug)
            <a href="{{ route('categories.articles', ['slug' => $article->category->slug]) }}" class="hover:text-primary">{{ $article->category->name }}</a>
            <span>›</span>
        @endif
        <span class="text-slate-700 dark:text-slate-200">Chi tiết bài viết</span>
    </nav>

    <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
        <article class="lg:col-span-8">
            <h1 class="guest-title font-display font-extrabold text-slate-900 dark:text-white">{{ $article->title }}</h1>

            <div class="mt-6 mb-7 flex flex-wrap items-center justify-between gap-4 border-y border-slate-200 py-4 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <img src="{{ $article->author?->avatar_url ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80' }}" alt="{{ $article->author?->name ?? 'Tác giả' }}" class="h-12 w-12 rounded-full object-cover border border-slate-200" />
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $article->author?->name ?? config('app.name') }}</p>
                        <p class="text-xs text-slate-500">{{ optional($article->published_at)->format('d/m/Y') ?? optional($article->created_at)->format('d/m/Y') }} · {{ max(3, (int) str_word_count(strip_tags((string) $article->content)) / 220) }} phút đọc</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="inline-flex btn-share items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">
                        <span class="material-symbols-outlined text-base">share</span>
                        Chia sẻ
                    </button>
                    <button type="button" class="inline-flex btn-copy-link h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">
                        <span class="material-symbols-outlined text-base">bookmark</span>
                    </button>
                </div>
            </div>

            <figure class="group">
                <div class="article-image-shell rounded-xl">
                    <img
                        src="{{ $article->getFirstMediaUrl('featured_image') ?: asset('images/no-content.jpg') }}"
                        alt="{{ $article->title }}"
                        loading="lazy"
                        decoding="async"
                        class="article-lazy-image aspect-video w-full rounded-xl border border-slate-200 object-cover transition-transform duration-700 group-hover:scale-[1.02]"
                    />
                </div>
                <figcaption class="mt-3 text-center text-xs italic text-slate-500">{{ $article->excerpt ?: 'Bài viết chuyên sâu về xu hướng và tác động trong bối cảnh hiện tại.' }}</figcaption>
            </figure>

            <div class="mt-8 space-y-6 guest-text text-slate-700 dark:text-slate-200">
                {!! $article->content !!}
            </div>

            <div class="mt-10 flex flex-wrap gap-2 border-t border-slate-200 pt-6 dark:border-slate-700">
                @forelse ($article->tags as $tag)
                    <a href="{{ route('tags.articles', ['slug' => $tag->slug]) }}" class="rounded border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-slate-600 hover:text-primary dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">{{ $tag->name }}</a>
                @empty
                    <span class="rounded border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">Tin tức</span>
                @endforelse
            </div>

            <section class="mt-10 rounded-2xl border border-slate-200 bg-slate-50 p-6 dark:border-slate-700 dark:bg-slate-900/40">
                <h3 class="guest-subtitle mb-5 flex items-center gap-2 font-display font-bold text-slate-900 dark:text-white">
                    <span class="material-symbols-outlined">forum</span>
                    Bình luận ({{ $article->comments_count ?? 0 }})
                </h3>

                <div class="flex gap-3">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/15 text-primary"><span class="material-symbols-outlined text-base">person</span></span>
                    <div class="flex-1">
                        <textarea rows="3" class="w-full rounded-lg border border-slate-200 bg-white p-3 text-sm focus:border-primary focus:ring-primary dark:border-slate-700 dark:bg-slate-800" placeholder="Thêm ý kiến của bạn..."></textarea>
                        <div class="mt-2 flex justify-end">
                            <button type="button" class="rounded-lg bg-primary px-4 py-1.5 text-sm font-bold text-white hover:bg-blue-700">Gửi bình luận</button>
                        </div>
                    </div>
                </div>
            </section>
        </article>

        <aside class="lg:col-span-4 space-y-6">
            <section>
                <h3 class="guest-subtitle mb-4 border-b-2 border-primary pb-2 font-display font-bold text-slate-900 dark:text-white">Bài viết liên quan</h3>
                <div class="space-y-4">
                    @forelse ($relatedArticles as $related)
                        <a href="{{ route('articles.show', ['article' => $related->slug]) }}" class="group flex gap-3 rounded-lg p-1 hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <div class="article-image-shell h-20 w-20 overflow-hidden rounded-lg">
                                <img src="{{ $related->getFirstMediaUrl('featured_image') ?: asset('images/no-content.jpg') }}" alt="{{ $related->title }}" loading="lazy" decoding="async" class="article-lazy-image h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            </div>
                            <div>
                                <h4 class="text-sm font-bold leading-snug text-slate-800 group-hover:text-primary dark:text-slate-100">{{ \Illuminate\Support\Str::limit($related->title, 62) }}</h4>
                                <p class="mt-1 text-xs text-slate-500">{{ optional($related->published_at)->format('d/m/Y') }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Chưa có bài viết liên quan.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-slate-100 p-5 dark:border-slate-700 dark:bg-slate-800/40">
                <h3 class="guest-subtitle font-display font-bold text-slate-900 dark:text-white">Đang thịnh hành</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($trendingArticles as $trend)
                        <a href="{{ route('articles.show', ['article' => $trend->slug]) }}" class="flex items-center gap-3 text-sm hover:text-primary">
                            <span class="w-6 text-2xl font-display font-black text-slate-400">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ \Illuminate\Support\Str::limit($trend->title, 52) }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Chưa có dữ liệu xu hướng.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-xl bg-primary p-6 text-white shadow">
                <h3 class="guest-subtitle font-display font-extrabold">Nhận bản tin sáng</h3>
                <p class="mt-2 text-sm text-blue-100">Những tin tức bạn cần biết, gửi trực tiếp vào email mỗi sáng.</p>
                <form class="mt-4 space-y-3" method="POST" action="#">
                    <input type="email" placeholder="Email của bạn" class="w-full rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-sm placeholder:text-blue-100 focus:border-white focus:ring-white" />
                    <button type="button" class="w-full rounded-lg bg-white py-2 text-sm font-bold text-primary hover:bg-slate-100">Đăng ký miễn phí</button>
                </form>
            </section>

            @if (($trendingTags ?? collect())->isNotEmpty())
                <section class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-900/40">
                    <h3 class="text-xl font-bold">Từ khóa nổi bật</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($trendingTags as $tag)
                            <a href="{{ route('tags.articles', ['slug' => $tag->slug]) }}" class="rounded bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:text-primary dark:bg-slate-800 dark:text-slate-200">#{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </section>
            @endif
        </aside>
    </div>
</div>
@endsection
