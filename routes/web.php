<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NoteController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        // login routes
        Route::get('/login', 'loginform')->name('login');
        Route::post('/login', 'loginHandler')->name('login_handler');

        // Password reset routes
        Route::get('/forgot-password', 'forgotform')->name('forgot');
        Route::post('/forgot-password', 'resetHandler')->name('send_reset_link');
        Route::post('/reset-password/{token}', 'resetForm')->name('reset_password');
        Route::get('/reset-password/{token}', 'showResetForm')->name('reset_password_form');

        // Register routes
        Route::get('/register', 'registerform')->name('register');
        Route::post('/register', 'registerHandler')->name('register_handler');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'Homepage'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
});

Route::controller(ContactController::class)->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact/send', 'sendContact')->name('contact_send');
});


Route::middleware('auth')->group(function () {
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings', 'settings')->name('settings');
        Route::post('/settings/update-password', 'updatePassword')->name('update_password');
        Route::delete('/settings/delete-account', 'deleteAccount')->name('delete_account');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(NoteController::class)->group(function () {
        Route::get('/notes/create', 'create')->name('notes_create');
        Route::post('/notes', 'notes')->name('notes_save');
        Route::get('/notes/{note}', 'show')->name('notes_show');
        Route::get('/notes/{note}/edit', 'edit')->name('notes_edit');
        Route::put('/notes/{note}', 'update')->name('notes_update');
        Route::delete('/notes/{note}', 'destroy')->name('notes_destroy');
    });
});
