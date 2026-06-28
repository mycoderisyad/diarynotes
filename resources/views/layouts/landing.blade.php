<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DiaryNotes')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/landing.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <nav class="app-navbar" id="landing-nav">
        <div class="navbar-container">
            <!-- Left Island (Logo) -->
            <div class="navbar-logo-island">
                <a href="{{ route('landing') }}" class="navbar-brand">
                    <img src="{{ asset('image/logo diary.svg') }}" alt="DiaryNotes" class="nav-logo">
                </a>
            </div>

            <!-- Right Island (Menu & Actions) -->
            <div class="navbar-menu-island">
                <div class="nav-links">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#public-notes" class="nav-link">Public Notes</a>
                    <a href="#about" class="nav-link">About</a>
                </div>

                <div class="navbar-actions">
                    @auth
                        <a href="{{ route('home') }}" class="nav-btn">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-btn">Login</a>
                    @endauth
                </div>
                
                <button class="nav-toggle" id="nav-toggle" aria-label="Toggle menu">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="landing-footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <img src="{{ asset('image/logo diary.svg') }}" alt="DiaryNotes" class="footer-logo">
                <p>Platform sederhana untuk menulis dan berbagi catatan.</p>
            </div>
            <div class="footer-links">
                <h4>Links</h4>
                <a href="#features">Features</a>
                <a href="#public-notes">Public Notes</a>
                <a href="#about">About</a>
            </div>
            <div class="footer-social">
                <h4>Connect</h4>
                <div class="social-icons">
                    <a href="https://instagram.com/mrraflann" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="https://youtube.com/@RaflanGT" target="_blank" rel="noopener" aria-label="YouTube">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.13C5.12 19.56 12 19.56 12 19.56s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.43z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                    </a>
                    <a href="https://github.com/risyadraf" target="_blank" rel="noopener" aria-label="GitHub">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Made by Muhammad Risyad Raflan &mdash; Mahasiswa Universitas Siber Muhammadiyah</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
