<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $note['title'] }} - Public Note</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kalam:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/welcome/style.css') }}">
</head>
<body class="public-note-read-page">
    <nav class="app-navbar" id="public-note-read-nav">
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

    <main class="public-note-read-shell">
        <div class="public-note-read-header">
            <a href="{{ route('public_notes') }}" class="public-note-back-link">Back to Public Notes</a>
            <div class="public-note-read-meta">
                <span class="note-type-chip">{{ !empty($note['is_guest']) ? 'Guest Note' : 'Member Note' }}</span>
                <span class="public-note-read-date">{{ \Carbon\Carbon::parse($note['created_at'])->format('d M Y, H:i') }} WIB</span>
            </div>
        </div>

        <article class="public-note-read-card {{ $note['theme'] ?? 'theme-yellow' }}">
            <div class="public-note-read-top">
                <p class="public-note-overline">Public Note</p>
                <h1 class="public-note-read-title">{{ $note['title'] }}</h1>
                <p class="public-note-read-author">by {{ $note['author'] }}</p>
            </div>

            <div class="public-note-read-body">
                {!! $renderedContent !!}
            </div>

            @if(!empty($note['audio_path']) && !empty($note['source']))
                <div class="public-note-audio-section">
                    <p class="public-note-audio-label">Attached audio</p>
                    <audio controls preload="none" class="note-audio-player">
                        <source src="{{ route('public_notes_audio', ['source' => $note['source'], 'note' => $note['id']]) }}" type="{{ $note['audio_mime'] ?? 'audio/mpeg' }}">
                    </audio>
                </div>
            @endif
        </article>
    </main>

    <footer class="welcome-footer">
        <p>Made by Muhammad Risyad Raflan</p>
    </footer>
</body>
</html>
