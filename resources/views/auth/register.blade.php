@extends('layouts.auth')
@section('title', 'Register')
@section('content')
<form class="auth-form" action="{{ route('register_handler') }}" method="POST">
    @csrf
    <h2>Register</h2>

    <div class="form-group">
        <div class="input-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            <input type="text" id="name" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
        </div>
        @error('name')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <div class="input-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            <input type="text" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required>
        </div>
        @error('username')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <div class="input-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        @error('password')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary auth-submit-spaced">
        Register 
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </button>
</form>
<div class="auth-footer">
    Already have an account? <a href="{{ route('login') }}">Sign In</a>
</div>
@endsection
