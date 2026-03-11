@extends('guest.layouts.app')

@section('title', 'Về chúng tôi | '.config('app.name'))

@section('content')
<section class="relative min-h-[420px] overflow-hidden text-white lg:min-h-[560px]">
    <img
        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCE3nfGcUu4OmsHKYrFkPMElsZxzNW1tSICQYQtjulcY1s5jExKUokte0iqcNiGR0nd0100QzTYtW2zUg859EvOjnEkrPe3f82_HJe2mOUZp58GmikE2l_5u66mv1PnmrAROiS85ykk0-LWh6K5D3ERtSLJfOzIyduXXpTxM9orBO2W6ocdBYsfInT6fN6jfjCDxJwtiiYUMgTpDweNN_C3kwF3XHiJvgNMVQWb4NvLVnzBWVCfV09jNUts61-QfAiTx_NrenpLc9mM"
        alt="{{ config('app.name') }} building"
        class="absolute inset-0 h-full w-full object-cover"
    />
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/70 to-slate-900/25"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-24">
        <p class="inline-flex rounded-full border border-primary/40 bg-primary/20 px-4 py-1 text-xs font-semibold">Câu chuyện của chúng tôi</p>
        <h1 class="guest-title mt-4 max-w-3xl font-display font-extrabold">Về chúng tôi - {{ config('app.name') }}</h1>
        <p class="guest-lead mt-6 max-w-2xl text-slate-200">
            Cung cấp tin tức trung thực, khách quan và nhanh chóng cho độc giả toàn cầu.
            Chúng tôi cam kết mang lại những góc nhìn đa chiều về các sự kiện quan trọng nhất thế giới.
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('articles.index') }}" class="rounded-lg bg-primary px-6 py-3 text-sm font-bold text-white hover:bg-blue-700">Khám phá ngay</a>
            <a href="{{ route('contact') }}" class="rounded-lg border border-white/40 bg-white/10 px-6 py-3 text-sm font-bold text-white hover:bg-white/20">Liên hệ tòa soạn</a>
        </div>
    </div>
</section>

