# Attendance Frontend API

This repository provides the employee‑facing API gateway for the attendance system.
It connects directly to the database managed by the
`attendance_backoffice_api` project and therefore contains **no** database
migrations. Any schema changes must be made in the backoffice project.

## Setup

1. Copy `.env.example` to `.env` and configure the shared database connection.
2. Install dependencies with `composer install` and `npm install`.
3. Clear cached configuration and routes before serving the application:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

> **Do not run** `php artisan migrate` in this repository.

Finally, start the development server with `php artisan serve`.

## Database usage

Because the schema lives elsewhere, models here are thin wrappers around the
existing tables. You can interact with the data via `php artisan tinker`, e.g.:

```php
App\Models\Employee::first();
App\Models\AttendanceLog::latest('work_date')->first();
App\Models\LeaveRequest::latest('id')->first();
```

A running PostgreSQL instance with the shared schema is required for the API and
for local testing.
