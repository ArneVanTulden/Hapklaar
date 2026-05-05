# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# First-time setup (installs deps, generates APP_KEY, runs migrations, builds frontend)
composer run-script setup

# Start all dev processes concurrently (Laravel server, queue, logs, Vite HMR)
composer run-script dev

# Run tests
composer run-script test

# Lint/format PHP code
php artisan pint

# Frontend only
npm run dev      # Vite dev server
npm run build    # Production build
```

To run a single test file: `php artisan test tests/Feature/ExampleTest.php`

## Architecture

**Hapklaar** is a Laravel 13 recipe/cooking app. Stack: Laravel 13, Blade + Livewire 4, Alpine.js, Tailwind CSS 4, Vite, MySQL (default).

The database schema defines the full domain model across 18 migrations:

- **Core**: `recipes`, `categories`, `ingredients`, `recipe_ingredients`, `recipe_steps`, `nutrition_info`
- **Tagging**: `recipe_tags`, `diet_tags`
- **User activity**: `favorites`, `reviews`, `review_photos`, `shopping_lists`, `shopping_list_items`, `notification_preferences`
- **Scanning**: `scanner_sessions`, `scanned_detected` — barcode/product scanning feature

The application is in early development: migrations and infra are in place, but application routes, controllers, and views beyond the default welcome page have not yet been built. The `routes/web.php`, `app/Http/Controllers/`, and `resources/views/` are the primary areas for new feature work.

Bootstrap uses Laravel 13's `bootstrap/app.php` style (not `App\Http\Kernel`). Service providers register via `bootstrap/providers.php`.

Queue, cache, and sessions all default to the `database` driver. Mail defaults to `log` in development.

## Project Context

Hapklaar is a student cooking platform (Belgian market) with the following key features:
- Recipe discovery with filters (diet, calories, afwas-score, price)
- Step-by-step recipe videos with voice control ("spring naar het deel waar je knoflook snijdt")
- IJskast scanner: AI scans fridge photo and suggests recipes based on available ingredients
- Shopping list with Colruyt/Albert Heijn integration
- Review system with photos, replies and helpful votes
- Admin portal for recipe and review management