<section class="bg-slate-100 py-14 lg:py-20 dark:bg-slate-900/30">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-14 lg:px-8">
        <div>
            <h2 class="guest-subtitle font-display font-extrabold text-slate-900 dark:text-white">Sứ mệnh của chúng tôi</h2>
            <p class="guest-text mt-6 text-slate-600 dark:text-slate-300">
                Tại {{ config('app.name') }}, sứ mệnh của chúng tôi là mang đến những thông tin chính xác,
                kịp thời và có chiều sâu. Chúng tôi tin rằng báo chí chất lượng là nền tảng
                của một xã hội thông tin minh bạch.
            </p>
            <p class="guest-text mt-5 text-slate-600 dark:text-slate-300">
                Mỗi bài viết đều trải qua quy trình kiểm chứng nghiêm ngặt để đảm bảo tính xác thực cao nhất trước khi đến tay độc giả.
            </p>
            <ul class="mt-6 space-y-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary">verified</span>Xác thực nguồn tin đa lớp</li>
                <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary">verified</span>Phân tích khách quan, không định kiến</li>
                <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary">verified</span>Cập nhật thời gian thực 24/7</li>
            </ul>
        </div>

        <div class="grid grid-cols-2 gap-4" data-lightgallery>
            <a href="https://lh3.googleusercontent.com/aida-public/AB6AXuAE9Z8g5nd6jMWVr4NIx8LL-1jv0gzUE7D8O7jSNOMVtk84B0FRLP_WrKpqRUv0DAZQOQ9fa3FoibhPO4efZCcFMIouix_wwhUEzsqcoWA1JMRo6g3lBnPB7bqNqjTfb8fLUV0pkjvj0QqXSxASLHN-BPtmkyimzwvvzUoOVUhvS9xiJ1vehgTAhoDGfMDejHhv1pqVOLtuROlYcp7NNWsZoOjMf9Q0NsF2BdkEsVW7_QD8g2e1JfK1vNVbDZhQUK1OcUtlA7mrVfWx" class="js-lg-item block" aria-label="Mở ảnh Team">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAE9Z8g5nd6jMWVr4NIx8LL-1jv0gzUE7D8O7jSNOMVtk84B0FRLP_WrKpqRUv0DAZQOQ9fa3FoibhPO4efZCcFMIouix_wwhUEzsqcoWA1JMRo6g3lBnPB7bqNqjTfb8fLUV0pkjvj0QqXSxASLHN-BPtmkyimzwvvzUoOVUhvS9xiJ1vehgTAhoDGfMDejHhv1pqVOLtuROlYcp7NNWsZoOjMf9Q0NsF2BdkEsVW7_QD8g2e1JfK1vNVbDZhQUK1OcUtlA7mrVfWx" alt="Team" class="h-40 w-full rounded-xl object-cover shadow md:h-48" />
            </a>
            <a href="https://lh3.googleusercontent.com/aida-public/AB6AXuDbqqsMhdcwpkNnY5SUCUejq3uua4UHsdzhzzMx7aQI1qAR70F6yPc0CugYpat6o5GLbCSuHsm1CPorTaPLfYSem0oi1mHGjNsRYSulFBA3wyHvhPnf-ck9NVC446FpzSlcEdonv_oSKhifNtIAQ8FgWT5Hm-t8EDliQSFiJloR934RNmCMRVO2VFB-I7I1ZCRmH3S4m-JiuZ9Ja0YZX5cQRbztjlx1qPOkjOfSNFmpFPKtIwsK4RXWpk20VSoW62rhOJsk_zhkeTaa" class="js-lg-item block" aria-label="Mở ảnh Newspaper">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDbqqsMhdcwpkNnY5SUCUejq3uua4UHsdzhzzMx7aQI1qAR70F6yPc0CugYpat6o5GLbCSuHsm1CPorTaPLfYSem0oi1mHGjNsRYSulFBA3wyHvhPnf-ck9NVC446FpzSlcEdonv_oSKhifNtIAQ8FgWT5Hm-t8EDliQSFiJloR934RNmCMRVO2VFB-I7I1ZCRmH3S4m-JiuZ9Ja0YZX5cQRbztjlx1qPOkjOfSNFmpFPKtIwsK4RXWpk20VSoW62rhOJsk_zhkeTaa" alt="Newspaper" class="h-40 w-full rounded-xl object-cover shadow md:h-48" />
            </a>
            <a href="https://lh3.googleusercontent.com/aida-public/AB6AXuCKlWedDrxXqXbPYric1eZewtgPV9507TS_axy0L5phy4GsajmV2PCdX58hIFPKacLEq1LJrl9fTdWmukhB0hnkISIQaDEmvIe0N5h0tujhfanI0xboMpvnvjWyih09oe1gt3H1l1GJcEv7Sq5ua0EViq-t7HMSJXEl25Xatg_sYJfEcAmxarhXoTGMYsUlij6JSEnxpV14HTpw9RsR6D1VmjjXsU2mvNRil2w9lP--kWr5xljhv1T6QCUoKA2m9_vbOXnL1trVnWPz" class="js-lg-item block" aria-label="Mở ảnh Editor">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCKlWedDrxXqXbPYric1eZewtgPV9507TS_axy0L5phy4GsajmV2PCdX58hIFPKacLEq1LJrl9fTdWmukhB0hnkISIQaDEmvIe0N5h0tujhfanI0xboMpvnvjWyih09oe1gt3H1l1GJcEv7Sq5ua0EViq-t7HMSJXEl25Xatg_sYJfEcAmxarhXoTGMYsUlij6JSEnxpV14HTpw9RsR6D1VmjjXsU2mvNRil2w9lP--kWr5xljhv1T6QCUoKA2m9_vbOXnL1trVnWPz" alt="Editor" class="h-40 w-full rounded-xl object-cover shadow md:h-48" />
            </a>
            <a href="https://lh3.googleusercontent.com/aida-public/AB6AXuCkoDaCsBbvpsjPALE_QSZNT1-2R_omIODMXl2-fnbsjt3oq2RiVialtdbN-N1lCDRTokRcQqnB3QWF7PHp20AQGVe1ccoRalMgMzeQj7AV67awQ8pulbpW2vGu0UQ5TYaagKgDgBbf7aWwfRDZBxRQ-mk0i8IsS30FRS14XcmEfMHyp7LZBBtX3UySp3E8w2HeXrJZbLZm21hSNxDHhhKwut7sJy_auTvfqG2FLsP289hi2Pe4hyNyVhqtfldl3wQWuCrhRbF9gsL7" class="js-lg-item block" aria-label="Mở ảnh Analytics">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCkoDaCsBbvpsjPALE_QSZNT1-2R_omIODMXl2-fnbsjt3oq2RiVialtdbN-N1lCDRTokRcQqnB3QWF7PHp20AQGVe1ccoRalMgMzeQj7AV67awQ8pulbpW2vGu0UQ5TYaagKgDgBbf7aWwfRDZBxRQ-mk0i8IsS30FRS14XcmEfMHyp7LZBBtX3UySp3E8w2HeXrJZbLZm21hSNxDHhhKwut7sJy_auTvfqG2FLsP289hi2Pe4hyNyVhqtfldl3wQWuCrhRbF9gsL7" alt="Analytics" class="h-40 w-full rounded-xl object-cover shadow md:h-48" />
            </a>
        </div>
    </div>
