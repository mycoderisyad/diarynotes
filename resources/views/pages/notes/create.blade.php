@extends('layouts.app')

@section('title', 'Create Note')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/notes/styles.css') }}">
@endpush

@section('content')
    @include('pages.notes.partials.editor-form', [
        'formAction' => route('notes_save'),
        'httpMethod' => 'POST',
        'submitLabel' => 'Save',
        'editorContent' => '<p><br></p>',
    ])
@endsection

@push('scripts')
    @include('pages.notes.partials.editor-script')
@endpush
