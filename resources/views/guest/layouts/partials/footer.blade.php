<footer class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div>
                <a class="flex items-center gap-2" href="{{ route('home') }}">
                    <span class="material-symbols-outlined text-primary text-3xl">newspaper</span>
                    <h3 class="text-3xl font-display font-bold">{{ config('app.name') }}</h3>
                </a>
                <p class="mt-4 text-sm text-slate-500">Cập nhật tin tức nhanh nhất, chính xác nhất về các sự kiện diễn ra tại Việt Nam và trên toàn thế giới.</p>
                <p class="mt-2 text-sm text-slate-500">© 2026 {{ config('app.name') }}</p>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Chuyên mục</h4>
                <ul class="mt-4 space-y-2 text-sm text-slate-500">
                    @foreach (($guestFooterCategories ?? collect()) as $footerCategory)
                        <li>
                            <a href="{{ route('categories.articles', ['slug' => $footerCategory->slug]) }}" class="hover:text-primary">
                                {{ $footerCategory->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Hỗ trợ</h4>
                <ul class="mt-4 space-y-2 text-sm text-slate-500">
                    <li><a href="{{ route('about') }}" class="hover:text-primary">Về chúng tôi</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary">Liên hệ tòa soạn</a></li>
                    <li><a href="{{ route('term') }}" class="hover:text-primary">Điều khoản sử dụng</a></li>
                    <li><a href="{{ route('policy') }}" class="hover:text-primary">Chính sách bảo mật</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Theo dõi</h4>
                <div class="mt-4 flex gap-3">
                    <a class="h-9 w-9 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center hover:bg-primary hover:text-white transition-colors" href="#">
                        <span class="material-symbols-outlined text-[18px]">public</span>
                    </a>
                    <a class="h-9 w-9 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center hover:bg-primary hover:text-white transition-colors" href="#">
                        <span class="material-symbols-outlined text-[18px]">share</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<button
    id="goToTopButton"
    type="button"
    aria-label="Go to top"
    class="pointer-events-none fixed bottom-6 right-6 z-50 inline-flex h-11 w-11 translate-y-3 items-center justify-center rounded-full bg-primary text-white opacity-0 shadow-lg transition-all duration-300 hover:bg-blue-700"
    onclick="scrollToTop()"
>
    <span class="material-symbols-outlined text-[20px]">keyboard_arrow_up</span>
</button>