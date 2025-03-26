<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTimeZone;
use DateTime;

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
        $path = $this->getUserNotesPath();
        Storage::put($path, json_encode($notes, JSON_PRETTY_PRINT));
    }

    public function create()
    {
        return view('pages.notes.create', [
            'pageTitle' => 'Create Note'
        ]);
    }

    public function notes(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);
    
        $notes = $this->getUserNotes();
    
        $jakartaTimeZone = new DateTimeZone('Asia/Jakarta');
        $currentTime = new DateTime('now', $jakartaTimeZone);
        $currentTimeFormatted = $currentTime->format('Y-m-d H:i:s');
    
        $newNote = [
            'id' => Str::uuid()->toString(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'created_at' => $currentTimeFormatted,
            'updated_at' => $currentTimeFormatted
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
            'note' => $note
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
            'note' => $note
        ]);
    }

    public function update(Request $request, $noteId)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $notes = $this->getUserNotes();
        
        $noteIndex = collect($notes)->search(function($note) use ($noteId) {
            return $note['id'] === $noteId;
        });

        if ($noteIndex === false) {
            abort(404);
        }

        $notes[$noteIndex]['title'] = $validated['title'];
        $notes[$noteIndex]['content'] = $validated['content'];
        $notes[$noteIndex]['updated_at'] = '2025-01-02 03:34:41';

        $this->saveUserNotes($notes);

        return redirect()->route('home')
            ->with('success', 'Note updated successfully.');
    }

    public function destroy($noteId)
    {
        $notes = $this->getUserNotes();
        
        $notes = collect($notes)->filter(function($note) use ($noteId) {
            return $note['id'] !== $noteId;
        })->values()->all();

        $this->saveUserNotes($notes);

        return redirect()->route('home')
            ->with('success', 'Note deleted successfully.');
    }
}