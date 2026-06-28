@extends('layouts.app')

@section('title', 'My Notes')

@push('styles')
    @vite('resources/css/pages/home.css')
@endpush

@section('content')
<div class="note-container">
    @if(count($notes) > 0)
        <div class="notes-grid">
            @foreach($notes as $note)
                <div class="note-card {{ $note['theme'] ?? 'theme-yellow' }}" role="link" tabindex="0" data-note-card-url="{{ route('notes_show', $note['id']) }}">
                    <div class="note-card-content">
                        <div class="note-card-header">
                            <h3 class="note-card-title">{{ $note['title'] }}</h3>
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

                        <p class="note-card-text">{{ Str::limit(\App\Http\Controllers\NoteController::extractPlainText($note['content'] ?? ''), 160) }}</p>
                    </div>

                    <div class="note-card-footer">
                        <div class="note-card-meta">
                            <div class="note-card-meta-copy">
                                <span class="note-date-label">Created</span>
                                <span class="note-date">{{ \Carbon\Carbon::parse($note['created_at'])->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="note-card-actions">
                            <a href="{{ route('notes_edit', $note['id']) }}" class="action-btn" title="Edit" data-stop-card-navigation>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="{{ route('notes_destroy', $note['id']) }}" method="POST" class="inline-form" data-stop-card-navigation
                                  onsubmit="return confirm('Yakin ingin menghapus note ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-danger" title="Delete">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            <p>Belum ada catatan. Mulai menulis sekarang!</p>
        </div>
    @endif
</div>
@endsection
