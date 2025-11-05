# PineCMS Development Guide

## ⚠️ Modification Policy

**CRITICAL: Before ANY modification to this CLAUDE.md file:**

```bash
cp CLAUDE.md "CLAUDE.$(date +%Y%m%d_%H%M%S).md.backup"
```

**Backup location:** Keep backups in project root for easy access
**Backup retention:** Keep last 5 backups, delete older ones

**This rule applies to:**

- Content updates
- Structural changes
- Optimization iterations
- Adding/removing sections

**Why:** CLAUDE.md is auto-read on every session. Breaking it breaks the entire AI workflow.

---

## 🚀 Quick Reference

**Before ANY coding:**

1. `/clear` - Clear context for new task
2. Use `search-docs` tool - Fetch Laravel/Inertia/Vue docs (Laravel Boost MCP)
3. Use `context7` - For PrimeVue/TipTap/Alpine docs
4. Plan first (`Shift+Tab` for complex tasks)

**Common Commands:**

```bash
# PHP Quality Check
composer quality              # Format, analyze, test (all-in-one)

# JavaScript Quality Check
npm run quality               # Format, lint, type-check, test (all-in-one)
```

**After commit:** Always run `/clear` to reset context

**📚 Detailed Instructions:** See `.claude/instructions/` for comprehensive guidelines

---

## 🚨 CRITICAL Rules

### Forbidden Patterns

- ❌ **NEVER use Hooks** - This project uses Events only (Laravel event system)
- ❌ **NEVER skip `declare(strict_types=1);`** in PHP files
- ❌ **NEVER use `env()` directly** - ALWAYS use `config()` (after config:cache, env() returns null)
- ❌ **NEVER use CommonJS `require`** - Use ES modules
- ❌ **NEVER create inline styles** - Use TailwindCSS utility classes
- ❌ **NEVER commit `console.log`** - Remove debug statements
- ❌ **NEVER delete files/code without asking** - Always request user approval first
- ❌ **NEVER create .md files outside `docs/`** - Exception: README.md in project root

### Protected Areas (Ask Before Modifying)

- Database Migrations (schema changes need review)
- Security Code (authentication, authorization, encryption)
- Test Files (changes affect test validity)
- API Contracts (breaking changes affect consumers)
- Configuration (.env, config files with secrets)

---

## 🛠️ Tech Stack

**Backend:** PHP 8.3 | Laravel 12
**Storage:** SQLite (relational) + Flat-File (content/markdown) - **Hybrid Approach**
**Frontend (Public):** Blade Templates | PHP | TailwindCSS 4.1 | Alpine.js
**Admin Panel:** Vue 3.5 Composition API | Inertia.js 2.x | PrimeVue | Pinia | TailwindCSS 4.1
**Build:** Vite
**Testing:** PHPUnit | Vitest | Playwright
**Quality:** PHPStan (level 8) | ESLint | Prettier | Pint

---

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.14
- inertiajs/inertia-laravel (INERTIA) - v2
- laravel/fortify (FORTIFY) - v1
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- laravel/scout (SCOUT) - v10
- larastan/larastan (LARASTAN) - v3
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- laravel/telescope (TELESCOPE) - v5
- phpunit/phpunit (PHPUNIT) - v12
- rector/rector (RECTOR) - v2
- @inertiajs/vue3 (INERTIA) - v2
- alpinejs (ALPINEJS) - v3
- vue (VUE) - v3
- eslint (ESLINT) - v9
- prettier (PRETTIER) - v3
- tailwindcss (TAILWINDCSS) - v4

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

## Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs

- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments

- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks

- Add useful array shape type definitions for arrays when appropriate.

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

=== inertia-laravel/core rules ===

## Inertia Core

- Inertia.js components should be placed in the `resources/js/Pages` directory unless specified differently in the JS bundler (vite.config.js).
- Use `Inertia::render()` for server-side routing instead of traditional Blade views.
- Use `search-docs` for accurate guidance on all things Inertia.

<code-snippet lang="php" name="Inertia::render Example">
// routes/web.php example
Route::get('/users', function () {
    return Inertia::render('Users/Index', [
        'users' => User::all()
    ]);
});
</code-snippet>

=== inertia-laravel/v2 rules ===

## Inertia v2

- Make use of all Inertia features from v1 & v2. Check the documentation before making any changes to ensure we are taking the correct approach.

### Inertia v2 New Features

