# PineCMS

> Security & Privacy-First Content Management System

A modern, lightweight CMS built with **Laravel Framework 12**, Vue 3, Inertia.js, and TailwindCSS. PineCMS prioritizes security, privacy, and developer experience while maintaining flexibility and performance.

## Features

- **Laravel MVC Architecture**: Clean, maintainable codebase using standard Laravel patterns
- **Full-Stack SPA**: Inertia.js for seamless SPA navigation without API complexity
- **Modern Tech Stack**: Laravel 12, Vue 3 Composition API, TailwindCSS 4
- **Security-Focused**: CSP, secure headers, activity logging, authentication tracking, role-based permissions
- **Media Management**: Spatie Media Library with image conversions and responsive images
- **Multilingual**: Dual-layer i18n (vue-i18n for UI, Laravel Translatable for content)
- **Automated Backups**: Scheduled database and file backups with retention policies
- **Developer Tools**: Telescope debugging, Larastan static analysis, comprehensive testing
- **Type-Safe**: PHP 8.3+ strict types, PHPUnit 12.4, Xdebug 3 code coverage
- **Authentication**: Laravel Sanctum for SPA + API token authentication

## Tech Stack

### Backend
- **Laravel Framework 12** - Full framework with MVC architecture
- **PHP 8.3+** with strict types and modern features
- **SQLite/MySQL** - Database (SQLite for development)
- **Laravel Sanctum** - SPA and API authentication
- **Laravel Telescope** - Debugging and monitoring (development)

### Spatie Packages
- **laravel-permission** - Role-based access control
- **laravel-activitylog** - User action tracking
- **laravel-backup** - Automated backups with retention
- **laravel-medialibrary** - Media management and image processing
- **laravel-translatable** - Multi-language content support
- **laravel-csp** - Content Security Policy
- **yaml-front-matter** - YAML + Markdown parsing

### Additional Backend
- **bepsvpt/secure-headers** - HTTP security headers
- **rappasoft/laravel-authentication-log** - Login tracking
- **intervention/image** - Image processing
- **league/commonmark** - Markdown parsing
- **monolog** - Advanced logging

### Frontend
- **Vue 3** with Composition API (`<script setup>`)
- **Inertia.js 2.0** - Modern monolith SPA framework
- **TailwindCSS 4** - Utility-first CSS framework
- **vue-i18n** - Frontend internationalization
- **Vite 7** - Lightning-fast build tool

### Development & Quality
- **PHPUnit 12.4** with Xdebug 3.4 for testing and code coverage
- **Larastan (PHPStan 2.0)** - Laravel-optimized static analysis
- **Laravel Pint** - Opinionated PHP code formatter
- **Rector** - Automated code refactoring
- **Infection** - Mutation testing (80% MSI, 90% covered MSI)
- **ESLint + Prettier** - JavaScript/Vue linting and formatting
- **Husky + lint-staged** - Pre-commit quality checks
- **Commitlint** - Conventional commit enforcement

## Requirements

- **PHP**: 8.3 or higher (PHP 8.4 recommended)
- **Node.js**: 20.0.0 or higher
- **npm**: 10.0.0 or higher
- **Composer**: 2.x
- **Database**: SQLite (dev), MySQL/PostgreSQL (production)
- **Extensions**: ext-gd or ext-imagick for image processing

## Installation

```bash
# Clone the repository
git clone https://github.com/k0r37k1/pinecms.git
cd pinecms

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Link storage for media library
php artisan storage:link

# Build frontend assets
npm run build

# Start development servers (two terminals)
npm run dev          # Terminal 1: Vite dev server
php artisan serve    # Terminal 2: Laravel dev server
```

Visit: http://localhost:8000

## Development

### Common Commands

**Frontend:**
```bash
npm run dev          # Start Vite dev server with HMR
npm run build        # Build for production
npm run preview      # Preview production build
npm run lint         # Lint JavaScript/Vue
npm run lint:fix     # Fix linting issues
npm run format       # Format code with Prettier
npm run type-check   # TypeScript type checking (if using TS)
```

