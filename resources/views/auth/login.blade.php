@extends('layouts.auth')

@section('title', 'Login')

@push('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
@endpush

@section('content')
<form class="form" action="{{ route('login_handler') }}" method="POST">
    @csrf
    <input type="text" id="login_id" name="login_id" placeholder="Username or Email" required />
    @error('login_id')
        <span class="error">{{ $message }}</span>
    @enderror

    <div class="input-wrapper">
        <input type="password" id="password" name="password" placeholder="Password" />
    </div>
    @error('password')
        <span class="error">{{ $message }}</span>
    @enderror

    <div class="remember-me">
        <label for="remember">
            <input type="checkbox" id="remember" name="remember">
            Remember Me
        </label>
    </div>

    <button type="submit">Sign In</button>
</form>

<div class="footer">
    Forgot your password? <a href="{{ route('forgot') }}">Reset Password</a>
    Don't have an account yet? <a href="{{ route('register') }}">Register</a>
</div>
@endsection