# Fin

Laravel 12 + MySQL finance management system for Nepali office workflows with a React + TypeScript frontend layer, TailAdmin-inspired admin styling, Ant Design components, modular Laravel services, and shared-hosting-friendly deployment.

## Overview

This project is a practical MVP for tracking income, expenses, categories, reports, documents, announcements, users, and permissions.

It uses:

- Laravel 12
- React 19 + TypeScript + Vite
- Blade routing with React-powered guest and dashboard surfaces
- Tailwind CSS v4
- TailAdmin-inspired admin shell
- Ant Design
- Spatie Laravel Permission
- Laravel Excel
- L5 Swagger / OpenAPI
- Nepali date conversion support (AD to BS and BS to AD)

## Main Features

- Dashboard with React-powered summary cards, chart, announcements, and recent activity
- Auth with Laravel Breeze routes and a redesigned React-enhanced guest shell
- Roles and permissions for `admin` and `staff`
- Transaction CRUD with AD + BS date support
- Category CRUD
- Monthly, yearly, and category reports
- CSV / Excel import, export, and template download
- Document upload, download, and transaction attachment
- Announcement management
- Settings for organization details and mail notification behavior
- Basic API routes for users and transactions
- Swagger docs at `/api/documentation`
- Shared-hosting-friendly defaults

## Module Structure

Backend modules follow an `app/Modules/<Module>` pattern inspired by larger modular Laravel codebases:

- DTOs: `app/Modules/<Module>/DTOs`
- Controllers: `app/Modules/<Module>/Http/Controllers`
- Requests: `app/Modules/<Module>/Http/Requests`
- Services: `app/Modules/<Module>/Services`
- Routes: `app/Modules/<Module>/Routes`
- Views: `resources/views/modules`

Core domain models live in `app/Models`, while shared helpers/services live in `app/Support`.

## Local Setup

1. Install PHP and JS dependencies.

```bash
composer install
npm install
```

2. Copy environment and generate the app key.

```bash
cp .env.example .env
php artisan key:generate
```

3. Create the MySQL database.

```sql
CREATE DATABASE fin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. Update `.env` with your local database and mail settings.

5. Run migrations and seeders.

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan l5-swagger:generate
```

6. Start development.

```bash
php artisan serve
npm run dev
```

## Default Seeded Accounts

These come from `.env` / `.env.example` values and should be changed immediately for real use:

- Admin: `admin@fin.test` / `password`
- Staff: `staff@fin.test` / `password`

## Shared Hosting Deployment Guide

This app is designed to avoid Redis, Horizon, WebSockets, Supervisor, and queue workers.

### Recommended Runtime Choices

- `QUEUE_CONNECTION=sync`
- `CACHE_STORE=file`
- `SESSION_DRIVER=file`
- `FILESYSTEM_DISK=public`

### Deployment Steps

1. Upload the project files to your hosting account.

2. Point your domain or subdomain to the Laravel `public` directory.

If your host does not allow changing the document root:

- move the contents of `public/` into your web root
- update `index.php` paths carefully to point back to the project root

3. Create the MySQL database and user from cPanel.

4. Update `.env`:

- database connection
- app URL
- mail settings
- admin/staff seeded credentials if you want custom defaults before seeding

5. Run:

```bash
php artisan migrate --seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan l5-swagger:generate
```

6. Build frontend assets locally or on the server:

```bash
npm install
npm run build
```

### If `storage:link` Is Not Allowed

Use your host’s file manager to create a symlink from:

- `public/storage` -> `storage/app/public`

If symlinks are blocked entirely, copy uploaded public files manually as a fallback, but symlinks are preferred.

## Mail Setup

The project uses standard SMTP settings from `.env`.

Examples:

- Gmail SMTP
- cPanel mail server
- any normal SMTP provider

Important settings:

- `MAIL_MAILER=smtp`
- `MAIL_HOST=...`
- `MAIL_PORT=...`
- `MAIL_USERNAME=...`
- `MAIL_PASSWORD=...`
- `MAIL_FROM_ADDRESS=...`
- `MAIL_FROM_NAME=...`

Transaction notification emails are controlled from the Settings screen.

## Nepali Date Support

- Store transaction dates in A.D.
- Display B.S. where helpful
- Filter reports using both A.D. and B.S. inputs
- Format currency with `NPR`

## API and Swagger

- API prefix: `/api/v1`
- Docs route: `/api/documentation`
- Basic auth is used for the included API endpoints

After API changes, regenerate docs with:

```bash
php artisan l5-swagger:generate
```

## Playwright E2E

Playwright is configured for a simple login and dashboard smoke test.

Run:

```bash
npm run test:e2e
```

Notes:

- the command builds frontend assets first
- it starts `php artisan serve` automatically on `http://127.0.0.1:8000`
- it expects the local `.env` database to be migrated and seeded

## Branch Usage

- `main`: stable branch
- `dev`: active development branch

Suggested workflow:

1. work in `dev`
2. test and review
3. merge to `main` for stable releases

## CI

GitHub Actions is included in `.github/workflows/ci.yml`.

The workflow runs:

- `composer install`
- `php artisan test`
- `npm ci`
- `npm run typecheck`
- `npm run build`

## Useful Commands

```bash
php artisan migrate:fresh --seed
php artisan l5-swagger:generate
php artisan test
npm run typecheck
npm run build
```
