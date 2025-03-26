@extends('layouts.app')

@section('title', 'My Notes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home/styles.css') }}">
@endpush

@section('content')
<div class="note-container">
    <div class="home-header">
    </div>
    @if(count($notes) > 0)
        <div class="notes-grid">
            @foreach($notes as $note)
                <div class="note-card">
                    <div class="note-card-content">
                        <h3 class="note-card-title">{{ $note['title'] }}</h3>
                        <p class="note-card-text">{{ Str::limit(strip_tags($note['content']), 150) }}</p>
                    </div>
                    <div class="note-card-footer">
                        <span>{{ \Carbon\Carbon::parse($note['created_at'])->format('M d, Y') }}</span>
                        <div class="note-card-actions">
                            <a href="{{ route('notes_show', $note['id']) }}" class="note-card-action">View</a>
                            <a href="{{ route('notes_edit', $note['id']) }}" class="note-card-action">Edit</a>
                            <form action="{{ route('notes_destroy', $note['id']) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this note?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="note-card-action delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <p class="empty-state-text">You don't have any notes yet.</p>
            <a href="{{ route('notes_create') }}" class="btn btn-primary">Create your first note</a>
        </div>
    @endif
</div>
@endsection
