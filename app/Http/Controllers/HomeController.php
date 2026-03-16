<?php

namespace App\Http\Controllers;

use App\Models\GuestNote;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function landing()
    {
        return view('welcome');
    }

    public function publicNotes()
    {
        $publicNotes = collect(NoteController::getAllPublicNotes())
            ->map(function ($note) {
                $note['source'] = 'member';
                $note['is_guest'] = false;

                return $note;
            })
            ->all();

        $guestNotes = GuestNote::latest()->get()->map(function ($note) {
            return [
                'id'         => $note->id,
                'title'      => $note->title,
                'content'    => $note->content,
                'author'     => $note->author_name,
                'created_at' => $note->created_at->format('Y-m-d H:i:s'),
                'theme'      => $note->theme ?? 'theme-yellow',
                'source'     => 'guest',
                'is_guest'   => true,
            ];
        })->toArray();

        $allNotes = array_merge($publicNotes, $guestNotes);
        usort($allNotes, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return view('pages.public-notes', [
            'publicNotes' => $allNotes,
        ]);
    }

    public function showPublicNote(string $source, string $noteId)
    {
        $note = match ($source) {
            'guest' => $this->findGuestPublicNote($noteId),
            'member' => $this->findMemberPublicNote($noteId),
            default => null,
        };

        abort_if(!$note, 404);

        return view('pages.public-note-show', [
            'note' => $note,
            'renderedContent' => NoteController::renderContent($note),
        ]);
    }

    public function streamPublicNoteAudio(string $source, string $noteId)
    {
        $note = match ($source) {
            'guest' => $this->findGuestPublicNote($noteId),
            'member' => $this->findMemberPublicNote($noteId),
            default => null,
        };

        if (!$note || empty($note['audio_path']) || !Storage::exists($note['audio_path'])) {
            abort(404);
        }

        return response()->file(Storage::path($note['audio_path']), [
            'Content-Type' => $note['audio_mime'] ?? 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="' . ($note['audio_name'] ?? 'note-audio') . '"',
        ]);
    }

    public function homepage()
    {
        $notes = [];
        $path = 'notes/' . Auth::id() . '/notes.json';

        if (Storage::exists($path)) {
            $notes = json_decode(Storage::get($path), true) ?? [];

            usort($notes, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }

        return view('pages.home', compact('notes'));
    }

    private function findGuestPublicNote(string $noteId): ?array
    {
        $note = GuestNote::find($noteId);

        if (!$note) {
            return null;
        }

        return [
            'id' => (string) $note->id,
            'title' => $note->title,
            'content' => $note->content,
            'author' => $note->author_name,
            'created_at' => $note->created_at->format('Y-m-d H:i:s'),
            'theme' => $note->theme ?? 'theme-yellow',
            'source' => 'guest',
            'is_guest' => true,
        ];
    }

    private function findMemberPublicNote(string $noteId): ?array
    {
        $note = collect(NoteController::getAllPublicNotes())
            ->first(function ($note) use ($noteId) {
                return (string) ($note['id'] ?? '') === $noteId;
            });

        if (!$note) {
            return null;
        }

        $note['source'] = 'member';
        $note['is_guest'] = false;

        return $note;
    }
}
