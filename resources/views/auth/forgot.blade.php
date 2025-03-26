@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<form class="form" action="{{ route('send_reset_link') }}" method="POST">
    @csrf
    <input type="text" id="email" name="email" placeholder="Email Address"  name="email" 
    value="{{ old('email') }}" />
    @error('email')
        <span class="error">{{ $message }}</span>
    @enderror

    <button type="submit">Reset Password</button>
</form>
<div class="footer">
    Remember your password? <a href="{{ route('login') }}">Login</a>
</div>
@endsection