**Backend:**
```bash
# Testing
php artisan test                    # Run all tests
php artisan test --coverage         # With code coverage
php artisan test --coverage --min=80  # Enforce 80% coverage
composer test:unit                  # Unit tests only
composer test:feature               # Feature tests only
composer test:mutation              # Mutation testing

# Code Quality
composer analyse                    # PHPStan static analysis
composer analyse:baseline           # Generate PHPStan baseline
composer format                     # Format code with Pint
composer format:check               # Check formatting
composer rector                     # Preview Rector refactorings
composer rector:fix                 # Apply Rector refactorings

# CI Pipeline
composer ci                         # Full CI pipeline
composer quality                    # Quick quality checks

# Security
composer security                   # Security vulnerability check

# Backups
php artisan backup:run              # Run backup manually
php artisan backup:list             # List all backups
php artisan backup:clean            # Cleanup old backups
php artisan backup:monitor          # Check backup health
```

**Development Tools:**
```bash
# Laravel Telescope
php artisan telescope:clear         # Clear Telescope entries
php artisan telescope:prune         # Prune old entries

# Debugging
php artisan serve                   # Start dev server
php artisan tinker                  # Interactive REPL
```

### Accessing Development Tools

- **Application**: http://localhost:8000
- **Telescope Dashboard**: http://localhost:8000/telescope (local only)
- **Coverage Reports**: `open coverage/html/index.html`

## Project Structure

```
pinecms/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Request handlers
│   │   ├── Middleware/        # HTTP middleware
│   │   └── Requests/          # Form request validation
│   ├── Models/                # Eloquent models
│   ├── Providers/             # Service providers
│   └── helpers.php            # Global helper functions
├── bootstrap/                 # Laravel bootstrap
├── config/                    # Configuration files
│   ├── backup.php            # Backup configuration
│   ├── media-library.php     # Media library settings
│   ├── permission.php        # Permissions configuration
│   └── telescope.php         # Telescope settings
├── database/
│   ├── factories/            # Model factories for testing
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── docs/                      # Project documentation
│   ├── DEVELOPMENT-TOOLS.md  # Telescope, Backup, Media guides
│   ├── LOCALIZATION.md       # Multi-language setup
│   └── TESTING.md            # Comprehensive testing guide
├── public/                    # Web root
│   ├── build/                # Compiled assets (Vite)
│   └── storage/              # Public storage symlink
├── resources/
│   ├── css/                  # Stylesheets
│   │   └── app.css          # Main CSS (Tailwind)
│   ├── js/                   # JavaScript/Vue
│   │   ├── Components/      # Vue components
│   │   ├── Composables/     # Vue composables
│   │   ├── Pages/           # Inertia.js pages
│   │   ├── locales/         # i18n translations (en.json, de.json)
│   │   ├── app.js           # Main Vue app
│   │   └── i18n.js          # vue-i18n setup
│   ├── lang/                # Backend translations
│   └── views/               # Blade templates
│       └── app.blade.php    # Main Inertia layout
├── routes/
│   ├── web.php              # Web routes
│   ├── api.php              # API routes
│   └── console.php          # Console routes
├── storage/
│   ├── app/
│   │   ├── backups/         # Automated backups
│   │   ├── public/          # Public file storage
│   │   └── content/         # Content storage (future)
│   ├── framework/           # Framework caches
│   ├── logs/                # Application logs
│   └── phpstan/             # PHPStan cache
├── tests/
│   ├── Feature/             # Feature tests
│   ├── Unit/                # Unit tests
│   └── TestCase.php         # Base test class
├── .claude/                  # Claude Code configuration
├── .husky/                   # Git hooks
├── phpstan.neon             # PHPStan configuration
├── phpunit.xml              # PHPUnit configuration
├── rector.php               # Rector configuration
├── vite.config.js           # Vite configuration
└── composer.json            # PHP dependencies
```

## Configuration

### Environment Variables

Key environment variables in `.env`:

