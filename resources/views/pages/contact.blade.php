@extends('layouts.app')

@section('title', 'Contact')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contact/styles.css') }}">
@endpush

@section('content')
<div class="contact-container">
    <section class="hero-section">
        <h1>Contact</h1>
        <p>We'd love to hear from you! Fill out the form below to get in touch.</p>
    </section>

    <section class="form-section">
        <form method="POST" action="{{ route('contact_send') }}">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select a Category</option>
                    <option value="question" {{ old('category') == 'question' ? 'selected' : '' }}>Question</option>
                    <option value="suggestion" {{ old('category') == 'suggestion' ? 'selected' : '' }}>Suggestion</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group other-category" style="display: none;">
                <label for="other_category">Specify Other Category</label>
                <input type="text" id="other_category" name="other_category" placeholder="Specify Category" value="{{ old('other_category') }}">
                @error('other_category')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Your Message" rows="5" required>{{ old('message') }}</textarea>
                @error('message')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="submit-button">Send Message</button>
        </form>
    </section>
</div>

@push('scripts')
    <script>
        document.getElementById('category').addEventListener('change', function() {
            const otherCategory = document.querySelector('.other-category');
            if (this.value === 'other') {
                otherCategory.style.display = 'block';
            } else {
                otherCategory.style.display = 'none';
            }
        });
    </script>
@endpush
@endsection