</section>

<section class="bg-primary py-10">
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 text-center text-white sm:px-6 lg:grid-cols-4 lg:px-8">
        <div>
            <p class="font-display text-5xl font-black">5M+</p>
            <p class="mt-2 text-sm text-blue-100">Độc giả hàng ngày</p>
        </div>
        <div>
            <p class="font-display text-5xl font-black">15+</p>
            <p class="mt-2 text-sm text-blue-100">Năm kinh nghiệm</p>
        </div>
        <div>
            <p class="font-display text-5xl font-black">120+</p>
            <p class="mt-2 text-sm text-blue-100">Giải thưởng báo chí</p>
        </div>
        <div>
            <p class="font-display text-5xl font-black">50+</p>
            <p class="mt-2 text-sm text-blue-100">Văn phòng đại diện</p>
        </div>
    </div>
</section>

<section class="bg-slate-100 py-14 lg:py-20 dark:bg-slate-900/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-10 max-w-3xl text-center">
            <h2 class="guest-subtitle font-display font-extrabold text-slate-900 dark:text-white">Đội ngũ của chúng tôi</h2>
            <p class="guest-text mt-4 text-slate-600 dark:text-slate-300">Những người đứng sau những bản tin chất lượng, làm việc không mệt mỏi để mang đến sự thật cho độc giả.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4" data-lightgallery>
            <article>
                <a href="https://lh3.googleusercontent.com/aida-public/AB6AXuDTdqzi6Qc6cDxN0oplTvBB0nD8Hp5ZQgQFtcQlFcoG6NIbK6JN-XqwAO2Ovc2Db_7i6FKHbpXLiFVN7bX7bQoyfNtmk1usNtFxJtvgI7XbEKHB59Btkcix2b35-ufh3Wsd6XFBsX-5sva6FSK4XQSM9MWnm6gaCSVni8kxfOFoLjLtUNM9dbbqy5jLg5cgW-BGIe3roQ29AKVxW-v35OnBCBhonpWL7sER3-l-Zay-IFyW9cf5Bal5Nexqavz-zLAiKufKs_vugSUv" class="js-lg-item block" aria-label="Mở ảnh Lê Minh Tuấn">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTdqzi6Qc6cDxN0oplTvBB0nD8Hp5ZQgQFtcQlFcoG6NIbK6JN-XqwAO2Ovc2Db_7i6FKHbpXLiFVN7bX7bQoyfNtmk1usNtFxJtvgI7XbEKHB59Btkcix2b35-ufh3Wsd6XFBsX-5sva6FSK4XQSM9MWnm6gaCSVni8kxfOFoLjLtUNM9dbbqy5jLg5cgW-BGIe3roQ29AKVxW-v35OnBCBhonpWL7sER3-l-Zay-IFyW9cf5Bal5Nexqavz-zLAiKufKs_vugSUv" alt="Lê Minh Tuấn" class="aspect-[3/4] w-full rounded-xl object-cover grayscale" />
                </a>
                <h3 class="guest-card-title mt-3 font-bold">Lê Minh Tuấn</h3>
                <p class="text-primary">Tổng biên tập</p>
            </article>
            <article>
                <a href="https://lh3.googleusercontent.com/aida-public/AB6AXuCKlWedDrxXqXbPYric1eZewtgPV9507TS_axy0L5phy4GsajmV2PCdX58hIFPKacLEq1LJrl9fTdWmukhB0hnkISIQaDEmvIe0N5h0tujhfanI0xboMpvnvjWyih09oe1gt3H1l1GJcEv7Sq5ua0EViq-t7HMSJXEl25Xatg_sYJfEcAmxarhXoTGMYsUlij6JSEnxpV14HTpw9RsR6D1VmjjXsU2mvNRil2w9lP--kWr5xljhv1T6QCUoKA2m9_vbOXnL1trVnWPz" class="js-lg-item block" aria-label="Mở ảnh Nguyễn Thu Thủy">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCKlWedDrxXqXbPYric1eZewtgPV9507TS_axy0L5phy4GsajmV2PCdX58hIFPKacLEq1LJrl9fTdWmukhB0hnkISIQaDEmvIe0N5h0tujhfanI0xboMpvnvjWyih09oe1gt3H1l1GJcEv7Sq5ua0EViq-t7HMSJXEl25Xatg_sYJfEcAmxarhXoTGMYsUlij6JSEnxpV14HTpw9RsR6D1VmjjXsU2mvNRil2w9lP--kWr5xljhv1T6QCUoKA2m9_vbOXnL1trVnWPz" alt="Nguyễn Thu Thủy" class="aspect-[3/4] w-full rounded-xl object-cover grayscale" />
                </a>
                <h3 class="guest-card-title mt-3 font-bold">Nguyễn Thu Thủy</h3>
                <p class="text-primary">Phó tổng biên tập</p>
            </article>
            <article>
                <a href="https://lh3.googleusercontent.com/aida-public/AB6AXuAp4Tq5tyULNN0oF5l66RZFERtHntrZW6Qh4CUB8tEf4e7LM9x9k7wowGk56A-ESIS-ANxibF0OuvbQ8Lj3UIshyY5uI-iLFZ0UuOyTAiMwal0Bp2E4SwUV5Axh1i7lm8JKTp39sp_1S3EVhAQtHBpXaN9XKdW_oPqU_ev7400DtlGCWyacuA8SfjwdA8xmdlNubEFMi9hMdASJf0uswjAo3HnT5TQx8LgmSNqyOLpNQe6rl163jvpUOdOuqxkEryX93aZbEXcknLVt" class="js-lg-item block" aria-label="Mở ảnh Trần Quốc Hùng">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAp4Tq5tyULNN0oF5l66RZFERtHntrZW6Qh4CUB8tEf4e7LM9x9k7wowGk56A-ESIS-ANxibF0OuvbQ8Lj3UIshyY5uI-iLFZ0UuOyTAiMwal0Bp2E4SwUV5Axh1i7lm8JKTp39sp_1S3EVhAQtHBpXaN9XKdW_oPqU_ev7400DtlGCWyacuA8SfjwdA8xmdlNubEFMi9hMdASJf0uswjAo3HnT5TQx8LgmSNqyOLpNQe6rl163jvpUOdOuqxkEryX93aZbEXcknLVt" alt="Trần Quốc Hùng" class="aspect-[3/4] w-full rounded-xl object-cover grayscale" />
                </a>
                <h3 class="guest-card-title mt-3 font-bold">Trần Quốc Hùng</h3>
                <p class="text-primary">Trưởng ban Quốc tế</p>
            </article>
            <article>
                <a href="https://lh3.googleusercontent.com/aida-public/AB6AXuDIez2pxucIsmgNYmAJ9xNhSi73cu98KNqB5JOKKSvO8InNBE27UqsSKrrDCftBD-aGypGdkUKNQ_8Lyx8gWDzG8D6aq8gJvPp_ujtnifZOdHobclfIj_uIlALxqR308OH5qd1-y2xMv6tPAsLhqUBFGMTXPEuHwOHJp9fyQPHemNTEG79lSz2SBb3-IjdoaL_7eTOMzHyD0Ka3UJ8oYPenBkA4xy0o2muo_V0Bb4uSWdksyGsJxcs4kCuUgb945rSxfyGCl9Husydu" class="js-lg-item block" aria-label="Mở ảnh Phạm Minh Anh">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDIez2pxucIsmgNYmAJ9xNhSi73cu98KNqB5JOKKSvO8InNBE27UqsSKrrDCftBD-aGypGdkUKNQ_8Lyx8gWDzG8D6aq8gJvPp_ujtnifZOdHobclfIj_uIlALxqR308OH5qd1-y2xMv6tPAsLhqUBFGMTXPEuHwOHJp9fyQPHemNTEG79lSz2SBb3-IjdoaL_7eTOMzHyD0Ka3UJ8oYPenBkA4xy0o2muo_V0Bb4uSWdksyGsJxcs4kCuUgb945rSxfyGCl9Husydu" alt="Phạm Minh Anh" class="aspect-[3/4] w-full rounded-xl object-cover grayscale" />
                </a>
                <h3 class="guest-card-title mt-3 font-bold">Phạm Minh Anh</h3>
                <p class="text-primary">Trưởng ban Công nghệ</p>
            </article>
        </div>
    </div>
</section>

<section class="bg-slate-200/70 py-16 dark:bg-slate-900/50">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="guest-subtitle font-display font-extrabold text-slate-900 dark:text-white">Đăng ký nhận tin từ chúng tôi</h2>
        <p class="guest-text mx-auto mt-4 max-w-2xl text-slate-600 dark:text-slate-300">Hãy là người đầu tiên nhận được những tin tức quan trọng nhất và các phân tích chuyên sâu hàng tuần.</p>
        <form class="mx-auto mt-7 flex max-w-xl flex-col gap-3 sm:flex-row" method="POST" action="#">
            <input type="email" placeholder="Địa chỉ email của bạn" class="w-full flex-1 rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-primary focus:ring-primary" />
            <button type="button" class="rounded-lg bg-primary px-6 py-3 text-sm font-bold text-white hover:bg-blue-700">Đăng ký ngay</button>
        </form>
    </div>
</section>
@endsection
