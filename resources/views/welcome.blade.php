<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DiaryNotes</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/welcome/style.css') }}">
    <style>
        /* Navbar hanya di pojok kanan atas */
        .welcome-navbar {
            position: fixed;
            top: 24px;
            right: 40px;
            z-index: 100;
        }

        .welcome-navbar .navbar-menu-island {
            background: #ffffff;
            padding: 8px 16px;
            border-radius: 40px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }
    </style>
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

    <script src="{{ asset('js/quotes.js') }}"></script>
</body>
</html>
