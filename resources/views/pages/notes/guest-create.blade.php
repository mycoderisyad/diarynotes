@extends('layouts.landing')

@section('title', 'Write a Note - DiaryNotes')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/notes/styles.css') }}">
<style>
    .guest-container { max-width: 680px; margin: 100px auto 60px; padding: 0 24px; }
    .guest-info { background: var(--color-info-bg, #e8f4fd); border: 1px solid var(--color-info-border, #b3d7f0); border-radius: 8px; padding: 16px; margin-bottom: 24px; font-size: 14px; color: var(--color-text-secondary, #555); }
</style>
@endpush

@section('content')
<div class="guest-container">
    <div class="guest-info">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
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

        <div class="theme-group" style="margin-top: 4px;">
            <label class="visibility-label" style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; color: var(--color-text);">Theme / Card Color</label>
            <div class="theme-options" style="display: flex; gap: 8px;">
                <label class="theme-option" title="Yellow" style="cursor: pointer;">
                    <input type="radio" name="theme" value="theme-yellow" {{ old('theme', 'theme-yellow') === 'theme-yellow' ? 'checked' : '' }} style="display: none;">
                    <span class="theme-box" style="display: inline-block; width: 36px; height: 36px; border-radius: 50%; background-color: #ffd27d; border: 2px solid transparent; transition: all 0.2s;"></span>
                </label>
                <label class="theme-option" title="Peach" style="cursor: pointer;">
                    <input type="radio" name="theme" value="theme-peach" {{ old('theme') === 'theme-peach' ? 'checked' : '' }} style="display: none;">
                    <span class="theme-box" style="display: inline-block; width: 36px; height: 36px; border-radius: 50%; background-color: #ffa882; border: 2px solid transparent; transition: all 0.2s;"></span>
                </label>
                <label class="theme-option" title="Mint" style="cursor: pointer;">
                    <input type="radio" name="theme" value="theme-mint" {{ old('theme') === 'theme-mint' ? 'checked' : '' }} style="display: none;">
                    <span class="theme-box" style="display: inline-block; width: 36px; height: 36px; border-radius: 50%; background-color: #d1f49b; border: 2px solid transparent; transition: all 0.2s;"></span>
                </label>
                <label class="theme-option" title="Blue" style="cursor: pointer;">
                    <input type="radio" name="theme" value="theme-blue" {{ old('theme') === 'theme-blue' ? 'checked' : '' }} style="display: none;">
                    <span class="theme-box" style="display: inline-block; width: 36px; height: 36px; border-radius: 50%; background-color: #9ee1ff; border: 2px solid transparent; transition: all 0.2s;"></span>
                </label>
                <label class="theme-option" title="Pink" style="cursor: pointer;">
                    <input type="radio" name="theme" value="theme-pink" {{ old('theme') === 'theme-pink' ? 'checked' : '' }} style="display: none;">
                    <span class="theme-box" style="display: inline-block; width: 36px; height: 36px; border-radius: 50%; background-color: #ffafc5; border: 2px solid transparent; transition: all 0.2s;"></span>
                </label>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('landing') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Publish</button>
        </div>
    </form>
</div>
@endsection
