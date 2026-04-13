# Campus Lost & Found Tracker

A full-stack Laravel application for reporting, tracking, and recovering lost or found items across a campus community.

The system supports authenticated reporting, item lifecycle tracking (Lost -> Found -> Claimed), image uploads with background optimization, ownership-based authorization, and a local browser Database UI for inspecting records.

## System Highlights

- Secure user registration and login.
- Dashboard with live statistics for total, Lost, Found, and Claimed items.
- Search and status filtering across item name, description, and location.
- Full item workflow: create, edit, claim, soft delete, and detail view.
- Item detail page with smart potential-match suggestions (Lost vs Found correlation).
- Up to 3 photos per item, validated and processed asynchronously into optimized WebP files.
- Claim audit support using `claimed_at` and optional `claimant_info`.
- Queued email notification to the reporting user when an item is marked as claimed.
- Admin-only Database UI (local environment) to browse tables and preview rows.

## Architecture

- Pattern: MVC + Service Layer.
- Controllers handle request/response flow.
- `ItemService` centralizes business logic for dashboard queries, create/update/claim/delete, photo handling, and smart matching.
- FormRequest classes (`StoreItemRequest`, `UpdateItemRequest`) encapsulate validation and post-validation checks.
- `ItemPolicy` enforces ownership-based actions.
- Queue jobs process heavy tasks in the background (`ProcessItemPhoto`, queued notifications).

## Technology Stack

- Backend: PHP 8.2+, Laravel 12.
- Frontend: Blade templates, Tailwind CSS, Vite, Alpine.js.
- Database: MySQL (current local setup), with SQLite/PostgreSQL compatibility in Laravel config.
- Image processing: `intervention/image`.
- Queue + Notifications: Laravel Queue and Notification system.
- Testing: PHPUnit via `php artisan test`.

## Data Model Overview

Core tables:

- `users`: account records.
- `items`: item metadata and workflow fields (`status`, `claimed_at`, `claimant_info`, `user_id`, soft deletes).
- `item_photos`: photo paths with sort order and soft deletes.

Key relationships:

- One user has many items.
- One item has many photos.

Status model:

- Allowed statuses: `Lost`, `Found`, `Claimed`.
- `claimed_at` is populated when status becomes `Claimed`.

## Local Setup

### 1. Prerequisites

- PHP 8.2 or newer.
- Composer.
- Node.js 18+ and npm.
- MySQL server (XAMPP, Herd, Laragon, or standalone MySQL).

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

Create your env file:

```bash
copy .env.example .env
```

Recommended MySQL configuration in `.env`:

```env
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_lostfound
DB_USERNAME=root
DB_PASSWORD=
ADMIN_EMAILS=admin@example.com
```

Then initialize the app:

```bash
php artisan key:generate
php artisan migrate
php artisan storage:link
```

## Running the Application

### Option A: Composer dev script (best on macOS/Linux)

```bash
composer run dev
```

### Option B: Windows-friendly startup (without Pail/pcntl)

Run these in separate terminals:

```bash
php artisan serve
```

```bash
php artisan queue:listen --tries=1 --timeout=0
```

```bash
npm run dev
```

App URL: `http://127.0.0.1:8000`

## Database UI (Browser)

Open:

`http://127.0.0.1:8000/database`

What it provides:

- Connection metadata (driver, database, table count).
- Table list with row and column counts.
- Table data preview with adjustable row limits.

Security note:

- This page is restricted to local environment (`APP_ENV=local`) and admin users only.
- Admin users are controlled by the comma-separated `ADMIN_EMAILS` setting in `.env`.

## Authorization Rules

- Only the item owner can edit or delete an item.
- Any authenticated user can claim an item that is not already claimed.
- Public users can browse items and open item detail pages.

## Validation Rules

- `item_name`, `description`, `location`, and `status` are required.
- Photo upload supports JPEG/JPG/PNG/WEBP.
- Maximum 3 photos per item.
- Maximum 10MB per photo.
- Update validation ensures removed photo IDs belong to the current item.

## Testing

Run all tests:

```bash
php artisan test
```

Current suite covers:

- Dashboard response.
- Item creation.
- Validation errors.
- Soft-delete behavior.
- Photo upload and photo replacement flow.

## Useful Commands

```bash
php artisan migrate:status
php artisan route:list --name=database
php artisan tinker --execute="dump(DB::select('SHOW TABLES'));"
```

## Troubleshooting

- If `composer run dev` fails on Windows due `pcntl`/Pail, use the Windows-friendly startup commands above.
- If image URLs do not render, run `php artisan storage:link`.
- If queued tasks are not processing, ensure a queue worker is running.
- If `php artisan db:show` fails on some local MySQL setups, use the Database UI route (`/database`) or Tinker table inspection.

## Project Identity

- Project: Campus Lost & Found Tracker.
- Developer: Kamwanga Rahiim.
- Registration Number: JAN23/BSE/2177U.
