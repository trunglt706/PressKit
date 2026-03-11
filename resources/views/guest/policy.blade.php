@extends('guest.layouts.app')

@section('title', 'Chính sách bảo mật | '.config('app.name'))

@section('head')
@vite(['resources/css/policy.css', 'resources/js/policy.js'])
@endsection

@section('content')
<section class="bg-slate-100 py-12 dark:bg-slate-900/30 lg:py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <header class="text-center">
            <h1 class="guest-title font-display font-extrabold tracking-tight text-slate-900 dark:text-white text-[32px]">Chính sách bảo mật</h1>
            <p class="mt-3 inline-flex rounded-full bg-primary/10 px-4 py-1 text-sm font-semibold text-primary">Cập nhật lần cuối: 24 tháng 5, 2024</p>
        </header>

        <div class="mt-8 grid gap-7 lg:grid-cols-12">
            <aside class="lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/40 lg:sticky lg:top-24">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Mục lục</h2>
                    <nav id="policy-nav" class="mt-4 space-y-1 text-sm">
                        <a href="#collect" class="policy-toc-link is-active flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-300">
                            <span class="material-symbols-outlined text-sm">info</span>
                            Thu thập thông tin
                        </a>
                        <a href="#cookie" class="policy-toc-link flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-300">
                            <span class="material-symbols-outlined text-sm">cookies</span>
                            Sử dụng Cookie
                        </a>
                        <a href="#rights" class="policy-toc-link flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-300">
                            <span class="material-symbols-outlined text-sm">shield_person</span>
                            Quyền người dùng
                        </a>
                        <a href="#security" class="policy-toc-link flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-300">
                            <span class="material-symbols-outlined text-sm">verified_user</span>
                            Bảo mật dữ liệu
                        </a>
                        <a href="#contact" class="policy-toc-link flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-300">
                            <span class="material-symbols-outlined text-sm">alternate_email</span>
                            Liên hệ
                        </a>
                    </nav>
                </div>
            </aside>

            <article class="lg:col-span-9 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/40 lg:p-8">
                <blockquote class="border-l-4 border-primary bg-slate-50 px-4 py-3 text-slate-600 italic dark:bg-slate-800/60 dark:text-slate-300">
                    Tại {{ config('app.name') }}, chúng tôi cam kết bảo vệ quyền riêng tư và thông tin cá nhân của bạn.
                    Chính sách này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ dữ liệu của bạn khi bạn truy cập trang web của chúng tôi.
                </blockquote>

                <div class="mt-8 space-y-10 text-slate-600 dark:text-slate-300">
                    <section id="collect">
                        <h2 class="guest-subtitle font-extrabold text-slate-900 dark:text-white"><span class="text-primary">1.</span> Thu thập thông tin cá nhân</h2>
                        <p class="mt-4">Chúng tôi có thể thu thập các loại thông tin sau từ người dùng của mình:</p>
                        <ul class="mt-4 space-y-3 text-sm leading-7">
                            <li class="flex items-start gap-3"><span class="material-symbols-outlined mt-1 text-primary">check_circle</span><span><strong class="text-slate-900 dark:text-white">Thông tin định danh:</strong> Tên, địa chỉ email, và thông tin liên hệ khi bạn đăng ký nhận bản tin hoặc gửi phản hồi.</span></li>
                            <li class="flex items-start gap-3"><span class="material-symbols-outlined mt-1 text-primary">check_circle</span><span><strong class="text-slate-900 dark:text-white">Dữ liệu kỹ thuật:</strong> Địa chỉ IP, loại trình duyệt, hệ điều hành và thông tin thiết bị được thu thập tự động.</span></li>
                            <li class="flex items-start gap-3"><span class="material-symbols-outlined mt-1 text-primary">check_circle</span><span><strong class="text-slate-900 dark:text-white">Dữ liệu sử dụng:</strong> Thông tin về cách bạn tương tác với các bài viết và chuyên mục của chúng tôi.</span></li>
                        </ul>
                    </section>

                    <section id="cookie">
                        <h2 class="guest-subtitle font-extrabold text-slate-900 dark:text-white"><span class="text-primary">2.</span> Sử dụng Cookie và Công nghệ theo dõi</h2>
                        <p class="mt-4">Chúng tôi sử dụng cookie để nâng cao trải nghiệm người dùng và phân tích lưu lượng truy cập trang web:</p>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                                <h3 class="font-bold text-slate-900 dark:text-white">Cookie thiết yếu</h3>
                                <p class="mt-2 text-xs">Bắt buộc để trang web hoạt động bình thường và bảo mật tài khoản.</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                                <h3 class="font-bold text-slate-900 dark:text-white">Cookie phân tích</h3>
                                <p class="mt-2 text-xs">Giúp chúng tôi hiểu nội dung nào được người dùng quan tâm nhất.</p>
                            </div>
                        </div>
                    </section>

                    <section id="rights">
                        <h2 class="guest-subtitle font-extrabold text-slate-900 dark:text-white"><span class="text-primary">3.</span> Quyền của người dùng</h2>
                        <p class="mt-4">Bạn có các quyền sau đối với thông tin cá nhân của mình:</p>
                        <ol class="mt-4 space-y-3 text-sm leading-7">
                            <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-bold text-primary">1</span><span><strong class="text-slate-900 dark:text-white">Quyền truy cập</strong><br />Yêu cầu bản sao dữ liệu cá nhân mà chúng tôi đang lưu trữ.</span></li>
                            <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-bold text-primary">2</span><span><strong class="text-slate-900 dark:text-white">Quyền chỉnh sửa</strong><br />Yêu cầu sửa đổi bất kỳ thông tin nào không chính xác hoặc không đầy đủ.</span></li>
                            <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-bold text-primary">3</span><span><strong class="text-slate-900 dark:text-white">Quyền xóa dữ liệu</strong><br />Yêu cầu chúng tôi xóa dữ liệu cá nhân của bạn trong một số trường hợp nhất định.</span></li>
                        </ol>
                    </section>

                    <section id="security">
                        <h2 class="guest-subtitle font-extrabold text-slate-900 dark:text-white"><span class="text-primary">4.</span> Bảo mật dữ liệu</h2>
                        <p class="mt-4">Chúng tôi áp dụng các biện pháp bảo mật tiêu chuẩn ngành, bao gồm mã hóa SSL và tường lửa, để bảo vệ thông tin cá nhân của bạn khỏi bị truy cập, tiết lộ hoặc phá hủy trái phép. Tuy nhiên, không có phương thức truyền tin qua Internet nào là an toàn 100%.</p>
                    </section>

                    <section id="contact">
                        <h2 class="guest-subtitle font-extrabold text-slate-900 dark:text-white"><span class="text-primary">5.</span> Liên hệ với chúng tôi</h2>
                        <div class="mt-4 rounded-xl border border-dashed border-primary/40 bg-primary/5 p-4">
                            <p class="text-sm">Nếu bạn có bất kỳ câu hỏi nào về Chính sách bảo mật này, vui lòng liên hệ với bộ phận pháp lý của chúng tôi:</p>
                            <p class="mt-3 flex items-center gap-2 text-sm font-bold text-primary"><span class="material-symbols-outlined text-base">mail</span>support@presskit.vn</p>
                            <p class="mt-2 flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-base text-primary">location_on</span>Phường Phú Định, TP. Hồ Chí Minh, Việt Nam</p>
                        </div>
                    </section>
                </div>
            </article>
        </div>
    </div>
</section>
@endsection
