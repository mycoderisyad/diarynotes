@extends('layouts.app')

@section('title', 'Edit Note')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/notes/styles.css') }}">
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

@push('scripts')
    @include('pages.notes.partials.editor-script')
@endpush