```env
# Application
APP_NAME="PineCMS"
APP_ENV=local
APP_DEBUG=true
APP_KEY=                    # Generate with: php artisan key:generate
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite        # sqlite, mysql, pgsql
DB_DATABASE=/absolute/path/to/database.sqlite

# Session & Cache
CACHE_DRIVER=file           # file, redis, memcached
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue
QUEUE_CONNECTION=sync       # sync, database, redis

# Mail (for notifications)
MAIL_MAILER=log             # log, smtp, sendmail
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Media Library
MEDIA_DISK=public           # public, s3
IMAGE_DRIVER=gd             # gd, imagick

# Backups
BACKUP_MAIL_TO=your@example.com
BACKUP_ARCHIVE_PASSWORD=    # Optional encryption

# Laravel Telescope (Development Only)
TELESCOPE_ENABLED=true      # Set to false in production

# Locale
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_SUPPORTED_LOCALES=en,de
```

## Architecture

### Laravel MVC Pattern

PineCMS follows standard Laravel MVC architecture:

```
Routes → Middleware → Controllers → Models → Database
           ↓              ↓
      Inertia.js      Events/Jobs
           ↓
     Vue Components
```

### Key Concepts

**Controllers**: Handle HTTP requests and return Inertia responses
```php
class PageController extends Controller
{
    public function index()
    {
        return inertia('Pages/Index', [
            'pages' => Page::all()
        ]);
    }
}
```

**Models**: Eloquent models with relationships and business logic
```php
class Page extends Model
{
    use HasMedia, HasTranslations, LogsActivity;

    protected $translatable = ['title', 'content'];
}
```

**Inertia Pages**: Vue components that receive data from controllers
```vue
<script setup>
defineProps({ pages: Array })
</script>

<template>
  <div v-for="page in pages" :key="page.id">
    {{ page.title }}
  </div>
</template>
```

**Events**: Decouple side effects from main logic
```php
// Dispatch
event(new PagePublished($page));

// Listen
class SendPublishNotification implements ShouldQueue
{
    public function handle(PagePublished $event)
    {
        // Send notification
    }
}
```

## Testing

### PHP Testing (PHPUnit 12.4)

```bash
# Run all tests
php artisan test

# With code coverage (requires Xdebug or PCOV)
php artisan test --coverage

# Enforce minimum coverage
php artisan test --coverage --min=80

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific test file
php artisan test tests/Unit/ExampleTest.php

# Parallel execution
php artisan test --parallel

# Generate HTML coverage report
vendor/bin/phpunit --coverage-html coverage/html
open coverage/html/index.html
```

### Mutation Testing

```bash
# Run mutation tests (min 80% MSI, 90% covered MSI)
composer test:mutation
```

### Code Coverage with Xdebug

The project includes Xdebug 3.4.5 for code coverage:

```bash
# Check Xdebug installation
php -v | grep Xdebug

# Run tests without coverage (faster)
XDEBUG_MODE=off php artisan test

# Run tests with coverage
php artisan test --coverage

# Generate all coverage formats
vendor/bin/phpunit --coverage-text \
                   --coverage-html coverage/html \
                   --coverage-clover coverage/clover.xml \
                   --coverage-cobertura coverage/cobertura.xml
```

See [docs/TESTING.md](docs/TESTING.md) for comprehensive testing guide.

## Security

PineCMS implements multiple security layers:

### Authentication & Authorization
- **Laravel Sanctum** - SPA and API token authentication
- **Spatie Permission** - Role-based access control (RBAC)
- **Authentication Log** - Track login attempts and devices

### Security Headers
- **Content Security Policy (CSP)** - Prevent XSS attacks
- **HTTP Security Headers** - HSTS, X-Frame-Options, etc.
- **CORS** - Cross-origin resource sharing configuration

### Activity Tracking
- **Activity Log** - Track all user actions
- **Authentication Log** - Monitor login history
- **Event System** - Audit trail for sensitive operations

### Input/Output Protection
- **Form Request Validation** - Laravel validation rules
- **Blade Auto-escaping** - XSS protection in templates
- **SQL Injection Protection** - Eloquent query builder
- **CSRF Protection** - Laravel CSRF middleware

### Media Security
- **File Upload Validation** - MIME type and size checks
- **Image Optimization** - Strip EXIF data
- **Secure Storage** - Private storage for sensitive files

