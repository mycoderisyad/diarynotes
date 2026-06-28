import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/auth.css',
                'resources/css/landing.css',
                'resources/css/welcome.css',
                'resources/css/notes.css',
                'resources/css/pages/home.css',
                'resources/css/pages/settings.css',
                'resources/css/pages/note-show.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
