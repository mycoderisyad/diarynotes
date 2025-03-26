@extends('layouts.app')

@section('title', $pageTitle)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/notes/show.css') }}">
@endpush

@section('content')
<div class="note-container">
    <div class="note-header">
        <a href="{{ route('home') }}" class="back-button">
            <-Back
        </a>
    </div>

    <div class="note-view">
        <h1 class="note-title">{{ $note['title'] }}</h1>
        <div class="note-metadata">
            Created on {{ \Carbon\Carbon::parse($note['created_at'])->format('M d, Y H:i') }} WIB
        </div>
        <div class="note-content">
            {!! nl2br(e($note['content'])) !!}
        </div>
    </div>

    <div class="note-actions">
        <a href="{{ route('notes_edit', $note['id']) }}" class="edit-button">Edit Note</a>
        <form action="{{ route('notes_destroy', $note['id']) }}" method="POST" class="delete-button" 
        onsubmit="return confirm('Are you sure you want to delete this note?')">
      @csrf
      @method('DELETE')
      <button type="submit" class="note-card-action delete-btn">Delete</button>
        </form>
    </div>
</div>
@endsection