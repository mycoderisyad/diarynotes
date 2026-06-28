@extends('layouts.public')

@section('title', $note['title'] . ' - Public Note')
@section('body_class', 'public-note-read-page')
@section('nav_id', 'public-note-read-nav')

@section('content')
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
@endsection
