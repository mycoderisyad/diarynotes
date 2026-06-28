@extends('layouts.app')

@section('title', 'Edit Note')

@push('styles')
    @vite('resources/css/notes.css')
@endpush

@section('content')
    @include('pages.notes.partials.editor-form', [
        'formAction' => route('notes_update', $note['id']),
        'httpMethod' => 'PUT',
        'submitLabel' => 'Update',
        'note' => $note,
        'editorContent' => $editorContent,
    ])
@endsection
