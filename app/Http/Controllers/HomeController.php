<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private function getUserNotesPath()
    {
        return 'notes/' . Auth::id() . '/notes.json';
    }

    public function Homepage()
    {
        $notes = [];
        $path = $this->getUserNotesPath();

        if (Storage::exists($path)) {
            $notes = json_decode(Storage::get($path), true) ?? [];
        
            usort($notes, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }

        return view('pages.home', compact('notes'));
    }

    public function about()
    {
        return view('pages.about', [
            'pageTitle' => 'About'
        ]);
    }
}