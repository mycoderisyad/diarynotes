@extends('layouts.app')

@section('title', 'View Note')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/notes/show.css') }}">
@endpush

@section('content')
<div class="note-container note-read-page">
    <div class="note-header">
        <a href="{{ route('home') }}" class="back-button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back
        </a>
        <span class="visibility-badge {{ ($note['visibility'] ?? 'private') === 'public' ? 'badge-public' : 'badge-private' }}">
            @if(($note['visibility'] ?? 'private') === 'public')
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                Public
            @else
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Private
            @endif
        </span>
    </div>

    <article class="note-view {{ $note['theme'] ?? 'theme-yellow' }}">
        <div class="note-view-top">
            <p class="note-overline">Your Note</p>
            <h1 class="note-title">{{ $note['title'] }}</h1>
            <div class="note-metadata">
                <span class="note-meta-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ \Carbon\Carbon::parse($note['created_at'])->format('d M Y, H:i') }} WIB
                </span>
                <span class="note-meta-pill">Theme: {{ str_replace('theme-', '', $note['theme'] ?? 'yellow') }}</span>
            </div>
        </div>
        <div class="note-body">
            {!! $renderedContent !!}
        </div>

        @if(!empty($note['audio_path']))
            <div class="note-audio-section">
                <p class="note-audio-label">Attached audio</p>
                <audio controls preload="none" class="note-audio-player">
                    <source src="{{ route('notes_audio', $note['id']) }}" type="{{ $note['audio_mime'] ?? 'audio/mpeg' }}">
                </audio>
            </div>
        @endif
    </article>

    <div class="note-actions">
        <a href="{{ route('notes_edit', $note['id']) }}" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit
        </a>
        <form action="{{ route('notes_destroy', $note['id']) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus note ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Delete
            </button>
        </form>
    </div>
</div>
@endsection
