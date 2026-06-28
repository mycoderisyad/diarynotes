@extends('layouts.app')

@section('title', 'Create Note')

@push('styles')
    @vite('resources/css/notes.css')
@endpush

@section('content')
    @include('pages.notes.partials.editor-form', [
        'formAction' => route('notes_save'),
        'httpMethod' => 'POST',
        'submitLabel' => 'Save',
        'editorContent' => '<p><br></p>',
    ])
@endsection
