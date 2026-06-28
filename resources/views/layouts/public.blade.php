<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DiaryNotes')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kalam:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/welcome.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="@yield('body_class')">
    @include('partials.layout.public-navbar', ['navId' => trim($__env->yieldContent('nav_id', 'public-nav'))])

    @yield('content')

    @include('partials.layout.welcome-footer')
    @stack('scripts')
</body>
</html>