## Localization

PineCMS supports multi-language content and UI:

### Frontend (UI)
- **vue-i18n** for interface translations
- Translations in `resources/js/locales/`

### Backend (Content)
- **Laravel Translatable** for database content
- Automatic locale detection from session
- SetLocale middleware for request handling

See [docs/LOCALIZATION.md](docs/LOCALIZATION.md) for detailed setup guide.

## Development Tools

### Laravel Telescope
Debugging and monitoring dashboard for development.

Access: http://localhost:8000/telescope

Features:
- Request/Response inspection
- Database queries with execution time
- Event monitoring
- Job tracking
- Exception logging
- Cache operations

### Automated Backups
Scheduled backups for database and files.

```bash
# Manual backup
php artisan backup:run

# Database only
php artisan backup:run --only-db

# Files only
php artisan backup:run --only-files

# Schedule in routes/console.php
Schedule::command('backup:run')->daily()->at('01:00');
```

### Media Library
Associate files with Eloquent models, with automatic image conversions.

```php
// Upload media
$page->addMedia($request->file('image'))
    ->toMediaCollection('images');

// Get media URL
$url = $page->getFirstMediaUrl('images');

// Define conversions
public function registerMediaConversions(?Media $media = null): void
{
    $this->addMediaConversion('thumb')
        ->width(300)
        ->height(200)
        ->sharpen(10);
}
```

See [docs/DEVELOPMENT-TOOLS.md](docs/DEVELOPMENT-TOOLS.md) for complete guides.

## Git Workflow

### Commit Convention

This project uses **Conventional Commits** enforced by Commitlint:

```
<type>(<optional scope>): <description>

[optional body]

[optional footer]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `docs`: Documentation changes
- `style`: Code style changes (formatting, semicolons)
- `chore`: Maintenance tasks
- `perf`: Performance improvements
- `ci`: CI/CD changes
- `build`: Build system changes
- `revert`: Revert previous commit

**Examples:**
```bash
feat: add user profile page
fix: resolve login redirect issue
refactor: simplify authentication logic
docs: update installation guide
test: add media upload tests
```

### Pre-commit Hooks

Husky + lint-staged run automatically before each commit:
- Laravel Pint (PHP formatting)
- ESLint (JavaScript linting)
- Prettier (JavaScript formatting)

## CI/CD

The project includes composer scripts for CI pipelines:

```bash
# Full CI pipeline
composer ci
# Runs: format:check, analyse, deptrac, test:coverage, test:mutation, security

# Quick quality checks
composer quality
# Runs: format, analyse, deptrac, test
```

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  tests:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: mbstring, xml, pcov
          coverage: pcov

      - name: Install Dependencies
        run: composer install --prefer-dist --no-progress

      - name: Run Tests
        run: php artisan test --coverage --min=80
```

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feat/your-feature`
3. Make your changes following the coding standards
4. Run quality checks: `composer quality && npm run quality`
5. Commit using conventional commits: `git commit -m "feat: add new feature"`
6. Push to your fork: `git push origin feat/your-feature`
7. Submit a pull request

## Documentation

- **[TESTING.md](docs/TESTING.md)** - Comprehensive testing guide
- **[DEVELOPMENT-TOOLS.md](docs/DEVELOPMENT-TOOLS.md)** - Telescope, Backup, Media Library
- **[LOCALIZATION.md](docs/LOCALIZATION.md)** - Multi-language setup

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Copyright (c) 2025 [k0r37k1.dev](https://k0r37k1.dev)

## Acknowledgments

- Built with [Laravel Framework](https://laravel.com/)
- UI powered by [Vue 3](https://vuejs.org/), [Inertia.js](https://inertiajs.com/), and [TailwindCSS](https://tailwindcss.com/)
- Security and utilities by [Spatie](https://spatie.be/)
- Testing with [PHPUnit](https://phpunit.de/) and [Xdebug](https://xdebug.org/)
- Static analysis with [Larastan](https://github.com/larastan/larastan)

---

**Made with ❤️ by [k0r37k1.dev](https://k0r37k1.dev)**
