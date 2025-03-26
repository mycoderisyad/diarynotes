<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app/styles.css') }}">
    <title>@yield('title')</title>
    @stack('styles')
</head>
<body>
    <div class="navbar">
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        @if (Request::is('home'))
            <a href="{{ route('notes_create') }}" class="create-note-btn">+ Create Note</a>
        @endif
    </div>
    <div class="sidebar">
        <div class="sidebar-brand">
            <a href="{{ url('/home') }}">
                <img src="{{ asset('image/logo diary.svg') }}" alt="Diary Notes">
            </a>
        </div>

        <div class="sidebar-menu">
            <ul>
                <li><a href="{{ url('/home') }}" class="{{ Request::is('home') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>Home
                    </a></li>
                <li><a href="{{ url('/settings') }}" class="{{ Request::is('settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>Settings
                    </a></li>
                <li><a href="{{ url('/about') }}" class="{{ Request::is('about') ? 'active' : '' }}">
                        <i class="fas fa-info-circle"></i>About
                    </a></li>
                <li><a href="{{ url('/contact') }}" class="{{ Request::is('contact') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i>Contact
                    </a></li>
                @auth
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>

        <div class="user-info">
            <p>Current User's Login: {{ Auth::user()->username }}</p>
            <div class="datetime-display"></div>
        </div>
    </div>

    <div class="overlay"></div>

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
