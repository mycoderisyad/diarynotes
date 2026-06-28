<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Notes - DiaryNotes</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kalam:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/welcome/style.css') }}">
</head>
<body class="public-notes-page">
    <nav class="app-navbar" id="public-notes-nav">
        <div class="navbar-container">
            <!-- Left Island (Logo) -->
            <div class="navbar-logo-island">
                <a href="{{ route('landing') }}" class="navbar-brand">
                    <img src="{{ asset('image/logo diary.svg') }}" alt="DiaryNotes">
                </a>
            </div>

            <!-- Right Island (Menu & Actions) -->
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

    <script>
        const searchInput = document.getElementById('public-notes-search');
        const filterButton = document.getElementById('public-notes-filter');
        const sortButton = document.getElementById('public-notes-sort');
        const notesGrid = document.getElementById('public-notes-grid');
        const noteItems = Array.from(document.querySelectorAll('[data-note-item]'));
        const notesCount = document.getElementById('public-notes-count');
        const emptySearchState = document.getElementById('public-notes-empty-search');
        const customSelects = Array.from(document.querySelectorAll('[data-public-select]'));

        if (searchInput && filterButton && sortButton && notesGrid && noteItems.length) {
            const closeSelect = (selectWrap) => {
                const button = selectWrap.querySelector('[data-select-button]');
                const menu = selectWrap.querySelector('.notes-select-menu');

                button?.setAttribute('aria-expanded', 'false');
                if (menu) {
                    menu.hidden = true;
                }
            };

            const closeOtherSelects = (activeSelect) => {
                customSelects.forEach((selectWrap) => {
                    if (selectWrap !== activeSelect) {
                        closeSelect(selectWrap);
                    }
                });
            };

            const applyFilters = () => {
                const query = searchInput.value.trim().toLowerCase();
                const selectedFilter = filterButton.dataset.value || 'all';
                const sortMode = sortButton.dataset.value || 'latest';

                const filteredItems = noteItems.filter((item) => {
                    const source = item.dataset.source || '';
                    const haystack = [
                        item.dataset.title || '',
                        item.dataset.author || '',
                        item.dataset.content || '',
                    ].join(' ');

                    const matchesQuery = !query || haystack.includes(query);
                    const matchesFilter = selectedFilter === 'all' || source === selectedFilter;

                    return matchesQuery && matchesFilter;
                });

                const sortedItems = [...filteredItems].sort((a, b) => {
                    const titleA = a.dataset.title || '';
                    const titleB = b.dataset.title || '';
                    const timeA = Number(a.dataset.createdAt || 0);
                    const timeB = Number(b.dataset.createdAt || 0);

                    switch (sortMode) {
                        case 'oldest':
                            return timeA - timeB;
                        case 'az':
                            return titleA.localeCompare(titleB);
                        case 'za':
                            return titleB.localeCompare(titleA);
                        case 'latest':
                        default:
                            return timeB - timeA;
                    }
                });

                noteItems.forEach((item) => {
                    item.style.display = 'none';
                });

                sortedItems.forEach((item) => {
                    item.style.display = '';
                    notesGrid.appendChild(item);
                });

                notesCount.textContent = sortedItems.length;
                if (emptySearchState) {
                    emptySearchState.hidden = sortedItems.length > 0;
                }
            };

            searchInput.addEventListener('input', applyFilters);

            customSelects.forEach((selectWrap) => {
                const button = selectWrap.querySelector('[data-select-button]');
                const label = selectWrap.querySelector('[data-select-label]');
                const menu = selectWrap.querySelector('.notes-select-menu');
                const options = Array.from(selectWrap.querySelectorAll('[data-select-option]'));

                button.addEventListener('click', () => {
                    const willOpen = menu.hidden;
                    closeOtherSelects(selectWrap);
                    button.setAttribute('aria-expanded', String(willOpen));
                    menu.hidden = !willOpen;
                });

                options.forEach((option) => {
                    option.addEventListener('click', () => {
                        button.dataset.value = option.dataset.value;
                        label.textContent = option.textContent;

                        options.forEach((item) => {
                            item.setAttribute('aria-selected', String(item === option));
                        });

                        closeSelect(selectWrap);
                        applyFilters();
                    });
                });
            });

            document.addEventListener('click', (event) => {
                if (!event.target.closest('[data-public-select]')) {
                    customSelects.forEach(closeSelect);
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    customSelects.forEach(closeSelect);
                }
            });

            applyFilters();
        }
    </script>

    <footer class="welcome-footer">
        <p>Made by Muhammad Risyad Raflan</p>
    </footer>
</body>
</html>
