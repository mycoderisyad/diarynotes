<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - DiaryNotes</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/auth.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="auth-page">
        <!-- Visual Background / Left Side -->
        <div class="auth-visual">
            <div class="visual-brand">
                <a href="{{ route('landing') }}" class="brand-link">
                    <img src="{{ asset('image/logo diary.svg') }}" alt="DiaryNotes Logo" class="brand-logo">
                </a>
            </div>
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
            <div class="bg-shape shape-4"></div>
        </div>

        <!-- Form / Right Side -->
        <div class="auth-content">
            <div class="blob blob-3"></div>
            <div class="blob blob-4"></div>
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
            <div class="bg-shape shape-4"></div>
            
            <div class="auth-card">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('fail'))
                    <div class="alert alert-danger">{{ session('fail') }}</div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
