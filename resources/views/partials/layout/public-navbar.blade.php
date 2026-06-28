<nav class="app-navbar" id="{{ $navId ?? 'public-nav' }}">
    <div class="navbar-container">
        <div class="navbar-logo-island">
            <a href="{{ route('landing') }}" class="navbar-brand">
                <img src="{{ asset('image/logo diary.svg') }}" alt="DiaryNotes">
            </a>
        </div>

        <div class="navbar-menu-island">
            <div class="navbar-actions">
                @auth
                    <a href="{{ route('home') }}" class="nav-btn">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
