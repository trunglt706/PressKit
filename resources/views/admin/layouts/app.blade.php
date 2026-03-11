<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>
    <style>
        body { font-family: sans-serif; margin: 2rem auto; max-width: 1100px; padding: 0 1rem; }
        .status { color: #2563eb; margin-bottom: 1rem; }
        .muted { color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #ddd; text-align: left; vertical-align: top; padding: 0.75rem; }
        .actions { display: flex; gap: 0.5rem; }
        pre { margin: 0; white-space: pre-wrap; font-size: 0.85rem; }
    </style>
    @yield('head')
</head>
<body>
    @if (session('status'))
        <p class="status">{{ session('status') }}</p>
    @endif

    @yield('content')
</body>
</html>