- Polling
- Prefetching
- Deferred props
- Infinite scrolling using merging props and `WhenVisible`
- Lazy loading data on scroll

### Deferred Props & Empty States

- When using deferred props on the frontend, you should add a nice empty state with pulsing / animated skeleton.

### Inertia Form General Guidance

- The recommended way to build forms when using Inertia is with the `<Form>` component - a useful example is below. Use `search-docs` with a query of `form component` for guidance.
- Forms can also be built using the `useForm` helper for more programmatic control, or to follow existing conventions. Use `search-docs` with a query of `useForm helper` for guidance.
- `resetOnError`, `resetOnSuccess`, and `setDefaultsOnSuccess` are available on the `<Form>` component. Use `search-docs` with a query of 'form component resetting' for guidance.

=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] <name>` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure

- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.

=== phpunit/core rules ===

## PHPUnit Core

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit <name>` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should test all of the happy paths, failure paths, and weird paths.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files, these are core to the application.

### Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test`.
- To run all tests in a file: `php artisan test tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --filter=testName` (recommended after making a change to a related file).

=== inertia-vue/core rules ===

## Inertia + Vue

- Vue components must have a single root element.
- Use `router.visit()` or `<Link>` for navigation instead of traditional links.

<code-snippet name="Inertia Client Navigation" lang="vue">

    import { Link } from '@inertiajs/vue3'
    <Link href="/">Home</Link>

</code-snippet>

=== inertia-vue/v2/forms rules ===

## Inertia + Vue Forms

<code-snippet name="`<Form>` Component Example" lang="vue">

<Form
    action="/users"
    method="post"
    #default="{
        errors,
        hasErrors,
        processing,
        progress,
        wasSuccessful,
        recentlySuccessful,
        setError,
        clearErrors,
        resetAndClearErrors,
        defaults,
        isDirty,
        reset,
        submit,
  }"
>
    <input type="text" name="name" />

    <div v-if="errors.name">
        {{ errors.name }}
    </div>

    <button type="submit" :disabled="processing">
        {{ processing ? 'Creating...' : 'Create User' }}
    </button>

    <div v-if="wasSuccessful">User created successfully!</div>
</Form>

</code-snippet>

=== tailwindcss/core rules ===

## Tailwind Core

- Use Tailwind CSS classes to style HTML, check and use existing tailwind conventions within the project before writing your own.
- Offer to extract repeated patterns into components that match the project's conventions (i.e. Blade, JSX, Vue, etc..)
- Think through class placement, order, priority, and defaults - remove redundant classes, add classes to parent or child carefully to limit repetition, group elements logically
- You can use the `search-docs` tool to get exact examples from the official documentation when needed.

### Spacing

- When listing items, use gap utilities for spacing, don't use margins.

    <code-snippet name="Valid Flex Gap Spacing Example" lang="html">
        <div class="flex gap-8">
            <div>Superior</div>
            <div>Michigan</div>
            <div>Erie</div>
        </div>
    </code-snippet>

### Dark Mode

- If existing pages and components support dark mode, new pages and components must support dark mode in a similar way, typically using `dark:`.

=== tailwindcss/v4 rules ===

## Tailwind 4

- Always use Tailwind CSS v4 - do not use the deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.
- In Tailwind v4, you import Tailwind using a regular CSS `@import` statement, not using the `@tailwind` directives used in v3:

<code-snippet name="Tailwind v4 Import Tailwind Diff" lang="diff">
   - @tailwind base;
   - @tailwind components;
   - @tailwind utilities;
   + @import "tailwindcss";
</code-snippet>

### Replaced Utilities

- Tailwind v4 removed deprecated utilities. Do not use the deprecated option - use the replacement.
- Opacity values are still numeric.

