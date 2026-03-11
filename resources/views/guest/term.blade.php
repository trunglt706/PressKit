@extends('guest.layouts.app')

@section('title', 'Điều khoản sử dụng | '.config('app.name'))

@section('content')
<section class="bg-slate-100 py-14 dark:bg-slate-900/30 lg:py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <header class="text-center">
            <h1 class="guest-title font-display font-extrabold tracking-tight text-slate-900 dark:text-white text-[32px]">Trung tâm pháp lý</h1>
            <p class="guest-lead mx-auto mt-4 max-w-2xl text-slate-500 dark:text-slate-300">Quyền riêng tư và quyền lợi của bạn rất quan trọng với chúng tôi. Vui lòng đọc các chính sách được cập nhật dưới đây.</p>
            <p class="mt-4 inline-flex rounded-full bg-primary/10 px-4 py-1 text-sm font-semibold text-primary">Cập nhật lần cuối: 24 tháng 10, 2023</p>
        </header>

        <article class="mx-auto mt-10 max-w-3xl space-y-10 guest-text text-slate-600 dark:text-slate-300">
            <section>
                <h2 class="guest-subtitle font-display font-extrabold text-slate-900 dark:text-white">1. Chấp thuận điều khoản</h2>
                <p class="mt-4">Chào mừng bạn đến với {{ config('app.name') }}. Khi truy cập hoặc sử dụng dịch vụ của chúng tôi, bạn đồng ý tuân thủ các Điều khoản sử dụng này. Nếu bạn không đồng ý với toàn bộ điều khoản và điều kiện được nêu tại đây, bạn không được phép sử dụng trang web hoặc dịch vụ.</p>
            </section>

            <section>
                <h2 class="guest-subtitle font-display font-extrabold text-slate-900 dark:text-white">2. Giấy phép sử dụng</h2>
                <p class="mt-4">Bạn được phép tải tạm thời một bản sao tài liệu (thông tin hoặc phần mềm) trên website {{ config('app.name') }} chỉ để xem cá nhân, phi thương mại và trong thời gian ngắn.</p>
                <ol class="mt-5 space-y-3 guest-text">
                    <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-bold text-primary">1</span><span>Chỉnh sửa hoặc sao chép tài liệu cho mục đích thương mại.</span></li>
                    <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-bold text-primary">2</span><span>Sử dụng tài liệu cho bất kỳ hình thức trình chiếu công khai nào (thương mại hoặc phi thương mại).</span></li>
                    <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-bold text-primary">3</span><span>Cố gắng dịch ngược, giải mã hoặc can thiệp vào bất kỳ phần mềm nào có trên website.</span></li>
                </ol>
            </section>

            <section>
                <h2 class="guest-subtitle font-display font-extrabold text-slate-900 dark:text-white">3. Độ chính xác nội dung</h2>
                <p class="mt-4">Các tài liệu xuất hiện trên website {{ config('app.name') }} có thể bao gồm lỗi kỹ thuật, lỗi đánh máy hoặc lỗi hình ảnh. {{ config('app.name') }} không đảm bảo rằng mọi nội dung trên website luôn chính xác, đầy đủ hoặc cập nhật tại mọi thời điểm. Chúng tôi có thể thay đổi nội dung trên website bất cứ lúc nào mà không cần thông báo trước.</p>
            </section>

            <section>
                <h2 class="guest-subtitle font-display font-extrabold text-slate-900 dark:text-white">4. Liên kết ngoài</h2>
                <p class="mt-4">{{ config('app.name') }} không xem xét toàn bộ các trang web được liên kết từ website của mình và không chịu trách nhiệm về nội dung của các trang đó. Việc xuất hiện liên kết không đồng nghĩa {{ config('app.name') }} xác nhận hoặc bảo trợ cho website liên kết. Người dùng tự chịu rủi ro khi truy cập các website bên ngoài.</p>
            </section>
        </article>

        <div class="mx-auto mt-10 max-w-3xl rounded-2xl border border-slate-200 bg-slate-200/60 p-7 dark:border-slate-700 dark:bg-slate-800/50">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Bạn có thắc mắc về điều khoản?</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">Nếu bạn có bất kỳ câu hỏi hoặc quan ngại nào liên quan đến tài liệu pháp lý của chúng tôi, vui lòng liên hệ bộ phận pháp chế.</p>
            <a href="mailto:support@presskit.vn" class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline">
                <span class="material-symbols-outlined text-base">mail</span>
                support@presskit.vn
            </a>
        </div>
    </div>
</section>
@endsection
