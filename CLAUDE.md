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

## Design

The Figma design uses a bold, maximalist student aesthetic:
- Colors: hot pink (#FF0066), lime green (#CCFF00), dark background
- Typography: Anton (display), heavy uppercase headings
- Components: recipe cards with afwas-score, diet tags, price per portion

## Stack Decisions

- **Laravel 13** + **Livewire 4** for server-driven reactivity
- **Alpine.js** for client-side UI only (modals, tabs, toggles, show/hide)
- **Tailwind CSS 4** utility-first, no separate SCSS
- **Edamam API** for nutrition data (calories, macros, vitamins)
- Rule of thumb: Livewire = server data, Alpine = visual/client-side only

## Database

Schema is based on a custom ERD designed from Figma screens.
Key design decisions:
- `afwas_score` (1-5) directly on `recipes`
- `avg_rating` and `review_count` are cached columns on `recipes`
- `nutrition_info` has 1:1 with `recipes`, stores Edamam data
- `scanned_detected.ingredient_id` is nullable (AI may detect unknown ingredients)
- `shopping_list_items.ingredient_id` is nullable (manual items allowed)
- Connection: MySQL (local via MySQL Workbench)
- Database name: `hapklaar`

## Figma Screenshots

All 20 design screens are in `docs/figma/`:
- `01-home.png` — landing page
- `02-ontdekken.png` — receptenlijst met filters
- `03-recept-stappen.png` — recept detail, stappen tab
- `04-recept-voeding.png` — recept detail, voedingswaarden tab
- `05-recept-reviews.png` — recept detail, reviews tab
- `06-ijskast-scanner.png` — AI scanner
- `07-ijskast-scanner-modal.png` — ingrediënt toevoegen modal
- `08-boodschappen.png` — boodschappenlijst
- `09-boodschappen-modal.png` — item toevoegen modal
- `10-profiel-favorieten.png` — profiel, favorieten tab
- `11-profiel-reviews.png` — profiel, reviews tab
- `12-profiel-instellingen.png` — profiel, instellingen tab
- `13-login.png` — inlogpagina
- `14-register.png` — registratiepagina
- `15-wachtwoord-vergeten.png` — wachtwoord reset
- `16-admin-dashboard.png` — admin dashboard
- `17-admin-recensies.png` — admin recensies beheer
- `18-admin-recepten.png` — admin recepten overzicht
- `19-admin-nieuw-recept.png` — admin nieuw recept formulier
- `20-admin-gebruikers.png` — admin gebruikersbeheer

## Stack Decisions

- **Laravel 13** + **Livewire 4** for server-driven reactivity
- **Alpine.js** for client-side UI only (modals, tabs, toggles, show/hide)
- **Tailwind CSS 4** utility-first, no separate SCSS
- **Edamam API** for nutrition data (calories, macros, vitamins)
- Rule of thumb: Livewire = server data, Alpine = visual/client-side only

### Strict styling rules
- **ONLY Tailwind utility classes** — no custom CSS, no SCSS, no style tags
- **NO** `@apply` except for very small reusable components in `app.css`
- **NO** inline `style=""` attributes
- All spacing, colors, typography via Tailwind classes directly in Blade files
- Custom colors are defined in `app.css` as Tailwind theme variables, not in separate files