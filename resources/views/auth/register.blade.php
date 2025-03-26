@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    @push('scripts')
        <script src="{{ asset('js/script.js') }}"></script>
    @endpush
    <form class="form" action="{{ route('register_handler') }}" method="POST">
        @csrf

        <input type="text" id="name" name="name" placeholder="Full Name" value="{{ old('name') }}"
            required />
        @error('name')
            <span class="error">{{ $message }}</span>
        @enderror
        <input type="email" id="email" name="email" placeholder="Email Address" value="{{ old('email') }}"
            required />
        @error('email')
            <span class="error">{{ $message }}</span>
        @enderror

        <input type="text" id="username" name="username" placeholder="Username" value="{{ old('username') }}"
            required />
        @error('username')
            <span class="error">{{ $message }}</span>
        @enderror

        <div class="input-wrapper">
            <input type="password" id="password" name="password" placeholder="Password" required />
            <span class="eye-icon" onclick="togglePassword()">👁️</span>
        </div>
        @error('password')
            <span class="error">{{ $message }}</span>
        @enderror

        <button type="submit">Register</button>
    </form>
    <div class="footer">
        Already have an account? <a href="{{ route('login') }}">Login</a>
    </div>
@endsection
