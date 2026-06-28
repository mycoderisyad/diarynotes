@php
    $selectedVisibility = old('visibility', $selectedVisibility ?? 'private');
@endphp

<div class="visibility-group">
    <label class="visibility-label">Visibility</label>
    <div class="visibility-options">
        <label class="visibility-option">
            <input type="radio" name="visibility" value="private" {{ $selectedVisibility === 'private' ? 'checked' : '' }}>
            <span class="option-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Private
            </span>
        </label>
        <label class="visibility-option">
            <input type="radio" name="visibility" value="public" {{ $selectedVisibility === 'public' ? 'checked' : '' }}>
            <span class="option-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                Public
            </span>
        </label>
    </div>
</div>
