@extends('layouts.app')

@section('title', 'home')

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

    <form action="{{ route('notes_save') }}" method="POST" class="note-form">
        @csrf
        <input type="text" 
               class="note-title" 
               name="title" 
               value="{{ old('title') }}" 
               placeholder="Untitled"
               autofocus>

        <textarea class="note-content" 
                  name="content" 
                  placeholder="Start writing here...">{{ old('content') }}</textarea>

        <div class="form-actions">
            <a href="{{ route('home') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection