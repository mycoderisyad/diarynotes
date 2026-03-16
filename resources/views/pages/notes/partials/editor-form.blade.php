@php
    $noteId = $note['id'] ?? 'draft';
    $initialEditorContent = old('content')
        ? \App\Http\Controllers\NoteController::renderEditorContent([
            'id' => $noteId,
            'content' => old('content'),
        ])
        : ($editorContent ?? '<p><br></p>');
@endphp

<div class="note-container">
    <div class="note-header">
        <a href="{{ route('home') }}" class="back-button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="note-form note-editor-form" data-note-editor-form>
        @csrf
        @if(!empty($httpMethod) && strtoupper($httpMethod) !== 'POST')
            @method($httpMethod)
        @endif

        <input type="text"
               class="note-title"
               name="title"
               value="{{ old('title', $note['title'] ?? '') }}"
               placeholder="Untitled"
               autofocus>

        <input type="hidden" name="content" id="note-content-input">

        <div class="editor-shell">
            <div class="editor-toolbar">
                <div class="editor-toolbar-actions">
                    <button type="button" class="editor-tool-btn" data-editor-action="image">Add Image</button>
                    <button type="button" class="editor-tool-btn" data-editor-action="audio">Add Audio</button>
                    <button type="button" class="editor-tool-btn" data-editor-action="record" data-record-button>Record Audio</button>
                </div>
                <span class="editor-toolbar-hint" data-recorder-status>Place media anywhere between your sentences.</span>
            </div>

            <div class="editor-surface"
                 id="note-editor"
                 contenteditable="true"
                 spellcheck="true"
                 data-placeholder="Start writing here. Add audio or images exactly where they belong in the note.">{!! $initialEditorContent !!}</div>

            <div class="editor-hidden-uploads" id="editor-hidden-uploads"></div>
            <input type="file" id="editor-image-picker" accept="image/*" hidden>
            <input type="file" id="editor-audio-picker" accept="audio/*" hidden>
        </div>

        <div class="visibility-group">
            <label class="visibility-label">Visibility</label>
            <div class="visibility-options">
                <label class="visibility-option">
                    <input type="radio" name="visibility" value="private" {{ old('visibility', $note['visibility'] ?? 'private') === 'private' ? 'checked' : '' }}>
                    <span class="option-box">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Private
                    </span>
                </label>
                <label class="visibility-option">
                    <input type="radio" name="visibility" value="public" {{ old('visibility', $note['visibility'] ?? 'private') === 'public' ? 'checked' : '' }}>
                    <span class="option-box">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        Public
                    </span>
                </label>
            </div>
        </div>

        <div class="theme-group">
            <label class="visibility-label">Theme / Card Color</label>
            <div class="theme-options">
                <label class="theme-option" title="Yellow">
                    <input type="radio" name="theme" value="theme-yellow" {{ old('theme', $note['theme'] ?? 'theme-yellow') === 'theme-yellow' ? 'checked' : '' }}>
                    <span class="theme-box" style="background-color: #ffd27d;"></span>
                </label>
                <label class="theme-option" title="Peach">
                    <input type="radio" name="theme" value="theme-peach" {{ old('theme', $note['theme'] ?? 'theme-yellow') === 'theme-peach' ? 'checked' : '' }}>
                    <span class="theme-box" style="background-color: #ffa882;"></span>
                </label>
                <label class="theme-option" title="Mint">
                    <input type="radio" name="theme" value="theme-mint" {{ old('theme', $note['theme'] ?? 'theme-yellow') === 'theme-mint' ? 'checked' : '' }}>
                    <span class="theme-box" style="background-color: #d1f49b;"></span>
                </label>
                <label class="theme-option" title="Blue">
                    <input type="radio" name="theme" value="theme-blue" {{ old('theme', $note['theme'] ?? 'theme-yellow') === 'theme-blue' ? 'checked' : '' }}>
                    <span class="theme-box" style="background-color: #9ee1ff;"></span>
                </label>
                <label class="theme-option" title="Pink">
                    <input type="radio" name="theme" value="theme-pink" {{ old('theme', $note['theme'] ?? 'theme-yellow') === 'theme-pink' ? 'checked' : '' }}>
                    <span class="theme-box" style="background-color: #ffafc5;"></span>
                </label>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('home') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        </div>
    </form>
</div>
