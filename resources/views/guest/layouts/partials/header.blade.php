<header class="sticky top-0 z-50 w-full border-b border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-background-dark/95 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <div class="flex items-center gap-8">
                <a class="flex items-center gap-2 group" href="{{ route('home') }}">
                    <span class="material-symbols-outlined text-primary text-3xl">newspaper</span>
                    <h2 class="text-[32px] leading-none font-display font-extrabold tracking-tight text-slate-900 dark:text-white">{{ config('app.name') }}</h2>
                </a>

                <nav class="hidden md:flex items-center gap-6">
                    @foreach (($guestNavCategories ?? collect()) as $menuCategory)
                        @php
                            $isActiveCategory = request()->routeIs('categories.articles') && request()->route('slug') === $menuCategory->slug;
                        @endphp
                        <a
                            class="text-sm font-semibold transition-colors {{ $isActiveCategory ? 'text-primary' : 'text-slate-600 hover:text-primary' }}"
                            href="{{ route('categories.articles', ['slug' => $menuCategory->slug]) }}"
                        >
                            {{ $menuCategory->name }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <form class="hidden lg:block" method="GET" action="{{ route('articles.index') }}">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                        <input name="keyword" class="w-64 rounded-lg border border-slate-200 bg-slate-100 py-2 pl-10 pr-4 text-sm focus:border-primary focus:ring-primary" placeholder="Tìm kiếm tin tức..." type="text" />
                    </div>
                </form>

                <button aria-label="Toggle Theme" class="h-9 w-9 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors" onclick="toggleTheme()">
                    <span class="material-symbols-outlined dark:hidden">dark_mode</span>
                    <span class="material-symbols-outlined hidden dark:block">light_mode</span>
                </button>
            </div>
        </div>
    </div>
</header>