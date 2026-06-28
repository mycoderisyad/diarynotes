# DiaryNotes

DiaryNotes is a Laravel 11 note-taking app with authentication, guest notes, public notes, rich text, image embeds, and audio uploads.

## Requirements

- PHP 8.2 or newer
- Composer
- PHP SQLite extensions enabled: `pdo_sqlite` and `sqlite3`

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create the local SQLite database file:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

Run migrations:

```bash
php artisan migrate
```

Start the application:

```bash
php artisan serve
```

## Database

The app uses SQLite by default:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

The SQLite file is local-only and ignored by Git.

## Storage

- Users, sessions, cache, jobs, and guest notes are stored in SQLite.
- Member note content, embedded media, and uploaded audio are stored under `storage/app/private/notes`.

## Tests

```bash
php artisan test
```