| Deprecated | Replacement |
|------------+--------------|
| bg-opacity-*| bg-black/* |
| text-opacity-*| text-black/* |
| border-opacity-*| border-black/* |
| divide-opacity-*| divide-black/* |
| ring-opacity-*| ring-black/* |
| placeholder-opacity-*| placeholder-black/* |
| flex-shrink-*| shrink-* |
| flex-grow-*| grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |
</laravel-boost-guidelines>

---

## 🎯 Software Engineering Principles

Apply these principles when designing and implementing features:

**KISS (Keep It Simple)** - Prefer simple solutions, avoid over-engineering
**DRY (Don't Repeat Yourself)** - Extract repeated code, use Laravel's built-in features
**YAGNI (You Aren't Gonna Need It)** - Implement only what's currently needed
**SOLID Principles:**

- **S** - Single Responsibility (one reason to change)
- **O** - Open/Closed (extend, don't modify)
- **L** - Liskov Substitution (subtypes substitutable)
- **I** - Interface Segregation (focused interfaces)
- **D** - Dependency Inversion (depend on abstractions)

**Separation of Concerns:**

- Business logic in Services
- Data access in Repositories/Models
- HTTP handling in Controllers
- Presentation in Views/Components
- Side effects in Event Listeners

**📚 Details:** `.claude/instructions/architecture.md`

---

## 🔌 MCP Server Priority

Use MCP servers in this order for optimal results:

1. **Laravel Boost** - Laravel/PHP ecosystem (`search-docs`, `tinker`, `database-query`, `browser-logs`)
2. **filesystem** - Flat-file operations (Critical for PineCMS content management)
3. **Laravel MCP Companion** - Alternative Laravel docs (`search_laravel_docs_with_context`)
4. **context7** - Modern framework documentation (PrimeVue, TipTap, Alpine.js)
5. **vibe-check** - Metacognitive questioning, pattern learning, tunnel vision prevention
6. **chrome-devtools** - Browser automation and debugging
7. **eslint** - JavaScript/TypeScript linting
8. **playwright** - End-to-end testing
9. **gitmcp** - Git/GitHub operations

---

## 🤖 Specialized Agents

**14 agents available** via `Task` tool - See `.claude/agents/` for full descriptions

**Development:** frontend-developer, backend-architect, fullstack-developer, ui-ux-designer
**Tools:** code-reviewer, test-engineer, debugger, error-detective, context-manager
**Specialists:** task-decomposition-expert, database-architect, architect-reviewer, deployment-engineer, security-auditor

**Usage:**

- Simple tasks → Implement directly
- Complex tasks → Use specialized agents
- **Multi-step features → Start with task-decomposition-expert for planning**

---

## 🆕 New Best Practices (2025)

### Inline Instructions

Press `#` to give Claude instructions that auto-incorporate into CLAUDE.md
Useful for documenting commands, files, style guidelines while coding

### Subagent Limitations

- ❌ NO thinking mode (can't monitor progress)
- ❌ NO stepwise plans (single-shot execution only)
- ⚠️ Requires explicit MCP tool permissions

### Test-Driven Development (MANDATORY)

**Be explicit about TDD to prevent mock implementations:**

```
"I'm doing TDD. Write failing test first, don't create mock implementation."
```

**RED → GREEN → REFACTOR cycle:**

1. Write failing test
2. Minimal implementation to pass
3. Refactor with confidence

**📚 Details:** `.claude/workflows/tdd.md`

### Plan Mode (`Shift+Tab`)

**Use for:**

- Complex multi-step implementations
- Large-scale refactoring
- Architecture decisions
- Features requiring multiple agents

**📚 Details:** `.claude/workflows/plan-mode.md`

### MCP Debugging

```bash
claude --mcp-debug  # Troubleshoot MCP configuration issues
```

---

## 📚 Detailed Instructions

**This CLAUDE.md contains ONLY core rules. For comprehensive guidelines:**

### Instructions

- `.claude/instructions/backend.md` - Laravel, Services, Eloquent, API, Queues
- `.claude/instructions/frontend.md` - Vue, Inertia, TailwindCSS, Alpine
- `.claude/instructions/testing.md` - PHPUnit, Vitest, Playwright
- `.claude/instructions/security.md` - OWASP, Auth, Security Principles
- `.claude/instructions/quality-gates.md` - QPLAN, QCHECK, QCODE
- `.claude/instructions/architecture.md` - Event-Driven, Layers, N+1

### Commands

- `.claude/commands/quality.md` - composer quality, npm run quality
- `.claude/commands/development.md` - Common Artisan/Git commands

### Workflows

- `.claude/workflows/tdd.md` - Test-Driven Development
- `.claude/workflows/plan-mode.md` - When/how to use Plan Mode
- `.claude/workflows/git-workflow.md` - Conventional commits, PRs

---

**Last updated:** 2025-10-27
**Version:** 2.0 - Modularized Architecture (40% Token Reduction)
**Project:** PineCMS - Security & Privacy-First Flat-File Hybrid CMS
