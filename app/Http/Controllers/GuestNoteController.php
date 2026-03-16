<?php

namespace App\Http\Controllers;

use App\Models\GuestNote;
use Illuminate\Http\Request;

class GuestNoteController extends Controller
{
    public function create(Request $request)
    {
        $sessionId = $request->session()->getId();
        $existing = GuestNote::where('session_id', $sessionId)->exists();

        if ($existing) {
            return redirect()->route('login')
                ->with('info', 'Kamu sudah membuat 1 note. Daftar atau login untuk membuat lebih banyak.');
        }

        return view('pages.notes.guest-create', [
            'pageTitle' => 'Write a Note',
        ]);
    }

    public function store(Request $request)
    {
        $sessionId = $request->session()->getId();
        $existing = GuestNote::where('session_id', $sessionId)->exists();

        if ($existing) {
            return redirect()->route('login')
                ->with('info', 'Kamu sudah membuat 1 note. Daftar atau login untuk membuat lebih banyak.');
        }

        $validated = $request->validate([
            'author_name' => 'nullable|string|max:50',
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'theme'       => 'nullable|string|in:theme-yellow,theme-peach,theme-mint,theme-blue,theme-pink',
        ]);

        GuestNote::create([
            'session_id'  => $sessionId,
            'author_name' => $validated['author_name'] ?: 'Anonymous',
            'title'       => $validated['title'],
            'content'     => $validated['content'],
            'theme'       => $validated['theme'] ?? 'theme-yellow',
        ]);

        return redirect()->route('landing')
            ->with('success', 'Note kamu sudah dipublikasikan!');
    }
}
