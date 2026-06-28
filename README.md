# DiaryNotes

DiaryNotes is a Laravel 11 note-taking app. It supports member notes, guest public notes, private/public visibility, rich note content, image embeds, audio uploads, and public note browsing.

## Features

- Username-based login and registration
- Member dashboard for creating, editing, viewing, and deleting notes
- Private or public note visibility
- Guest note form for publishing one public note
- Public notes page with search, filter, sort, and detail view
- Rich editor with inline image/audio embeds and browser audio recording
- Account settings for password update and account deletion

## Stack

- Laravel 11
- PHP 8.2+
- SQLite by default
- Blade views
- Vite for frontend CSS and JavaScript
- Pest/PHPUnit tests

## Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- PHP SQLite extensions: `pdo_sqlite` and `sqlite3`

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate
```

Run the app:

```bash
php artisan serve
npm run dev
```

Build frontend assets:

```bash
npm run build
```

## Storage

- SQLite stores users, sessions, cache, jobs, and guest notes.
- Member note data and uploaded media are stored under `storage/app/private/notes`.
- Frontend source files live in `resources/css` and `resources/js`; Vite builds public assets into `public/build`.

## Tests

```bash
php artisan test
```
