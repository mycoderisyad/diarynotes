<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title') - DiaryNotes</title>
    @stack('styles')
</head>
<body>
    <nav class="app-navbar">
        <div class="navbar-container">
            <!-- Left Island (Logo) -->
            <div class="navbar-logo-island">
                <a href="{{ url('/') }}" class="navbar-brand">
                    <img src="{{ asset('image/logo diary.svg') }}" alt="DiaryNotes">
                </a>
            </div>

            <!-- Right Island (Menu & Profile) -->
            <div class="navbar-menu-island">
                <div class="nav-links">
                    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('public_notes') }}" class="nav-link {{ Request::is('public-notes') ? 'active' : '' }}">
                        Public Notes
                    </a>
                </div>

                <div class="navbar-actions">
                    @if (Request::is('home'))
                        <a href="{{ route('notes_create') }}" class="create-note-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="create-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Create Note
                        </a>
                    @endif

                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-toggle" id="profileToggle">
                            <div class="avatar">
                                {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                            </div>
                            <!-- <span class="username">{{ Auth::user()->username ?? 'User' }}</span> -->
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chevron"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        
                        <div class="dropdown-menu" id="dropdownMenu">
                            <div class="dropdown-header">
                                <p class="dropdown-name">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="dropdown-username">{{ '@'.(Auth::user()->username ?? 'user') }}</p>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('settings') }}" class="dropdown-item {{ Request::is('settings') ? 'active' : '' }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                Settings
                            </a>
                            
                            @auth
                                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        Logout
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="app-main">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
