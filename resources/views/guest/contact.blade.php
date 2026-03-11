@extends('guest.layouts.app')

@section('title', 'Liên hệ | '.config('app.name'))

@section('head')
@vite(['resources/css/contact.css', 'resources/js/contact.js'])
@endsection

@section('content')
<section class="bg-slate-100 py-14 dark:bg-slate-900/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <h1 class="guest-title font-display font-extrabold tracking-tight text-slate-900 dark:text-white">Liên hệ tòa soạn</h1>
            <p class="guest-lead mt-4 text-slate-600 dark:text-slate-300">
                Chúng tôi luôn sẵn lòng lắng nghe ý kiến đóng góp, phản hồi và tin tức từ bạn.
                Hãy kết nối với {{ config('app.name') }} qua các kênh dưới đây.
            </p>
        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <div class="grid gap-5 sm:grid-cols-2">
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/40">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-xl">location_on</span>
                        </span>
                        <h2 class="mt-4 text-xl font-bold">Trụ sở chính</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">Phường Phú Định, TP. Hồ Chí Minh, Việt Nam</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/40">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-xl">call</span>
                        </span>
                        <h2 class="mt-4 text-xl font-bold">Số điện thoại</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">024 1234 5678 (Tổng đài)<br />024 8765 4321 (Hotline)</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/40">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-xl">mail</span>
                        </span>
                        <h2 class="mt-4 text-xl font-bold">Email Tòa soạn</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">support@presskit.vn</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/40">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-xl">web</span>
                        </span>
                        <h2 class="mt-4 text-xl font-bold">Email Quảng cáo</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">support@presskit.vn</p>
                    </article>
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/40">
                    <div class="relative h-64 sm:h-72">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31361.49371866861!2d106.59852579374524!3d10.720077523201423!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752de5a0ba39c3%3A0x11f83184e5a96249!2zUGjDuiDEkOG7i25oLCBRdeG6rW4gOCwgVGjDoG5oIHBo4buRIEjhu5MgQ2jDrSBNaW5oLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1773147930108!5m2!1svi!2s"
                            class="h-full w-full border-0"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <form id="contact-form" class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm dark:border-slate-800 dark:bg-slate-900/40" method="POST" action="{{ route('contact.submit') }}" data-submit-status="{{ session('success') ? 'success' : ($errors->any() ? 'fail' : '') }}" data-submit-message="{{ session('success') ? session('success') : ($errors->any() ? $errors->first() : '') }}">
                    @csrf
                    <h2 class="guest-subtitle font-extrabold">Gửi tin nhắn cho chúng tôi</h2>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                            Họ và tên
                            <input name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm placeholder:italic placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:border-slate-700 dark:bg-slate-800 dark:placeholder:text-slate-400/80" type="text" placeholder="Nguyễn Văn A" />
                            @error('name')
                                <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                            Email
                            <input name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm placeholder:italic placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:border-slate-700 dark:bg-slate-800 dark:placeholder:text-slate-400/80" type="email" placeholder="example@email.com" />
                            @error('email')
                                <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200 sm:col-span-2">
                            Số điện thoại
                            <input name="phone" value="{{ old('phone') }}" class="mt-2 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm placeholder:italic placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:border-slate-700 dark:bg-slate-800 dark:placeholder:text-slate-400/80" type="tel" placeholder="0901234567" />
                            @error('phone')
                                <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <label class="mt-4 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                        Tiêu đề
                        <input name="subject" value="{{ old('subject') }}" class="mt-2 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm placeholder:italic placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:border-slate-700 dark:bg-slate-800 dark:placeholder:text-slate-400/80" type="text" placeholder="Tôi muốn đóng góp tin tức" />
                        @error('subject')
                            <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="mt-4 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                        Nội dung tin nhắn
                        <textarea name="message" class="mt-2 min-h-44 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm placeholder:italic placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:border-slate-700 dark:bg-slate-800 dark:placeholder:text-slate-400/80" placeholder="Nhập nội dung bạn muốn gửi...">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <button type="submit" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-bold text-white hover:bg-blue-700">
                        Gửi tin nhắn
                        <span class="material-symbols-outlined text-base">send</span>
                    </button>

                    <p class="mt-4 text-center text-xs text-slate-500">Bằng cách nhấn gửi, bạn đồng ý với Điều khoản sử dụng và Chính sách bảo mật của chúng tôi.</p>
                </form>
            </div>
        </div>
    </div>
</section>

<div id="contact-status-modal" class="contact-modal" aria-hidden="true">
    <div class="contact-modal__card">
        <div class="contact-modal__header">
            <span id="contact-modal-icon" class="material-symbols-outlined contact-modal__icon">info</span>
            <h3 id="contact-modal-title" class="contact-modal__title">Thông báo</h3>
        </div>
        <p id="contact-modal-message" class="contact-modal__message"></p>
        <div class="contact-modal__actions">
            <span id="contact-modal-spinner" class="contact-modal__spinner hidden" aria-hidden="true"></span>
            <button id="contact-modal-confirm" type="button" class="contact-modal__button">Thoát</button>
        </div>
    </div>
</div>
@endsection
