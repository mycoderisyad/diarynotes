<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DiaryNotes</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/welcome.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Navbar pojok kanan atas -->
    <nav class="welcome-navbar">
        <div class="navbar-menu-island">
            <div class="nav-links">
                <a href="{{ route('public_notes') }}" class="nav-link">Public Notes</a>
            </div>
            <div class="navbar-actions">
                @auth
                    <a href="{{ route('home') }}" class="nav-btn">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Welcome --}}
    <div class="container">
        <img src="{{ asset('image/Diary Notes 1 (1).png') }}" alt="Logo" class="logo">
        <div id="quote"></div>
        <a href="{{ route('login') }}" class="login-btn">Start Writing</a>
    </div>

</body>
</html>
