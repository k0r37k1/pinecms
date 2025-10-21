# PineCMS Development Guide

## Project Overview

**Tech Stack:**
- **Backend:** PHP 8.3 | Laravel 12
- **Frontend:** Vue 3.5 Composition API | Inertia.js | Alpine.js | TailwindCSS 4.1
- **Build:** Vite
- **Database:** SQLite (primary)
- **Testing:** PHPUnit | Vitest | Playwright
- **Quality:** PHPStan (level 8) | ESLint | Prettier | Pint

---

## 🚨 CRITICAL: MCP Server Usage

**YOU MUST fetch documentation BEFORE implementing any feature!**

### Priority Order

1. **Laravel code?** → `laravel-mcp-companion` FIRST
2. **Vue/Inertia/TipTap/Alpine/PrimeVue?** → `context7`
3. **Browser debugging?** → `chrome-devtools`

### Available MCP Servers

**Project-Specific:**
- `eslint` - Lint JS/TS after code changes
- `chrome-devtools` - Browser automation, debugging

**Global (use as needed):**
- `laravel-mcp-companion` - **PRIORITY** for Laravel (docs, packages, ecosystem)
- `context7` - Library docs (Vue, Inertia, TipTap, Alpine, PrimeVue, etc.)
- `gitmcp` - Research GitHub repos
- `firecrawl-mcp` - Web scraping, documentation
- `brave-search` - Quick web searches

### Task-Based MCP Selection

**Writing New Feature:**
1. `laravel-mcp-companion` - Fetch Laravel docs FIRST (if Laravel-related)
2. `context7` - Fetch other library docs (Vue, Inertia, PrimeVue, etc.)
3. Write code following documentation
4. `eslint` - Lint JS/TS code
5. `chrome-devtools` - Test in browser (if frontend)

**Debugging:**
1. `chrome-devtools` - Inspect browser state, console
2. `eslint` - Check code quality
3. `laravel-mcp-companion` - Verify Laravel API usage
4. `context7` - Verify other library API usage

**Research:**
1. `laravel-mcp-companion` - Laravel-specific research
2. `brave-search` / `firecrawl-mcp` - Web research
3. `gitmcp` - Study similar implementations
4. `context7` - Official documentation (Vue, PrimeVue, Inertia, etc.)

---

## ⚠️ Things Claude Should NEVER Do

### Forbidden Patterns

- ❌ **NEVER use Hooks** - This project uses Events only (Laravel event system)
- ❌ **NEVER skip `declare(strict_types=1);`** in PHP files
- ❌ **NEVER use CommonJS `require`** - Use ES modules
- ❌ **NEVER use Firebase JWT** - Not installed (use Laravel Sanctum)
- ❌ **NEVER create inline styles** - Use TailwindCSS utility classes
- ❌ **NEVER commit code with `console.log`** - Remove debug statements
- ❌ **NEVER use `var`** - Use `const` or `let`
- ❌ **NEVER create .md files outside `docs/`** - Exception: README.md

### Protected Areas - Require Permission

Claude should **ask before modifying** these areas:

- **Database Migrations** - Schema changes need review
- **Security Code** - Authentication, authorization, encryption
- **Test Files** - Changes affect test validity
- **API Contracts** - Breaking changes affect consumers
- **Configuration** - .env, config files with secrets

---

## Code Style & Standards

### PHP Standards

#### Required in ALL PHP Files

```php
<?php

declare(strict_types=1);

namespace App\...;
```

#### PSR-12 Coding Standard

- Enforced by Laravel Pint
- Run `composer format` before commit
- Automatically fixed by pre-commit hooks

#### Type Safety

- **Always** use strict types
- **Always** use typehints for parameters
- **Always** use return type declarations
- Use PHP 8.3 features: union types, intersection types, readonly properties

```php
// ✅ Good
public function updateContent(int $id, string $title): Content
{
    return $this->repository->update($id, $title);
}

// ❌ Bad
public function updateContent($id, $title)
{
    return $this->repository->update($id, $title);
}
```

#### Dependency Injection

```php
// ✅ Good: Constructor injection
class ContentService
{
    public function __construct(
        private ContentRepository $repository,
        private EventDispatcher $events,
    ) {}
}

// ❌ Bad: Manual instantiation
class ContentService
{
    public function __construct()
    {
        $this->repository = new ContentRepository();
    }
}
```

