<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\GuestNoteController;

// Landing page
Route::get('/', [HomeController::class, 'landing'])->name('landing');

// Guest note
Route::get('/try', [GuestNoteController::class, 'create'])->name('guest_note_create');
Route::post('/try', [GuestNoteController::class, 'store'])->name('guest_note_store');

// Public notes page
Route::get('/public-notes', [HomeController::class, 'publicNotes'])->name('public_notes');
Route::get('/public-notes/{source}/{note}/audio', [HomeController::class, 'streamPublicNoteAudio'])->name('public_notes_audio');
Route::get('/public-notes/{source}/{note}', [HomeController::class, 'showPublicNote'])->name('public_notes_show');
Route::get('/note-media/{note}/{file}', [NoteController::class, 'mediaStream'])->name('note_media');

// Auth (guest only)
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'loginform')->name('login');
        Route::post('/login', 'loginHandler')->name('login_handler');
        Route::get('/register', 'registerform')->name('register');
        Route::post('/register', 'registerHandler')->name('register_handler');
    });
});

// Authenticated
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'homepage'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings', 'settings')->name('settings');
        Route::post('/settings/update-password', 'updatePassword')->name('update_password');
        Route::delete('/settings/delete-account', 'deleteAccount')->name('delete_account');
    });

    Route::controller(NoteController::class)->group(function () {
        Route::get('/notes/create', 'create')->name('notes_create');
        Route::post('/notes', 'notes')->name('notes_save');
        Route::get('/notes/{note}/audio', 'audioStream')->name('notes_audio');
        Route::get('/notes/{note}', 'show')->name('notes_show');
        Route::get('/notes/{note}/edit', 'edit')->name('notes_edit');
        Route::put('/notes/{note}', 'update')->name('notes_update');
        Route::delete('/notes/{note}', 'destroy')->name('notes_destroy');
    });
});
