# PineCMS

> Security & Privacy-First Content Management System

A modern, lightweight CMS built with **Laravel Framework 12**, Vue 3, Inertia.js, and TailwindCSS. PineCMS prioritizes security, privacy, and developer experience while maintaining flexibility and performance.

## Features

- **🏗️ Modern Stack**: Laravel 12, Vue 3 Composition API, Inertia.js 2.0, TailwindCSS 4, Vite 7
- **🔒 Security-First**: CSP, secure headers, activity logging, authentication tracking, RBAC (Spatie Permission)
- **📦 Media Management**: Spatie Media Library with image conversions and responsive images
- **🌍 Multilingual**: Dual i18n (vue-i18n for UI, Laravel Translatable for content)
- **💾 Automated Backups**: Scheduled database/file backups with retention policies (Spatie Backup)
- **🔍 Developer Tools**: Laravel Telescope debugging, Laravel Debugbar, Larastan static analysis
- **✅ Quality Assurance**: PHPUnit 12.4, Xdebug 3.4, Mutation Testing (Infection), Rector, Deptrac
- **🎨 Code Standards**: Laravel Pint, ESLint, Prettier, Stylelint, Husky pre-commit hooks

## Tech Stack

### Backend
- **PHP 8.3+** with strict types | **Laravel Framework 12** | **SQLite/MySQL**
- **Laravel Sanctum** (SPA + API auth) | **Monolog** (logging) | **Beberlei Assert** (validation)

### Spatie Ecosystem
- **laravel-permission** (RBAC) | **laravel-activitylog** (audit trail)
- **laravel-backup** (automated backups) | **laravel-medialibrary** (media management)
- **laravel-translatable** (multi-language) | **laravel-csp** (Content Security Policy)
- **yaml-front-matter** (YAML + Markdown parsing)

### Security & Headers
- **bepsvpt/secure-headers** (HTTP security headers)
- **rappasoft/laravel-authentication-log** (login tracking)

### Content & Images
- **intervention/image** (image processing) | **league/commonmark** (Markdown parsing)

### Frontend
- **Vue 3** (Composition API) | **Inertia.js 2.0** | **TailwindCSS 4** | **Vite 7**
- **vue-i18n** (i18n) | **Alpine.js** (progressive enhancement) | **Axios** (HTTP)
- **Chart.js** (charts) | **Tiptap** (rich text editor) | **Pinia** (state management)
- **@vueuse/core** (Vue composables) | **@mdi/js** (Material Design Icons)

### Development & Quality Tools

**Testing:**
- **PHPUnit 12.4** (unit/feature tests) | **Xdebug 3.4** (code coverage)
- **Infection** (mutation testing: 80% MSI, 90% covered MSI)
- **Vitest** (JS/TS testing) | **Playwright** (E2E testing)

**Static Analysis & Linting:**
- **Larastan (PHPStan 2.0)** (Laravel-optimized analysis) | **Rector** (automated refactoring)
- **Deptrac** (architecture enforcement) | **ESLint** (JS/Vue linting) | **Stylelint** (CSS linting)

**Formatters:**
- **Laravel Pint** (PHP) | **Prettier** (JS/TS/CSS) | **prettier-plugin-tailwindcss** (Tailwind class sorting)

**Development:**
- **Laravel Telescope** (debugging) | **Laravel Debugbar** (profiling) | **Spatie Ray** (debugging)
- **Husky + lint-staged** (pre-commit hooks) | **Commitlint** (conventional commits)

## Requirements

- **PHP**: 8.3+ (PHP 8.4 recommended)
- **Node.js**: 20.0.0+
- **npm**: 10.0.0+
- **Composer**: 2.x
- **Database**: SQLite (dev), MySQL/PostgreSQL (production)
- **Extensions**: ext-gd or ext-imagick (image processing)

## Quick Start

```bash
# Clone & Install
git clone https://github.com/k0r37k1/pinecms.git
cd pinecms
composer install
npm install

# Setup
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link

# Build & Run
npm run build                # Production build
npm run dev                  # Vite dev server (Terminal 1)
php artisan serve            # Laravel dev server (Terminal 2)
```

Visit: **http://localhost:8000**

## Essential Commands

### Testing & Coverage
```bash
php artisan test                       # All tests
php artisan test --coverage --min=80   # With coverage enforcement
composer test:mutation                 # Mutation testing (Infection)
npm run test                          # Vitest (JS/TS)
npm run test:e2e                      # Playwright E2E tests
```

### Code Quality
```bash
composer analyse                # PHPStan static analysis
composer format                 # Pint PHP formatting
composer rector:fix            # Apply Rector refactorings
composer deptrac               # Architecture validation
npm run lint:fix               # Fix ESLint + Prettier
npm run lint:css:fix           # Fix Stylelint
composer ci                    # Full CI pipeline
```

