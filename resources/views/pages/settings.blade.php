@extends('layouts.app')

@section('title', 'Settings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/settings/styles.css') }}">
@endpush

@section('content')
<div class="settings-container">
    <section class="hero-section">
        <h1>Settings</h1>
    </section>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h4>Update Password</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('update_password') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="current_password">Current Password</label>
                    <input type="password" class="form-input" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password">New Password</label>
                    <input type="password" class="form-input" id="new_password" name="new_password" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-input" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>

    <div class="card danger-card">
        <div class="card-header">
            <h4>Delete Account</h4>
        </div>
        <div class="card-body">
            <p class="text-danger">Warning: This action cannot be undone. All your data will be permanently deleted.</p>
            <form method="POST" action="{{ route('delete_account') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                
                <div class="form-group">
                    <label class="form-label" for="password">Enter Your Password to Confirm</label>
                    <input type="password" class="input-delete" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-danger">Delete Account</button>
            </form>
        </div>
    </div>
</div>
@endsection