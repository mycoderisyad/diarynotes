<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    private function getUserNotesPath()
    {
        return 'notes/' . Auth::id() . '/notes.json';
    }

    private function ensureUserDirectoryExists()
    {
        $directory = 'notes/' . Auth::id();
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        foreach (['audio', 'media'] as $child) {
            $childDirectory = $directory . '/' . $child;
            if (!Storage::exists($childDirectory)) {
                Storage::makeDirectory($childDirectory);
            }
        }
    }

    private function getUserNotes()
    {
        $this->ensureUserDirectoryExists();
        $path = $this->getUserNotesPath();

        if (Storage::exists($path)) {
            return json_decode(Storage::get($path), true) ?? [];
        }

        return [];
    }

    private function saveUserNotes(array $notes)
    {
        $this->ensureUserDirectoryExists();
        Storage::put($this->getUserNotesPath(), json_encode($notes, JSON_PRETTY_PRINT));
    }

    private function getNow()
    {
        $tz = new DateTimeZone('Asia/Jakarta');
        return (new DateTime('now', $tz))->format('Y-m-d H:i:s');
    }

    public function create()
    {
        return view('pages.notes.create', [
            'pageTitle' => 'Create Note',
        ]);
    }

    public function notes(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable|string',
            'visibility' => 'required|in:public,private',
            'theme' => 'nullable|string|in:theme-yellow,theme-peach,theme-mint,theme-blue,theme-pink',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,webm|max:20480',
            'embedded_images.*' => 'nullable|file|image|max:5120',
            'embedded_audios.*' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,webm|max:20480',
        ]);

        $notes = $this->getUserNotes();
        $now = $this->getNow();
        $noteId = Str::uuid()->toString();
        $audioData = $this->storeUploadedAudio($request->file('audio'));
        $content = $this->prepareRichContent(
            $request->input('content', ''),
            $noteId,
            $request->file('embedded_images', []),
            $request->file('embedded_audios', []),
            null
        );

        $newNote = [
            'id' => $noteId,
            'title' => $validated['title'],
            'content' => $content,
            'visibility' => $validated['visibility'],
            'theme' => $validated['theme'] ?? 'theme-yellow',
            'audio_path' => $audioData['path'] ?? null,
            'audio_name' => $audioData['name'] ?? null,
            'audio_mime' => $audioData['mime'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $notes[] = $newNote;
        $this->saveUserNotes($notes);

        return redirect()->route('home');
    }

    public function show($noteId)
    {
        $notes = $this->getUserNotes();
        $note = collect($notes)->firstWhere('id', $noteId);

        if (!$note) {
            abort(404);
        }

        return view('pages.notes.show', [
            'pageTitle' => 'View Note',
            'note' => $note,
            'renderedContent' => self::renderContent($note),
        ]);
    }

    public function audioStream($noteId)
    {
        $notes = $this->getUserNotes();
        $note = collect($notes)->firstWhere('id', $noteId);

        if (!$note || empty($note['audio_path']) || !Storage::exists($note['audio_path'])) {
            abort(404);
        }

        return response()->file(Storage::path($note['audio_path']), [
            'Content-Type' => $note['audio_mime'] ?? 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="' . ($note['audio_name'] ?? 'note-audio') . '"',
        ]);
    }

    public function mediaStream($noteId, $fileName)
    {
        $note = $this->findAccessibleNoteById($noteId);

        if (!$note) {
            abort(404);
        }

        $mediaPaths = array_merge(
            self::extractMediaPaths($note['content'] ?? ''),
            array_filter([$note['audio_path'] ?? null])
        );

        $path = collect($mediaPaths)->first(function ($candidate) use ($fileName) {
            return basename($candidate) === $fileName && Storage::exists($candidate);
        });

        if (!$path) {
            abort(404);
        }

        $mime = $this->guessMimeFromPath($path);

        return response()->file(Storage::path($path), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function edit($noteId)
    {
        $notes = $this->getUserNotes();
        $note = collect($notes)->firstWhere('id', $noteId);

        if (!$note) {
            abort(404);
        }

        return view('pages.notes.edit', [
            'pageTitle' => 'Edit Note',
            'note' => $note,
            'editorContent' => self::renderEditorContent($note),
        ]);
    }

    public function update(Request $request, $noteId)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable|string',
            'visibility' => 'required|in:public,private',
            'theme' => 'nullable|string|in:theme-yellow,theme-peach,theme-mint,theme-blue,theme-pink',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,webm|max:20480',
            'remove_audio' => 'nullable|boolean',
            'embedded_images.*' => 'nullable|file|image|max:5120',
            'embedded_audios.*' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,webm|max:20480',
        ]);

        $notes = $this->getUserNotes();
        $noteIndex = collect($notes)->search(function ($note) use ($noteId) {
            return $note['id'] === $noteId;
        });

        if ($noteIndex === false) {
            abort(404);
        }

        $shouldRemoveAudio = $request->boolean('remove_audio');
        $newAudioData = $this->storeUploadedAudio($request->file('audio'));

        if ($shouldRemoveAudio || $newAudioData !== null) {
            $this->deleteFileIfExists($notes[$noteIndex]['audio_path'] ?? null);
        }

        $notes[$noteIndex]['title'] = $validated['title'];
        $notes[$noteIndex]['content'] = $this->prepareRichContent(
            $request->input('content', ''),
            $noteId,
            $request->file('embedded_images', []),
            $request->file('embedded_audios', []),
            $notes[$noteIndex]
        );
        $notes[$noteIndex]['visibility'] = $validated['visibility'];
        $notes[$noteIndex]['theme'] = $validated['theme'] ?? 'theme-yellow';
        $notes[$noteIndex]['audio_path'] = $newAudioData['path'] ?? ($shouldRemoveAudio ? null : ($notes[$noteIndex]['audio_path'] ?? null));
        $notes[$noteIndex]['audio_name'] = $newAudioData['name'] ?? ($shouldRemoveAudio ? null : ($notes[$noteIndex]['audio_name'] ?? null));
        $notes[$noteIndex]['audio_mime'] = $newAudioData['mime'] ?? ($shouldRemoveAudio ? null : ($notes[$noteIndex]['audio_mime'] ?? null));
        $notes[$noteIndex]['updated_at'] = $this->getNow();

        $this->saveUserNotes($notes);

        return redirect()->route('home')
            ->with('success', 'Note updated successfully.');
    }

    public function destroy($noteId)
    {
        $notes = $this->getUserNotes();
        $noteToDelete = collect($notes)->firstWhere('id', $noteId);

        $this->deleteFileIfExists($noteToDelete['audio_path'] ?? null);
        foreach (self::extractMediaPaths($noteToDelete['content'] ?? '') as $path) {
            $this->deleteFileIfExists($path);
        }

        $notes = collect($notes)->filter(function ($note) use ($noteId) {
            return $note['id'] !== $noteId;
        })->values()->all();

        $this->saveUserNotes($notes);

        return redirect()->route('home')
            ->with('success', 'Note deleted successfully.');
    }

    public static function getAllPublicNotes()
    {
        $allPublicNotes = [];
        $basePath = 'notes';

        if (!Storage::exists($basePath)) {
            return [];
        }

        foreach (Storage::directories($basePath) as $dir) {
            $filePath = $dir . '/notes.json';
            if (!Storage::exists($filePath)) {
                continue;
            }

            $notes = json_decode(Storage::get($filePath), true) ?? [];
            $userId = basename($dir);
            $user = \App\Models\User::find($userId);

            foreach ($notes as $note) {
                if (($note['visibility'] ?? 'private') === 'public') {
                    $note['author'] = $user ? $user->name : 'Unknown';
                    $allPublicNotes[] = $note;
                }
            }
        }

        usort($allPublicNotes, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $allPublicNotes;
    }

    public static function extractPlainText(string $content): string
    {
        $content = preg_replace('/<(?:figure|span)\b[^>]*class="note-embed"[^>]*>.*?<\/(?:figure|span)>/is', ' ', $content);
        $content = strip_tags($content);
        $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $content) ?? '');
    }

    public static function renderContent(array $note): string
    {
        $content = $note['content'] ?? '';

        if ($content === '') {
            return '';
        }

        if (!self::looksLikeRichContent($content)) {
            return nl2br(e($content));
        }

        $safe = self::sanitizeRichHtml($content);

        return self::replaceEmbedPlaceholders($safe, function (string $attributes) use ($note) {
            $kind = strtolower(self::extractHtmlAttribute($attributes, 'data-kind') ?? '');
            $path = html_entity_decode(self::extractHtmlAttribute($attributes, 'data-media-path') ?? '', ENT_QUOTES, 'UTF-8');
            $name = html_entity_decode(self::extractHtmlAttribute($attributes, 'data-media-name') ?? '', ENT_QUOTES, 'UTF-8');
            $mime = html_entity_decode(self::extractHtmlAttribute($attributes, 'data-media-mime') ?? '', ENT_QUOTES, 'UTF-8');

            if (!in_array($kind, ['image', 'audio'], true) || $path === '') {
                return '';
            }

            $url = route('note_media', [
                'note' => $note['id'],
                'file' => basename($path),
            ]);

            if ($kind === 'image') {
                return '<span class="note-embedded-media note-embedded-image"><img src="' . e($url) . '" alt="' . e($name ?: 'Embedded image') . '"></span>';
            }

            return '<span class="note-embedded-media note-embedded-audio"><audio controls preload="none" class="note-audio-player"><source src="' . e($url) . '" type="' . e($mime ?: 'audio/mpeg') . '"></audio></span>';
        });
    }

    public static function renderEditorContent(array $note): string
    {
        $content = $note['content'] ?? '';

        if ($content === '') {
            return '<p><br></p>';
        }

        if (!self::looksLikeRichContent($content)) {
            $paragraphs = preg_split("/\R{2,}/", $content) ?: [];
            $html = collect($paragraphs)->map(function ($paragraph) {
                return '<p>' . nl2br(e(trim($paragraph))) . '</p>';
            })->implode('');

            return $html !== '' ? $html : '<p><br></p>';
        }

        $rendered = self::replaceEmbedPlaceholders($content, function (string $attributes) use ($note) {
            $kind = strtolower(self::extractHtmlAttribute($attributes, 'data-kind') ?? '');
            $path = html_entity_decode(self::extractHtmlAttribute($attributes, 'data-media-path') ?? '', ENT_QUOTES, 'UTF-8');
            $name = html_entity_decode(self::extractHtmlAttribute($attributes, 'data-media-name') ?? '', ENT_QUOTES, 'UTF-8');
            $mime = html_entity_decode(self::extractHtmlAttribute($attributes, 'data-media-mime') ?? '', ENT_QUOTES, 'UTF-8');

            if (!in_array($kind, ['image', 'audio'], true) || $path === '') {
                return '';
            }

            $previewUrl = route('note_media', [
                'note' => $note['id'],
                'file' => basename($path),
            ]);

            if ($kind === 'image') {
                return '<span class="editor-embed editor-embed-image" contenteditable="false" data-kind="image" data-media-path="' . e($path) . '" data-media-name="' . e($name) . '"><button type="button" class="editor-embed-remove" aria-label="Remove image">Remove</button><span class="editor-embed-preview"><img src="' . e($previewUrl) . '" alt="' . e($name ?: 'Embedded image') . '"></span><span class="editor-embed-label">' . e($name ?: 'Image') . '</span></span>';
            }

            return '<span class="editor-embed editor-embed-audio" contenteditable="false" data-kind="audio" data-media-path="' . e($path) . '" data-media-name="' . e($name) . '" data-media-mime="' . e($mime) . '"><button type="button" class="editor-embed-remove" aria-label="Remove audio">Remove</button><span class="editor-embed-preview"><audio controls preload="none" class="note-audio-player"><source src="' . e($previewUrl) . '" type="' . e($mime ?: 'audio/mpeg') . '"></audio></span><span class="editor-embed-label">' . e($name ?: 'Audio') . '</span></span>';
        });

        return $rendered !== '' ? $rendered : '<p><br></p>';
    }

    public static function extractMediaPaths(string $content): array
    {
        preg_match_all('/data-media-path="([^"]+)"/i', $content, $matches);

        return array_values(array_unique(array_map(function ($path) {
            return html_entity_decode($path, ENT_QUOTES, 'UTF-8');
        }, $matches[1] ?? [])));
    }

    public static function looksLikeRichContent(string $content): bool
    {
        return str_contains($content, 'class="note-embed"')
            || str_contains($content, '<p')
            || str_contains($content, '<div');
    }

    private function prepareRichContent(string $rawContent, string $noteId, array $embeddedImages, array $embeddedAudios, ?array $existingNote): string
    {
        $content = trim($rawContent);
        $previousPaths = self::extractMediaPaths($existingNote['content'] ?? '');

        if ($content === '') {
            foreach ($previousPaths as $path) {
                $this->deleteFileIfExists($path);
            }

            return '';
        }

        $content = self::sanitizeRichHtml($content);

        $processed = self::replaceEmbedPlaceholders($content, function (string $attributes) use ($noteId, $embeddedImages, $embeddedAudios, $previousPaths) {
            $kind = strtolower(self::extractHtmlAttribute($attributes, 'data-kind') ?? '');
            $uploadKey = self::extractHtmlAttribute($attributes, 'data-upload-key');
            $existingPath = html_entity_decode(self::extractHtmlAttribute($attributes, 'data-media-path') ?? '', ENT_QUOTES, 'UTF-8');
            $existingName = html_entity_decode(self::extractHtmlAttribute($attributes, 'data-media-name') ?? '', ENT_QUOTES, 'UTF-8');
            $existingMime = html_entity_decode(self::extractHtmlAttribute($attributes, 'data-media-mime') ?? '', ENT_QUOTES, 'UTF-8');

            if (!in_array($kind, ['image', 'audio'], true)) {
                return '';
            }

            if ($uploadKey) {
                $file = $kind === 'image'
                    ? ($embeddedImages[$uploadKey] ?? null)
                    : ($embeddedAudios[$uploadKey] ?? null);

                if (!$file instanceof UploadedFile) {
                    return '';
                }

                $mediaData = $this->storeEmbeddedMedia($noteId, $kind, $file);

                return '<span class="note-embed" data-kind="' . e($kind) . '" data-media-path="' . e($mediaData['path']) . '" data-media-name="' . e($mediaData['name']) . '" data-media-mime="' . e($mediaData['mime']) . '"></span>';
            }

            if ($existingPath !== '' && in_array($existingPath, $previousPaths, true) && Storage::exists($existingPath)) {
                return '<span class="note-embed" data-kind="' . e($kind) . '" data-media-path="' . e($existingPath) . '" data-media-name="' . e($existingName) . '" data-media-mime="' . e($existingMime) . '"></span>';
            }

            return '';
        });

        $processed = strip_tags($processed, '<p><br><span>');
        $processed = preg_replace_callback('/<span\b([^>]*)>\s*<\/span>/i', function ($matches) {
            $class = trim(self::extractHtmlAttribute($matches[1], 'class') ?? '');

            if ($class !== 'note-embed') {
                return '';
            }

            $kind = strtolower(self::extractHtmlAttribute($matches[1], 'data-kind') ?? '');
            $path = html_entity_decode(self::extractHtmlAttribute($matches[1], 'data-media-path') ?? '', ENT_QUOTES, 'UTF-8');
            $name = html_entity_decode(self::extractHtmlAttribute($matches[1], 'data-media-name') ?? '', ENT_QUOTES, 'UTF-8');
            $mime = html_entity_decode(self::extractHtmlAttribute($matches[1], 'data-media-mime') ?? '', ENT_QUOTES, 'UTF-8');

            if (!in_array($kind, ['image', 'audio'], true) || $path === '') {
                return '';
            }

            return '<span class="note-embed" data-kind="' . e($kind) . '" data-media-path="' . e($path) . '" data-media-name="' . e($name) . '" data-media-mime="' . e($mime) . '"></span>';
        }, $processed) ?? '';
        $processed = preg_replace('/<p>\s*<\/p>/i', '', $processed);
        $processed = preg_replace('/<p>(?:\s|&nbsp;|<br\s*\/?>)*<\/p>/i', '', $processed);

        $currentPaths = self::extractMediaPaths($processed);
        foreach (array_diff($previousPaths, $currentPaths) as $removedPath) {
            $this->deleteFileIfExists($removedPath);
        }

        return trim($processed);
    }

    private function storeUploadedAudio(?UploadedFile $audio): ?array
    {
        if (!$audio) {
            return null;
        }

        $this->ensureUserDirectoryExists();

        $extension = $audio->getClientOriginalExtension() ?: $audio->extension() ?: 'bin';
        $filename = Str::uuid()->toString() . '.' . $extension;
        $path = 'notes/' . Auth::id() . '/audio/' . $filename;

        Storage::putFileAs('notes/' . Auth::id() . '/audio', $audio, $filename);

        return [
            'path' => $path,
            'name' => $audio->getClientOriginalName(),
            'mime' => $audio->getMimeType(),
        ];
    }

    private function storeEmbeddedMedia(string $noteId, string $kind, UploadedFile $file): array
    {
        $this->ensureUserDirectoryExists();

        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'bin';
        $filename = Str::uuid()->toString() . '.' . $extension;
        $directory = 'notes/' . Auth::id() . '/media/' . $noteId;

        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        Storage::putFileAs($directory, $file, $filename);

        return [
            'path' => $directory . '/' . $filename,
            'name' => $file->getClientOriginalName() ?: ($kind . '.' . $extension),
            'mime' => $file->getMimeType() ?: ($kind === 'image' ? 'image/jpeg' : 'audio/mpeg'),
        ];
    }

    private function deleteFileIfExists(?string $path): void
    {
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    private static function sanitizeRichHtml(string $content): string
    {
        $content = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $content);
        $content = preg_replace('/\son\w+=(["\']).*?\1/i', '', $content);
        $content = preg_replace('/javascript:/i', '', $content);
        $content = preg_replace('/<div\b[^>]*>/i', '<p>', $content);
        $content = preg_replace('/<\/div>/i', '</p>', $content);

        return str_replace('&nbsp;', ' ', $content);
    }

    private static function replaceEmbedPlaceholders(string $content, callable $callback): string
    {
        return preg_replace_callback('/<(?:span|figure)\b([^>]*)>\s*<\/(?:span|figure)>/i', function ($matches) use ($callback) {
            $class = trim(self::extractHtmlAttribute($matches[1], 'class') ?? '');

            if ($class !== 'note-embed') {
                return $matches[0];
            }

            return $callback($matches[1]);
        }, $content) ?? $content;
    }

    private static function extractHtmlAttribute(string $attributes, string $attribute): ?string
    {
        if (preg_match('/' . preg_quote($attribute, '/') . '="([^"]*)"/i', $attributes, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function guessMimeFromPath(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'm4a' => 'audio/mp4',
            'aac' => 'audio/aac',
            'webm' => 'audio/webm',
            default => 'application/octet-stream',
        };
    }

    private function findAccessibleNoteById(string $noteId): ?array
    {
        if (Auth::check()) {
            $ownedNote = collect($this->getUserNotes())->firstWhere('id', $noteId);
            if ($ownedNote) {
                return $ownedNote;
            }
        }

        return collect(self::getAllPublicNotes())->first(function ($note) use ($noteId) {
            return (string) ($note['id'] ?? '') === $noteId;
        });
    }
}
