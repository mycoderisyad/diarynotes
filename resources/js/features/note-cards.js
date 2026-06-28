document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-note-card-url]').forEach((card) => {
        card.addEventListener('click', () => {
            window.location.href = card.dataset.noteCardUrl;
        });

        card.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                window.location.href = card.dataset.noteCardUrl;
            }
        });
    });

    document.querySelectorAll('[data-stop-card-navigation]').forEach((element) => {
        element.addEventListener('click', (event) => event.stopPropagation());
    });
});