### JavaScript/TypeScript Standards

#### ES Modules ONLY

```typescript
// ✅ Good: ES Modules
import { ref, computed } from 'vue'
export { myFunction }

// ❌ Bad: CommonJS
const { ref } = require('vue')
module.exports = { myFunction }
```

#### TypeScript

- Use types for ALL function parameters and returns
- Enable strict mode (already configured)
- No `any` types without explicit reason

```typescript
// ✅ Good
interface Props {
  title: string
  count?: number
}

function updateTitle(props: Props): void {
  // ...
}

// ❌ Bad
function updateTitle(props: any) {
  // ...
}
```

#### Vue 3 Composition API

```vue
<script setup lang="ts">
import { ref, computed } from 'vue'

interface Props {
  title: string
  count?: number
}

const props = withDefaults(defineProps<Props>(), {
  count: 0
})

const emit = defineEmits<{
  update: [value: string]
}>()

const localCount = ref(props.count)
const doubled = computed(() => localCount.value * 2)
</script>
```

### CSS/TailwindCSS Standards

- Use utility classes, avoid custom CSS when possible
- Use `@apply` sparingly (only for repeated patterns)
- Responsive: mobile-first (default), then `md:`, `lg:`, etc.

```vue
<!-- ✅ Good -->
<div class="p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">

<!-- ❌ Bad -->
<div style="padding: 16px; background: white;">
```

---

## Software Engineering Principles

### DRY (Don't Repeat Yourself)

- Extract repeated code into reusable functions/classes
- Use Laravel's service container for shared dependencies
- Create base classes or traits for common functionality

### KISS (Keep It Simple, Stupid)

- Prefer simple, readable solutions over clever code
- Avoid premature optimization
- One method should do one thing

### YAGNI (You Aren't Gonna Need It)

- Don't build features "just in case"
- Implement what's needed NOW
- Refactor when requirements actually change

### SOLID Principles

**Single Responsibility (S)**
- Each class/method has ONE reason to change

**Open/Closed Principle (O)**
- Open for extension, closed for modification

**Liskov Substitution (L)**
- Subtypes must be substitutable for their base types

**Interface Segregation (I)**
- Many specific interfaces > one general interface

**Dependency Inversion (D)**
- Depend on abstractions, not concrete classes

### Balance: Avoiding Over-Engineering

**Guidelines for Complexity:**
- **<50 lines in method:** Keep it simple, no extraction needed
- **50-100 lines:** Consider extracting private methods
- **>100 lines:** Definitely extract to service/class
- **Repeated 2x:** Keep as-is
- **Repeated 3x:** Extract to method/function (DRY)
- **Complex logic:** Always extract and test separately

---

## Architecture Patterns

### Event-Driven Architecture

This project uses **Events**, NEVER Hooks.

```
User Action → Controller → Service → Repository → Database
                            ↓
                          Event
                            ↓
                        Listeners (logging, notifications, caching, etc.)
```

**Events Location:**
- **Events:** `app/Events/`
- **Listeners:** `app/Listeners/`
- **Registration:** `app/Providers/EventServiceProvider.php`

### Layer Architecture

**Laravel's Official Approach:**

```
Controllers (HTTP Layer)
    ↓
Services (Business Logic Layer)
    ↓
Models (Eloquent ORM with Query Scopes)

DTOs (Data Transfer Objects)
    ↔ Between layers for data transfer

Events (Side Effects)
    ↔ Triggered by Services for cross-cutting concerns
```

**Optional Community Pattern (Repositories):**

> **⚠️ IMPORTANT**: The Repository Pattern is **NOT part of official Laravel documentation**.
> It's a community pattern. Laravel's official approach is to use Eloquent models directly
> with Query Scopes.

Use Repositories only when:
- You have complex queries used in multiple places
- You need to swap data sources (DB ↔ File ↔ API)
- You want to test services without database

### Laravel Best Practices

**Always Use Query Scopes:**

```php
// ✅ Good: Reusable, testable scopes
class Content extends Model
{
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at');
    }
}

// Usage
Content::published()->get();
```

**Avoid N+1 Queries (Critical!):**

