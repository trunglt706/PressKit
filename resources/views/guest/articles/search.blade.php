@extends('guest.layouts.app')

@section('title', "Kết quả tìm kiếm: {$searchKeyword} | ".config('app.name'))

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <section class="lg:col-span-8">
                <form method="GET" action="{{ route('articles.index') }}" class="mb-6">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                        <input
                            type="text"
                            name="keyword"
                            value="{{ $searchKeyword }}"
                            placeholder="Tìm kiếm bài viết..."
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white pl-12 pr-4 text-sm text-slate-800 shadow-sm focus:border-primary focus:ring-primary"
                        />
                    </div>
                </form>

                <div class="mb-6 border-b border-slate-200 pb-3">
                    <h1 class="guest-subtitle font-display font-extrabold text-slate-900 dark:text-white">
                        Kết quả cho: <span class="italic text-primary">'{{ $searchKeyword }}'</span>
                    </h1>
                    <div class="mt-3 flex items-center gap-5 text-sm font-semibold">
                        <span class="border-b-2 border-primary pb-2 text-primary">Tất cả</span>
                        <span class="pb-2 text-slate-400">Bài viết</span>
                        <span class="pb-2 text-slate-400">Video</span>
                        <span class="pb-2 text-slate-400">Podcast</span>
                    </div>
                </div>

                <div class="space-y-6">
                    @forelse ($articles as $article)
                        <article class="group flex flex-col gap-4 md:flex-row">
                            <a
                                href="{{ route('articles.show', ['article' => $article->slug]) }}"
                                class="article-image-shell block h-40 w-full shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-100 md:w-56"
                            >
                                <img
                                    src="{{ $article->getFirstMediaUrl('featured_image') ?: asset('images/no-content.jpg') }}"
                                    alt="{{ $article->title }}"
                                    loading="lazy"
                                    decoding="async"
                                    class="article-lazy-image h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                />
                            </a>

                            <div class="flex-1">
                                <p class="text-xs font-bold uppercase tracking-widest text-primary">
                                    {{ $article->category?->name ?? 'Tin tức' }}
                                </p>
                                <h2 class="mt-2 guest-card-title font-display font-bold text-slate-900 transition-colors group-hover:text-primary dark:text-white">
                                    <a href="{{ route('articles.show', ['article' => $article->slug]) }}">{{ $article->title }}</a>
                                </h2>
                                <p class="mt-2 line-clamp-2 text-sm italic text-slate-600 dark:text-slate-300">
                                    {{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 180) }}
                                </p>
                                <div class="mt-3 flex items-center gap-2 text-xs text-slate-400">
                                    <span class="material-symbols-outlined text-sm">calendar_today</span>
                                    <span>{{ optional($article->published_at)->format('d/m/Y') ?? 'Mới cập nhật' }}</span>
                                    <span>•</span>
                                    <span>{{ max(1, (int) ceil(str_word_count(strip_tags((string) $article->content)) / 220)) }} phút đọc</span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-600">
                            Không tìm thấy bài viết phù hợp với từ khóa <strong>{{ $searchKeyword }}</strong>.
                        </div>
                    @endforelse
                </div>

                @if ($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-10 flex items-center justify-center gap-2">
                        <a href="{{ $articles->previousPageUrl() ?: '#' }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg {{ $articles->onFirstPage() ? 'pointer-events-none text-slate-300' : 'text-slate-500 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-base">chevron_left</span>
                        </a>

                        @for ($i = 1; $i <= $articles->lastPage(); $i++)
                            @if ($i <= 3 || $i === $articles->lastPage() || abs($i - $articles->currentPage()) <= 1)
                                <a href="{{ $articles->url($i) }}" class="inline-flex h-9 w-9 items-center justify-center rounded {{ $i === $articles->currentPage() ? 'bg-primary text-white' : 'font-semibold text-slate-700 hover:bg-slate-100' }}">{{ $i }}</a>
                            @elseif ($i === 4)
                                <span class="px-1 text-slate-400">...</span>
                            @endif
                        @endfor

                        <a href="{{ $articles->nextPageUrl() ?: '#' }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg {{ $articles->hasMorePages() ? 'text-slate-500 hover:bg-slate-100' : 'pointer-events-none text-slate-300' }}">
                            <span class="material-symbols-outlined text-base">chevron_right</span>
                        </a>
                    </div>
                @endif
            </section>

            <aside class="space-y-6 lg:col-span-4">
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/40">
                    <h3 class="mb-4 flex items-center gap-2 text-xl font-display font-bold text-slate-900 dark:text-white">
                        <span class="material-symbols-outlined text-primary">trending_up</span>
                        Chủ đề thịnh hành
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (($popularTags ?? collect())->take(6) as $tag)
                            <a href="{{ route('tags.articles', ['slug' => $tag->slug]) }}" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-primary hover:text-white dark:bg-slate-800 dark:text-slate-300">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/40">
                    <h3 class="mb-4 flex items-center gap-2 text-xl font-display font-bold text-slate-900 dark:text-white">
                        <span class="material-symbols-outlined text-primary">category</span>
                        Chuyên mục liên quan
                    </h3>
                    <ul class="space-y-3">
                        @foreach (($relatedCategories ?? collect())->take(4) as $relatedCategory)
                            <li>
                                <a href="{{ route('categories.articles', ['slug' => $relatedCategory->slug]) }}" class="flex items-center justify-between text-sm text-slate-600 hover:text-primary dark:text-slate-300">
                                    <span>{{ $relatedCategory->name }}</span>
                                    <span class="rounded bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-slate-800">{{ $relatedCategory->articles_count ?? 0 }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="rounded-xl bg-primary p-5 text-white shadow-lg">
                    <h3 class="text-2xl font-display font-bold">Nhận tin mới mỗi ngày</h3>
                    <p class="mt-2 text-sm text-blue-100">Những tin tức quan trọng nhất sẽ được gửi đến email của bạn.</p>
                    <form class="mt-4 space-y-3" method="POST" action="#">
                        <input type="email" placeholder="email@example.com" class="w-full rounded-lg border border-white/20 bg-white/15 px-3 py-2 text-sm placeholder:text-blue-100 focus:border-white focus:ring-white" />
                        <button type="button" class="w-full rounded-lg bg-white py-2 text-sm font-bold text-primary hover:bg-slate-100">Đăng ký</button>
                    </form>
                </section>
            </aside>
        </div>
    </div>
@endsection
