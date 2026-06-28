<?php

use App\Http\Controllers\NoteController;

it('normalizes a legacy single note object into a note list', function () {
    $legacyNote = [
        'id' => 'legacy-note',
        'title' => 'Legacy note',
        'content' => 'Old storage shape',
        'visibility' => 'public',
        'created_at' => '2026-03-08 18:32:52',
    ];

    expect(NoteController::normalizeNotes($legacyNote))->toBe([$legacyNote]);
});

it('drops invalid note entries while normalizing notes', function () {
    $validNote = [
        'id' => 'valid-note',
        'created_at' => '2026-03-08 18:32:52',
    ];

    expect(NoteController::normalizeNotes(['bad', $validNote, ['title' => 'missing id']]))
        ->toBe([$validNote]);
});