```php
// ❌ Bad: N+1 query problem
$contents = Content::all();
foreach ($contents as $content) {
    echo $content->author->name;  // N queries!
}

// ✅ Good: Eager loading
$contents = Content::with('author')->get();
foreach ($contents as $content) {
    echo $content->author->name;  // 1 query!
}
```

---

## Workflow Best Practices

### Development Workflow

1. **Plan First**
   - For complex tasks: Use Plan Mode (`Shift+Tab`)
   - Ask Claude to "think hard" for better solutions
   - Create implementation plan before coding

2. **Research: Fetch Documentation**
   - ⚠️ **CRITICAL**: Never skip this step!
   - Laravel feature? → `laravel-mcp-companion`
   - Vue/Inertia/TipTap/PrimeVue? → `context7`

3. **Test-Driven Development (When Applicable)**

   ```bash
   # Write test first
   composer test:unit  # Should fail (RED)

   # Implement feature
   composer test:unit  # Should pass (GREEN)

   # Refactor
   composer test:unit  # Still passes
   ```

4. **Implement Code**
   - Follow code style guidelines
   - Use strict types in PHP
   - Add type hints in TypeScript
   - Document WHY, not WHAT

5. **Quality Check**

   ```bash
   # PHP
   composer format
   composer analyse
   composer test

   # JavaScript
   npm run format
   npm run lint:fix
   npm run type-check
   npm run test

   # Or run all at once
   composer quality && npm run quality
   ```

6. **Commit**

   ```bash
   git add .
   git commit -m "feat: add feature description"
   ```

### Before Every Commit

```bash
# PHP
composer format              # ✅ Code formatted
composer analyse             # ✅ No PHPStan errors
composer test                # ✅ Tests pass

# JavaScript/TypeScript
npm run format               # ✅ Code formatted
npm run lint:fix             # ✅ No JS/TS lint errors
npm run lint:css:fix         # ✅ No CSS lint errors
npm run type-check           # ✅ No type errors
npm run test                 # ✅ Tests pass
```

---

## Plan Mode

### What is Plan Mode?

Plan Mode is a **read-only permission mode** for safe code analysis without risk of modifications.

**Toggle:** Press `Shift+Tab` during any Claude session

### When to Use Plan Mode

**ALWAYS use Plan Mode for:**

1. **Complex Multi-Step Implementations**
   - Major feature additions
   - Large-scale refactoring
   - Database migrations with data transformations

2. **Code Exploration & Understanding**
   - Analyzing unfamiliar codebases
   - Understanding dependencies between components
   - Security audits

3. **Architecture & Design Decisions**
   - Evaluating different approaches
   - Comparing library/framework options
   - Designing event flows

### When NOT to Use Plan Mode

- ❌ Simple bug fixes (single file, obvious solution)
- ❌ Minor text/formatting changes
- ❌ Adding comments or documentation
- ❌ Running tests or linters

### Plan Mode Workflow

1. **Start in Plan Mode** (`Shift+Tab`)
2. **Research & Analyze** (Use MCP tools)
3. **Create Detailed Plan**
4. **Review Plan** (Discuss approach)
5. **Exit Plan Mode** (`Shift+Tab`)
6. **Implement** (Execute with confidence)

---

## Testing Strategy

### PHP Testing

**Running Tests:**

```bash
composer test                 # All tests with coverage
composer test:unit            # Unit tests only
composer test:feature         # Feature tests only
composer test:mutation        # Mutation testing (Infection)
```

**Test Structure:**

```php
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_content(): void
    {
        $service = app(ContentService::class);

        $content = $service->create([
            'title' => 'Test',
            'body' => 'Content',
        ]);

        $this->assertDatabaseHas('contents', [
            'title' => 'Test',
        ]);
    }
}
```

### JavaScript Testing

**Running Tests:**

```bash
npm test                    # Run tests in watch mode
npm run test:coverage       # Generate coverage
npm run test:ui             # Open Vitest UI
```

### Quality Gates

**PHP:**
- PHPStan: Level 8, strict rules
- Coverage: ≥90%
- Mutation Score: ≥80% MSI, ≥90% covered MSI

**JavaScript:**
- TypeScript: Strict mode, no errors
- ESLint: No errors
- Coverage: ≥80%

---

