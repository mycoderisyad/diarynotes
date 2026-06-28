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

        @include('partials.notes.visibility-picker', ['selectedVisibility' => $note['visibility'] ?? 'private'])
        @include('partials.notes.theme-picker', ['selectedTheme' => $note['theme'] ?? 'theme-yellow'])

        <div class="form-actions">
            <a href="{{ route('home') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        </div>
    </form>
</div>
