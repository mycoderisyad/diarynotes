@extends('layouts.landing')

@section('title', 'Write a Note - DiaryNotes')

@push('styles')
    @vite('resources/css/notes.css')
@endpush

@section('content')
<div class="guest-container">
    <div class="guest-info">
        <svg class="guest-info-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        Sebagai guest, kamu bisa membuat 1 note publik. <a href="{{ route('register') }}">Daftar</a> untuk membuat lebih banyak.
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

    <form action="{{ route('guest_note_store') }}" method="POST" class="note-form">
        @csrf
        <input type="text"
               class="note-title"
               name="author_name"
               value="{{ old('author_name') }}"
               placeholder="Your name (optional)">

        <input type="text"
               class="note-title"
               name="title"
               value="{{ old('title') }}"
               placeholder="Untitled"
               autofocus>

        <textarea class="note-content"
                  name="content"
                  placeholder="Start writing here...">{{ old('content') }}</textarea>

        @include('partials.notes.theme-picker', ['selectedTheme' => 'theme-yellow', 'class' => 'guest-theme-group'])

        <div class="form-actions">
            <a href="{{ route('landing') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Publish</button>
        </div>
    </form>
</div>
@endsection
