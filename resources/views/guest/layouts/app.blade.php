<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @include('guest.layouts.partials.styles')
    @include('guest.layouts.partials.scripts')
    @yield('head')
</head>
<body class="guest-site bg-background-light dark:bg-background-dark font-sans text-slate-900 dark:text-slate-100 antialiased">
    @include('guest.layouts.partials.header')

    <main class="guest-content">
        @yield('content')
    </main>

    @include('guest.layouts.partials.footer')
</body>
</html>
