@extends('layouts.app')

@section('title', 'About Us')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/about/styless.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="about-container">
    <section class="hero-section">
        <h1>About</h1>
        <p>
            This simple website was created to complete an optional assignment for a dynamic web programming course. 
            We aim to provide a personal digital space to capture life's moments.
        </p>
    </section>
    <section class="mission-section">
        <h2>Mission</h2>
        <p>
            To provide a safe and intuitive platform for everyone to document their thoughts, memories, and experiences.
        </p>
        <div class="features-grid">
            <div class="feature-card">
                <img src="{{ asset('image/secure-icon.svg') }}" alt="Secure">
                <h3>Secure & Private</h3>
                <p>Your thoughts remain private and protected.</p>
            </div>
            <div class="feature-card">
                <img src="{{ asset('image/easy-icon.svg') }}" alt="Easy to Use">
                <h3>User Friendly</h3>
                <p>Simple and intuitive interface for all users.</p>
            </div>
            <div class="feature-card">
                <img src="{{ asset('image/access-icon.svg') }}" alt="Accessible">
                <h3>Always Available</h3>
                <p>Access your notes anytime, anywhere.</p>
            </div>
        </div>
    </section>
    <section class="Develover-section">
        <h2>Develover</h2>
        <div class="Dev-grid">
                <img src="{{ asset('image/team2.jpg') }}" alt="developer">
                <h3>Muhammad Risyad Raflan</h3>
                <p>Mahasiswa Universitas Siber Muhammadiyah</p>
        </div>
    </section>
    <section class="contact-cta">
        <h2>Contact</h2>
        <p>Have questions or suggestions? Feel free to reach out to me!</p>
        <a href="{{ route('contact') }}" class="cta-button">Get in Touch</a>
        <div class="social-media">
            <a href="https://instagram.com/mrraflann" target="_blank" class="social-icon">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://youtube.com/@RaflanGT" target="_blank" class="social-icon">
                <i class="fab fa-youtube"></i>
            </a>
            <a href="https://github.com/risyadraf" target="_blank" class="social-icon">
                <i class="fab fa-github"></i>
            </a>
        </div>
    </section>
</div>
@endsection
