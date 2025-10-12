# PineCMS

> Security & Privacy-First Flat-File Hybrid CMS

A modern, lightweight content management system built with Laravel 12 (Illuminate Components), Vue 3, Inertia.js, and TailwindCSS. PineCMS prioritizes security, privacy, and performance while maintaining a flexible flat-file architecture.

## Features

- **Flat-File First**: Content stored as YAML Front Matter + Markdown for version control and portability
- **Event-Driven Architecture**: Clean, maintainable codebase using Laravel Events instead of hooks
- **Modern Tech Stack**: Vue 3 Composition API, Inertia.js for SPA navigation, Alpine.js for interactivity
- **Security-Focused**: Content Security Policy, secure headers, activity logging, authentication tracking
- **Developer-Friendly**: Hot module replacement, TypeScript support, comprehensive testing suite
- **Rich Text Editor**: TipTap editor with extensive formatting extensions
- **Multilingual**: Built-in i18n support with Vue i18n
- **Type-Safe**: PHP 8.3 strict types, TypeScript for frontend

## Tech Stack

### Backend
- **Laravel 12** (Illuminate Components only, no full framework)
- **PHP 8.3** with strict types
- **Spatie Packages**: Permission, ActivityLog, CSP, YAML Front Matter
- **Firebase PHP-JWT** for API authentication
- **Intervention Image** for media processing
- **League CommonMark** for Markdown parsing

### Frontend
- **Vue 3** with Composition API (`<script setup>`)
- **Inertia.js 2.0** for seamless SPA navigation
- **Alpine.js** for simple interactivity
- **TailwindCSS 4** with utility-first styling
- **TipTap** rich text editor
- **Pinia 3** for state management
- **Vue Router** for client-side routing
- **Chart.js** for data visualization

### Development
- **Vite 7** for lightning-fast builds
- **Husky 9 + lint-staged** for pre-commit quality checks
- **Commitlint** for conventional commits
- **ESLint + Prettier** for JavaScript/TypeScript
- **PHPStan + Rector** for PHP static analysis
- **Vitest + Playwright** for comprehensive testing
- **Infection** for mutation testing

## Requirements

- **PHP**: 8.3 or higher
- **Node.js**: 20.0.0 or higher
- **npm**: 10.0.0 or higher
- **Composer**: 2.x
- **Git**: For version control

## Installation

```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/pinecms.git
cd pinecms

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Start development servers
npm run dev
php artisan serve
```

## Development

### Common Commands

**Frontend:**
```bash
npm run dev          # Start Vite dev server
npm run build        # Build for production
npm run preview      # Preview production build
npm test             # Run Vitest unit tests
npm run test:e2e     # Run Playwright E2E tests
npm run lint:fix     # Fix linting issues
npm run format       # Format code with Prettier
npm run type-check   # TypeScript type checking
npm run quality      # Run all quality checks
```

**Backend:**
```bash
composer test        # Run PHPUnit tests
composer analyse     # Run PHPStan static analysis
composer format      # Format code with Laravel Pint
composer rector      # Preview Rector refactorings
composer quality     # Run all quality checks
composer ci          # Full CI pipeline
```

### Git Workflow

This project uses:
- **Husky** for Git hooks
- **lint-staged** for pre-commit linting
- **Commitlint** for conventional commit messages

Commit format:
```
<type>: <description>

[optional body]
```

Types: `feat`, `fix`, `refactor`, `test`, `docs`, `style`, `chore`, `perf`, `ci`, `revert`, `build`

## Project Structure

```
pinecms/
├── app/                    # PHP application code
│   ├── Services/          # Business logic layer
│   ├── Events/            # Event classes
│   └── Listeners/         # Event listeners
├── bootstrap/             # Laravel bootstrap
├── config/                # Configuration files
├── resources/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript/Vue components
│   │   ├── Components/   # Vue components
│   │   ├── Composables/  # Vue composables
│   │   └── locales/      # i18n translations
│   ├── lang/             # PHP translations
│   └── views/            # Blade templates
├── storage/
│   ├── content/          # Flat-file content (YAML + Markdown)
│   ├── framework/        # Framework cache/sessions
│   └── logs/             # Application logs
├── docs/                  # Project documentation
└── tests/                 # Test suites
```

## Configuration

### Environment Variables

Key environment variables in `.env`:

```env
APP_ENV=local
APP_DEBUG=true
APP_KEY=                    # Generate with: php artisan key:generate

DB_CONNECTION=sqlite        # SQLite for dev, optional for production
CACHE_DRIVER=file
SESSION_DRIVER=file

CONTENT_PATH=storage/content
MARKDOWN_PARSER=commonmark
```

### Content Storage

Content files are stored in `storage/content/` as YAML Front Matter + Markdown:

```yaml
---
title: Page Title
slug: page-slug
published: true
date: 2025-01-12
---

Markdown content here...
```

## Testing

### PHP Testing
```bash
composer test              # Run all tests
composer test:unit         # Unit tests only
composer test:feature      # Feature tests only
composer test:mutation     # Mutation testing (min 80% MSI)
composer test:coverage     # Generate HTML coverage report
```

### JavaScript Testing
```bash
npm test                   # Vitest unit tests
npm run test:ui           # Vitest with UI
npm run test:coverage     # Generate coverage report
npm run test:e2e          # Playwright E2E tests
npm run test:e2e:ui       # Playwright with UI
```

## Architecture

### Event-Driven Design

PineCMS uses Laravel Events for cross-cutting concerns:

```php
// Dispatch events
event(new ContentPublished($content));

// Listen to events
class SendPublishNotification implements ShouldQueue
{
    public function handle(ContentPublished $event): void
    {
        // Handle the event
    }
}
```

**Important:** This project uses Events, NOT Hooks.

### Layer Architecture

```
Controllers (HTTP) → Services (Business Logic) → Repositories (Data Access)
                  ↓
                DTOs (Data Transfer)
                  ↓
                Events (Side Effects)
```

## Security

- **Content Security Policy**: Configured via Spatie CSP
- **Secure Headers**: HTTP security headers via bepsvpt/secure-headers
- **Input Validation**: Laravel validation in Form Requests
- **Output Escaping**: Blade auto-escaping + manual where needed
- **Activity Logging**: User actions tracked via spatie/laravel-activitylog
- **Authentication Logging**: Login attempts via rappasoft/laravel-authentication-log
- **Permissions**: Role-based access control via spatie/laravel-permission

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Make your changes
4. Run quality checks: `composer quality && npm run quality`
5. Commit using conventional commits
6. Push to your fork and submit a pull request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Copyright (c) 2025 [k0r37k1.dev](https://k0r37k1.dev)

## Acknowledgments

- Built with [Laravel Illuminate Components](https://laravel.com/)
- UI powered by [Vue 3](https://vuejs.org/), [Inertia.js](https://inertiajs.com/), and [TailwindCSS](https://tailwindcss.com/)
- Rich text editing via [TipTap](https://tiptap.dev/)
- Security packages by [Spatie](https://spatie.be/)

---

**Made with ❤️ by [k0r37k1.dev](https://k0r37k1.dev)**
