@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
@push('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
@endpush
    <form class="form" method="POST" action="{{ route('reset_password', ['token' => $token]) }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required />
        @error('email')
            <span class="error">{{ $message }}</span>
        @enderror

        <div class="input-wrapper">
            <input type="password" id="password" name="password" placeholder="New Password" required />
            <span class="eye-icon" onclick="togglePassword()">👁️</span>
        </div>
        @error('password')
            <span class="error">{{ $message }}</span>
        @enderror

        <div class="input-wrapper">
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required />
            <span class="eye-icon" onclick="togglePassword()"></span>
        </div>
        @error('password_confirmation')
            <span class="error">{{ $message }}</span>
        @enderror

        <button type="submit" class="submit-button">Reset Password</button>
    </form>
@endsection