### Development
```bash
php artisan telescope:clear    # Clear Telescope data
php artisan backup:run         # Manual backup
php artisan tinker            # Interactive REPL
```

## Project Structure

```
pinecms/
├── app/
│   ├── Http/Controllers/          # Request handlers
│   ├── Http/Middleware/           # HTTP middleware
│   ├── Http/Requests/             # Form validation
│   ├── Models/                    # Eloquent models
│   ├── Providers/                 # Service providers
│   └── helpers.php                # Global helpers
├── config/                        # Configuration files
│   ├── backup.php                # Spatie Backup
│   ├── media-library.php         # Spatie Media Library
│   ├── permission.php            # Spatie Permission
│   └── telescope.php             # Laravel Telescope
├── database/
│   ├── factories/                # Model factories
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── docs/                          # Project documentation
│   ├── TESTING.md                # Testing guide
│   ├── DEVELOPMENT-TOOLS.md      # Telescope, Backup, Media
│   └── LOCALIZATION.md           # Multi-language setup
├── resources/
│   ├── css/app.css               # TailwindCSS
│   ├── js/                       # Vue 3 + Inertia
│   │   ├── Components/          # Vue components
│   │   ├── Composables/         # Vue composables
│   │   ├── Pages/               # Inertia pages
│   │   ├── locales/             # i18n (en.json, de.json)
│   │   ├── app.js               # Main Vue app
│   │   └── i18n.js              # vue-i18n setup
│   ├── lang/                     # Backend translations
│   └── views/app.blade.php       # Inertia layout
├── routes/
│   ├── web.php                   # Web routes
│   ├── api.php                   # API routes
│   └── console.php               # Console routes
├── storage/
│   ├── app/backups/              # Spatie Backup storage
│   ├── framework/                # Laravel caches
│   ├── logs/                     # Application logs
│   └── phpstan/                  # Static analysis cache
├── tests/
│   ├── Feature/                  # Feature tests
│   ├── Unit/                     # Unit tests
│   └── TestCase.php              # Base test class
├── .claude/                       # Claude Code config
├── .husky/                        # Git hooks
├── phpstan.neon                   # PHPStan config
├── phpunit.xml                    # PHPUnit config
├── rector.php                     # Rector config
├── deptrac.yaml                   # Deptrac config
├── infection.json5                # Infection config
├── eslint.config.js               # ESLint 9 flat config
├── postcss.config.js              # PostCSS config
├── tailwind.config.js             # Tailwind config
├── vite.config.js                 # Vite config
└── composer.json                  # PHP dependencies
```

## Configuration

### Key Environment Variables

```env
APP_NAME="PineCMS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

TELESCOPE_ENABLED=true

APP_LOCALE=en
APP_SUPPORTED_LOCALES=en,de
```

See `.env.example` for complete configuration options.

## Development Tools Access

- **Application**: http://localhost:8000
- **Telescope Dashboard**: http://localhost:8000/telescope (local only)
- **Debugbar**: Enabled automatically in local environment
- **Coverage Reports**: `open coverage/html/index.html` (after running tests with coverage)

## Documentation

Detailed guides available in `docs/`:

- **[TESTING.md](docs/TESTING.md)** - Comprehensive testing guide (PHPUnit, Mutation Testing, Coverage)
- **[DEVELOPMENT-TOOLS.md](docs/DEVELOPMENT-TOOLS.md)** - Telescope, Backup, Media Library guides
- **[LOCALIZATION.md](docs/LOCALIZATION.md)** - Multi-language setup (vue-i18n + Laravel Translatable)

## Git Workflow

### Commit Convention (Enforced by Commitlint)

```
<type>(<scope>): <description>
```

**Types**: `feat`, `fix`, `refactor`, `test`, `docs`, `style`, `chore`, `perf`, `ci`, `build`, `revert`

**Examples**:
```bash
feat: add user profile page
fix: resolve login redirect issue
docs: update installation guide
```

### Pre-commit Hooks

Automatically run via Husky + lint-staged:
- ✅ Laravel Pint (PHP formatting)
- ✅ ESLint + Prettier (JS/Vue)
- ✅ Stylelint (CSS)

## Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feat/your-feature`
3. Follow coding standards and commit conventions
4. Run quality checks: `composer quality && npm run quality`
5. Submit pull request

## License

MIT License - see [LICENSE](LICENSE) file for details.

Copyright (c) 2025 [k0r37k1.dev](https://k0r37k1.dev)

---

**Built with** [Laravel](https://laravel.com/) • [Vue 3](https://vuejs.org/) • [Inertia.js](https://inertiajs.com/) • [TailwindCSS](https://tailwindcss.com/)
