@extends('layouts.app')

@section('title', $pageTitle)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/notes/styles.css') }}">
@endpush

@section('content')
<div class="note-container">
    <div class="note-header">
        <a href="{{ route('home') }}" class="back-button">
            ← Back
        </a>
    </div>

    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('notes_update', $note['id']) }}" method="POST" class="note-form">
        @csrf
        @method('PUT')
        <input type="text" 
               class="note-title" 
               name="title" 
               value="{{ old('title', $note['title']) }}" 
               placeholder="Untitled"
               autofocus>

        <textarea class="note-content" 
                  name="content" 
                  placeholder="Start writing here...">{{ old('content', $note['content']) }}</textarea>

        <div class="form-actions">
            <a href="{{ route('home') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection