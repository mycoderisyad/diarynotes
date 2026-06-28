document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-forgot-password-demo]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            window.alert('This demo does not support password recovery since email is not used.');
        });
    });
});
