@php
    $selectedTheme = old('theme', $selectedTheme ?? 'theme-yellow');
    $themes = [
        'theme-yellow' => 'Yellow',
        'theme-peach' => 'Peach',
        'theme-mint' => 'Mint',
        'theme-blue' => 'Blue',
        'theme-pink' => 'Pink',
    ];
@endphp

<div class="theme-group {{ $class ?? '' }}">
    <label class="visibility-label">Theme / Card Color</label>
    <div class="theme-options">
        @foreach($themes as $value => $label)
            <label class="theme-option" title="{{ $label }}">
                <input type="radio" name="theme" value="{{ $value }}" {{ $selectedTheme === $value ? 'checked' : '' }}>
                <span class="theme-box theme-swatch-{{ str_replace('theme-', '', $value) }}"></span>
            </label>
        @endforeach
    </div>
</div>
