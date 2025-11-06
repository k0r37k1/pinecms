# Repository Guidelines

## Project Overview

PineCMS is a hybrid CMS on Laravel 12 and Inertia/Vue 3, routing requests through `routes/` into `app/` services and rendering pages from `resources/`.

## Project Structure & Module Organization

Business logic lives in `app/`, configuration in `config/`, console jobs in `app/Console`. Front-end modules stay in `resources/js`, styles in `resources/css`, translations in `lang/`, assets in `public/build`. Factories and seeders sit in `database/`, docs in `docs/`, and tests in `tests/{Unit,Feature,E2E}`.

## Quick Start

```bash
git clone https://github.com/pinecms/pinecms.git
cd pinecms
cp .env.example .env
php artisan key:generate
composer install && npm install
php artisan migrate && php artisan serve
npm run dev
```

## Build, Test, and Development Commands

- `npm run dev` — Vite dev server (pair with `php artisan serve` or Sail).
- `npm run build` — Builds production assets.
- `composer test` — PHPUnit with coverage HTML in `coverage/`.
- `npm run test:coverage` — Vitest with V8 coverage thresholds.
- `npm run test:e2e` — Playwright flows in `tests/E2E`.
- `composer ci` — Pint, PHPStan, Deptrac, coverage, Infection, security scan.

## Software Engineering Principles

Apply these principles when designing and implementing features:

- **KISS (Keep It Simple)** - Prefer simple solutions over clever ones. Avoid over-engineering.
- **DRY (Don't Repeat Yourself)** - Extract repeated code into reusable functions/classes.
- **YAGNI (You Aren't Gonna Need It)** - Implement only what's currently needed, not what might be needed later.
- **Single Responsibility** - Each class/function should have one reason to change.
- **Open/Closed** - Entities should be open for extension, closed for modification.
- **Liskov Substitution** - Subtypes must be substitutable for their base types.
- **Interface Segregation** - Many specific interfaces are better than one general interface.
- **Dependency Inversion** - Depend on abstractions, not concrete implementations.

**Separation of Concerns:**

- Business logic → Service layer
- Data access → Repository/Model layer
- HTTP handling → Controller layer
- Presentation → View/Component layer
- Side effects → Event Listener layer

## Coding Style & Naming Conventions

Use Laravel Pint (`composer format`) for PHP; styles follow PSR-12 with 4-space indentation and snake_case database columns. Front-end code is formatted by Prettier with 2-space indentation and kebab-case Vue filenames (e.g. `user-table.vue`); TypeScript modules should export PascalCase symbols. Run `npm run lint` and `npm run lint:css` before pushing changes.

## Testing Guidelines

Scope PHPUnit classes to one behavior; share fixtures via `tests/TestCase.php`. Component tests live beside Vue sources as `.test.ts`/`.spec.ts` using Vitest (`tests/setup.js`). Extend Playwright journeys when navigation, auth, or publishing flows change, and uphold Infection thresholds (80/90).

## Commit & Pull Request Guidelines

Commits follow Conventional Commits (`feat:`, `fix:`, `docs:`) with imperative subject lines and optional scopes (`feat(auth):`). Squash small clean-ups rather than stacking noise commits. Pull requests should describe the change, reference related issues, note migrations or config updates, and attach screenshots or command output for UI or test changes. Before review, run `composer ci` and relevant npm scripts, confirm migrations roll back cleanly, and document env impacts.

**Review checklist:** tests pass, docs/locales updated, config impacts noted, evidence attached, secrets scrubbed.

## Storage Architecture

PineCMS uses a **hybrid storage approach**:

- **SQLite** for relational data (users, settings, metadata)
- **Flat-File** for content (Markdown files in `storage/content/`)

**Critical:** Content operations must handle both database records AND file system operations. Always maintain consistency between SQLite metadata and flat-file content.

## Security & Forbidden Patterns

- ❌ **NEVER use Laravel Hooks** - This project uses Events only (Laravel event system)
- ❌ **NEVER use `env()` directly** - ALWAYS use `config()` (after `config:cache`, `env()` returns null)
- ❌ **NEVER skip `declare(strict_types=1);`** in PHP files
- ❌ **NEVER use CommonJS `require`** - Use ES modules only
- ❌ **NEVER create inline styles** - Use TailwindCSS utility classes
- ❌ **NEVER commit `console.log`** - Remove debug statements before committing
- ❌ **NEVER delete files/code without approval** - Always request user approval first

## Protected Areas (Request Approval First)

- Database Migrations (schema changes need review)
- Security Code (authentication, authorization, encryption)
- Test Files (changes affect test validity)
- API Contracts (breaking changes affect consumers)
- Configuration Files (`.env`, config files with secrets)

## Security & Configuration Tips

Copy `.env.example` to `.env` and generate fresh keys before local development; never commit secrets. Prefer Laravel Sail or the Docker compose files in `docker/` for reproducible environments. Review `boost.json` and `config/security.php` when introducing third-party packages, and run `composer security` after dependency bumps.