## Git & GitHub

### Commit Message Format

```
<type>: <description>

[optional body]

[optional footer]
```

**Types:**
- `feat` - New feature
- `fix` - Bug fix
- `refactor` - Code change (no bug fix or feature)
- `test` - Adding or updating tests
- `docs` - Documentation only
- `style` - Formatting, whitespace
- `chore` - Build, dependencies, config

**Examples:**

```bash
git commit -m "feat: add user authentication"
git commit -m "fix: resolve n+1 query in content listing"
git commit -m "refactor: extract content filtering to service"
```

### Branch Strategy

```bash
# Create branch from main
git checkout -b feature/short-description

# Work on feature
git add .
git commit -m "feat: add feature"

# Keep up-to-date with main
git pull origin main --rebase

# Push when ready
git push -u origin feature/short-description

# Create PR
gh pr create
```

### Commit Validation

Husky + commitlint enforce commit message format automatically.

---

## Security Principles

### Always Apply

- **Validate ALL inputs** - Use Laravel FormRequests
- **Escape ALL outputs** - Blade auto-escapes, manual for Vue
- **Track actions** - Use spatie/laravel-activitylog
- **Use CSP headers** - Configured via spatie/laravel-csp
- **CSRF Protection** - Enabled for all POST/PUT/DELETE
- **Never trust user data** - Validate, sanitize, escape

### Input Validation

```php
// ✅ Good: FormRequest
class StoreContentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:contents'],
        ];
    }
}
```

### Output Escaping

```blade
{{-- ✅ Good: Auto-escaped --}}
<h1>{{ $title }}</h1>

{{-- ❌ Dangerous: Unescaped --}}
<div>{!! $userContent !!}</div>
```

---

## Common Commands

### PHP/Backend

```bash
composer install              # Install PHP dependencies
composer test                 # Run PHPUnit tests
composer analyse              # Run PHPStan
composer format               # Format code with Pint
composer quality              # Run format, analyse, test
composer ci                   # Full CI pipeline
```

### JavaScript/Frontend

```bash
npm install                  # Install Node dependencies
npm run dev                  # Start Vite dev server
npm run build                # Build for production
npm test                     # Run Vitest tests
npm run lint                 # Lint JS/TS
npm run lint:fix             # Auto-fix linting
npm run type-check           # TypeScript check
npm run quality              # Run all quality checks
```

---

## Environment & Configuration

### Required Environment Variables

```bash
APP_ENV=local
APP_DEBUG=true
APP_KEY=                     # Generate via: php artisan key:generate

DB_CONNECTION=sqlite
DB_DATABASE=/path/to/db.sqlite

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## Key Dependencies

### Core PHP
- Laravel 12 (Illuminate packages)
- Spatie Laravel Permission, ActivityLog, CSP, YAML Front Matter
- Inertia.js Laravel
- League CommonMark
- Intervention Image

### Core JavaScript
- Vue 3.5, Vue i18n
- Inertia.js Vue3
- Alpine.js (with plugins)
- PrimeVue (UI Component Library)
- TipTap Editor
- Pinia (State Management)
- VueUse, Axios

### Development Tools
- PHPStan, Rector, PHPUnit, Infection
- Laravel Pint
- ESLint, Stylelint, Prettier
- Vitest, Playwright

---

## Documentation Guidelines

### Where to Document

- **General guides:** `docs/` directory
- **Architecture decisions:** `docs/adr/` (if needed)
- **API documentation:** `docs/api/` (if needed)
- **Troubleshooting:** `docs/troubleshooting.md`

### Documentation Standards

- Use Markdown
- Include code examples
- Document WHY, not WHAT
- Keep up-to-date with code changes

---

## 🔥 CRITICAL Reminders

1. **MCP FIRST:** Use `laravel-mcp-companion` or `context7` BEFORE writing any code
2. **Events ONLY:** Never use Hooks - this is event-driven architecture
3. **Test BEFORE commit:** Run `composer quality && npm run quality`
4. **Security first:** Validate inputs, escape outputs, never trust user data
5. **Docs in `docs/` only:** Never create .md files in project root (except README.md)
6. **Strict types:** Always use `declare(strict_types=1);` in PHP files
7. **No assumptions:** Don't assume classes/features exist - check first
