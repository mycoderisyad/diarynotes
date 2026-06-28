@extends('layouts.public')

@section('title', 'Public Notes - DiaryNotes')
@section('body_class', 'public-notes-page')
@section('nav_id', 'public-notes-nav')

@section('content')
    <div class="page-container">
        <section class="public-notes-toolbar">
            <div class="notes-summary-card">
                <span class="notes-summary-label">Public Notes</span>
                <div class="notes-summary-stats">
                    <span class="notes-summary-value" id="public-notes-count">{{ count($publicNotes) }}</span>
                    <span class="notes-summary-copy">notes available</span>
                </div>
            </div>

            <div class="notes-controls-card">
                <label class="notes-search-field" for="public-notes-search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input
                        type="search"
                        id="public-notes-search"
                        placeholder="Search title, content, or author"
                        autocomplete="off"
                    >
                </label>

                <div class="notes-filter-field">
                    <span>Filter</span>
                    <div class="notes-select-wrap" data-public-select>
                        <button type="button" class="notes-select-button" id="public-notes-filter" data-select-button data-value="all" aria-haspopup="listbox" aria-expanded="false">
                            <span data-select-label>All Notes</span>
                        </button>
                        <div class="notes-select-menu" role="listbox" aria-labelledby="public-notes-filter" hidden>
                            <button type="button" role="option" data-select-option data-value="all" aria-selected="true">All Notes</button>
                            <button type="button" role="option" data-select-option data-value="member" aria-selected="false">Member Notes</button>
                            <button type="button" role="option" data-select-option data-value="guest" aria-selected="false">Guest Notes</button>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>

                <div class="notes-filter-field">
                    <span>Sort</span>
                    <div class="notes-select-wrap" data-public-select>
                        <button type="button" class="notes-select-button" id="public-notes-sort" data-select-button data-value="latest" aria-haspopup="listbox" aria-expanded="false">
                            <span data-select-label>Latest</span>
                        </button>
                        <div class="notes-select-menu" role="listbox" aria-labelledby="public-notes-sort" hidden>
                            <button type="button" role="option" data-select-option data-value="latest" aria-selected="true">Latest</button>
                            <button type="button" role="option" data-select-option data-value="oldest" aria-selected="false">Oldest</button>
                            <button type="button" role="option" data-select-option data-value="az" aria-selected="false">A to Z</button>
                            <button type="button" role="option" data-select-option data-value="za" aria-selected="false">Z to A</button>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        @if(count($publicNotes) > 0)
            <div class="public-notes-grid" id="public-notes-grid">
                @foreach($publicNotes as $note)
                    <a
                        href="{{ route('public_notes_show', ['source' => $note['source'], 'note' => $note['id']]) }}"
                        class="public-note-card-link"
                        aria-label="Open {{ $note['title'] }}"
                        data-note-item
                        data-source="{{ $note['source'] }}"
                        data-title="{{ Str::lower($note['title']) }}"
                        data-author="{{ Str::lower($note['author']) }}"
                        data-content="{{ Str::lower(\App\Http\Controllers\NoteController::extractPlainText($note['content'] ?? '')) }}"
                        data-created-at="{{ \Carbon\Carbon::parse($note['created_at'])->timestamp }}"
                    >
                        <article class="public-note-card {{ $note['theme'] ?? 'theme-yellow' }}">
                            <div class="note-card-top">
                                <span class="note-type-chip">{{ !empty($note['is_guest']) ? 'Guest Note' : 'Member Note' }}</span>
                                <time class="note-date" datetime="{{ \Carbon\Carbon::parse($note['created_at'])->toIso8601String() }}">
                                    {{ \Carbon\Carbon::parse($note['created_at'])->format('d M Y') }}
                                </time>
                            </div>

                            <h3 class="note-title">{{ $note['title'] }}</h3>
                            <p class="note-excerpt">{{ Str::limit(\App\Http\Controllers\NoteController::extractPlainText($note['content'] ?? ''), 150) }}</p>

                            <div class="note-meta">
                                <div class="note-author-block">
                                    <span class="note-meta-label">Written by</span>
                                    <span class="note-author">{{ $note['author'] }}</span>
                                </div>
                                <span class="note-meta-dot" aria-hidden="true"></span>
                                <span class="note-meta-text">Public</span>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>

            <div class="empty-notes empty-notes-search" id="public-notes-empty-search" hidden>
                <p>No notes match your search or selected filter.</p>
            </div>
        @else
            <div class="empty-notes">
                <p>No public notes yet. Be the first person to publish one.</p>
                <a href="{{ route('guest_note_create') }}" class="welcome-btn-outline">Write a Note</a>
            </div>
        @endif
    </div>

@endsection
