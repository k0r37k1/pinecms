<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.13
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

| Deprecated |	Replacement |
|------------+--------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |
</laravel-boost-guidelines>

---

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
composer format               # Laravel Pint
composer analyse              # PHPStan level 8
composer test                 # PHPUnit

# JavaScript Quality Check
npm run quality               # Format, lint, type-check, test (all-in-one)
npm run format                # Prettier
npm run lint:fix              # ESLint auto-fix
npm run type-check            # TypeScript
npm test                      # Vitest

# Laravel Boost
php artisan boost:update      # Update AI guidelines
```

**After commit:** Always run `/clear` to reset context

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

## 🎯 Software Engineering Principles

Apply these principles when designing and implementing features:

### KISS (Keep It Simple, Stupid)
- Prefer simple solutions over complex ones
- Avoid over-engineering
- Write code that is easy to understand and maintain
- Question if complexity is truly necessary

### DRY (Don't Repeat Yourself)
- Extract repeated code into reusable functions/components
- Use Laravel's built-in features before creating custom solutions
- Create shared utilities for common operations
- Consider: Is this logic duplicated elsewhere?

### YAGNI (You Aren't Gonna Need It)
- Implement only what is currently needed
- Don't build features for hypothetical future requirements
- Avoid premature optimization
- Focus on MVP functionality first

### SOLID Principles

**S - Single Responsibility**
- Each class/function should have one reason to change
- Controllers handle HTTP, Services handle business logic, Models handle data
- Keep methods focused on a single task

**O - Open/Closed**
- Open for extension, closed for modification
- Use interfaces and abstract classes for extensibility
- Extend behavior through inheritance/composition, not modification

**L - Liskov Substitution**
- Subtypes must be substitutable for their base types
- Child classes should not break parent class contracts
- Maintain expected behavior when extending classes

**I - Interface Segregation**
- Many specific interfaces are better than one general interface
- Clients should not depend on methods they don't use
- Keep interfaces focused and minimal

**D - Dependency Inversion**
- Depend on abstractions, not concretions
- Use dependency injection (Laravel's container)
- High-level modules should not depend on low-level modules

### Separation of Concerns
- Business logic in Services
- Data access in Repositories/Models
- HTTP handling in Controllers
- Presentation in Views/Components
- Side effects in Event Listeners

---

## 🤖 Specialized Agents & MCP Mapping

**14 agents available** via `Task` tool - Critical for planning complex tasks and subtasks

**See `.claude/agents/` for full descriptions of each agent**

### Development Team
- **frontend-developer** (Sonnet)
  - **Focus:** Vue 3.5, Inertia.js, Alpine, TailwindCSS, PrimeVue
  - **MCP:** context7, chrome-devtools, eslint, playwright
  - **Use for:** UI components, frontend features, responsive design

- **backend-architect** (Sonnet)
  - **Focus:** Laravel, APIs, Database Design, Caching, Performance
  - **MCP:** laravel-boost, laravel-mcp-companion, filesystem
  - **Use for:** Backend architecture, business logic, API design

- **fullstack-developer** (Opus)
  - **Focus:** End-to-end features, Laravel + Vue + Inertia integration
  - **MCP:** laravel-boost, context7, chrome-devtools, filesystem
  - **Use for:** Complete feature implementation, API integration

- **ui-ux-designer** (Sonnet)
  - **Focus:** User interface, UX patterns, accessibility
  - **MCP:** chrome-devtools
  - **Use for:** Design decisions, user flow, accessibility improvements

### Development Tools
- **code-reviewer** (Sonnet)
  - **Focus:** Code quality, security, maintainability, best practices
  - **MCP:** eslint, laravel-boost, context7, filesystem
  - **Use for:** Pre-commit review, refactoring validation, code standards

- **test-engineer** (Sonnet)
  - **Focus:** Test strategy (PHPUnit, Vitest, Playwright)
  - **MCP:** playwright, laravel-boost
  - **Use for:** Writing tests, improving coverage, test automation

- **debugger** (Sonnet)
  - **Focus:** Root cause analysis, error tracking, stack traces
  - **MCP:** chrome-devtools, laravel-boost (browser-logs, tinker)
  - **Use for:** Complex bugs, performance issues, runtime errors

- **error-detective** (Sonnet)
  - **Focus:** Stack trace analysis, log patterns, exception handling
  - **MCP:** laravel-boost (browser-logs, last-error)
  - **Use for:** Runtime errors, exception handling, log analysis

- **context-manager** (Opus)
  - **Focus:** Codebase exploration, dependency analysis, refactoring planning
  - **MCP:** gitmcp, filesystem
  - **Use for:** Understanding codebases, large-scale refactoring

### Specialists
- **task-decomposition-expert** (Sonnet)
  - **Focus:** Complex task breakdown, roadmaps, subtask creation
  - **MCP:** All available MCPs
  - **Use for:** Large features, multi-step implementations, planning
  - **CRITICAL:** Use this agent for planning complex projects and creating structured tasks/subtasks

- **database-architect** (Opus)
  - **Focus:** Database design, scalability, migrations, query optimization
  - **MCP:** laravel-boost (database-query, database-schema), filesystem
  - **Use for:** Database design, schema planning, query optimization

- **architect-review** (Sonnet)
  - **Focus:** SOLID principles, design patterns, architecture consistency
  - **MCP:** gitmcp, filesystem
  - **Use for:** Architecture decisions, major refactoring, pattern validation

- **deployment-engineer** (Sonnet)
  - **Focus:** CI/CD, Docker, deployment automation, infrastructure
  - **MCP:** playwright, chrome-devtools
  - **Use for:** Deployment issues, pipeline improvements, automation

- **security-auditor** (Opus)
  - **Focus:** OWASP compliance, auth, encryption, vulnerability scanning
  - **MCP:** laravel-boost, eslint, filesystem
  - **Use for:** Security audits, sensitive features, vulnerability fixes

### Usage Guidelines
- **Simple tasks** → Implement directly
- **Complex tasks** → Use specialized agents via Task tool
- **Multi-step features** → Start with **task-decomposition-expert** for planning
- **Planning phase** → Use agents to create structured tasks, subtasks, and implementation roadmap

---

## 🔌 MCP Server Priority

Use MCP servers in this order for optimal results:

1. **Laravel Boost** - Laravel/PHP ecosystem
   - `search-docs` - Version-specific Laravel/Inertia/Vue docs
   - `tinker` - Execute PHP code in Laravel context
   - `database-query` - Query database directly
   - `browser-logs` - Read browser errors/exceptions
   - `database-schema` - Inspect database structure
   - `list-routes` - See all available routes
   - `list-artisan-commands` - Available Artisan commands

2. **filesystem** - Flat-file operations (Critical for PineCMS)
   - Read/write markdown content files
   - Manage flat-file storage
   - Handle content directory operations

3. **Laravel MCP Companion** - Alternative Laravel docs
   - `search_laravel_docs_with_context` - Alternative doc search
   - `read_laravel_doc_content` - Read specific doc files
   - `get_laravel_package_recommendations` - Package suggestions

4. **context7** - Modern framework documentation
   - PrimeVue component docs
   - TipTap editor documentation
   - Alpine.js patterns and examples

5. **chrome-devtools** - Browser automation and debugging
   - Browser automation for testing
   - Live debugging and inspection
   - Performance profiling

6. **eslint** - JavaScript/TypeScript linting
   - Lint JS/TS files after code changes
   - Enforce code style standards

7. **playwright** - End-to-end testing
   - E2E test automation
   - Cross-browser testing
   - UI interaction testing

8. **gitmcp** - Git/GitHub operations
   - Repository operations
   - GitHub API interactions
   - Version control automation

---

## 🏗️ Architecture

### Event-Driven (NEVER Hooks)
```
User Action → Controller → Service → Repository → Database
                            ↓
                          Event
                            ↓
                        Listeners (logging, notifications, caching, etc.)
```

**Events Location:** `app/Events/`
**Listeners Location:** `app/Listeners/`
**Registration:** `app/Providers/EventServiceProvider.php`

### Layer Architecture
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

### Flat-File Hybrid Storage Architecture
- **SQLite:** Relational data (users, metadata, configurations, relationships)
- **Flat-File:** Content storage (pages, posts, documentation in markdown format)
- **Use `filesystem` MCP** for all flat-file operations
- **Benefits:** Simple backups, version control friendly, no complex database migrations for content

### N+1 Prevention
Always use eager loading to prevent N+1 query problems:
```php
// Bad - N+1 queries
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name; // N queries
}

// Good - Eager loading
$posts = Post::with('author')->get();
foreach ($posts as $post) {
    echo $post->author->name; // 2 queries total
}
```

---

## 🔥 Development Workflow

### Standard Workflow
1. **Clear Context** - Run `/clear` when starting new feature (prevents token waste)
2. **Research** - Use `search-docs` (Laravel Boost) or `context7` (PrimeVue/TipTap/Alpine)
3. **Plan** - Use Plan Mode (`Shift+Tab`) for complex tasks
4. **Implement** - Follow code conventions, use strict types, add type hints
5. **Test** - Write/run tests for happy paths, failures, edge cases
6. **Quality Check** - `composer quality && npm run quality`
7. **Commit** - Conventional commits: `feat/fix/refactor/test/docs/style/chore`
8. **Clear Again** - Run `/clear` after commit to reset context

### Test-Driven Development (When Applicable)
```bash
# 1. Write test first
composer test:unit  # Should fail (RED)

# 2. Implement feature
composer test:unit  # Should pass (GREEN)

# 3. Refactor
composer test:unit  # Still passes
```

---

## 🧠 Plan Mode (think, think harder, ultrathink)

### When to Use Plan Mode

**ALWAYS use Plan Mode (`Shift+Tab`) for:**
1. Complex multi-step implementations
2. Large-scale refactoring
3. Database migrations with data transformations
4. Architecture & design decisions
5. Features requiring multiple agents/subtasks

**When NOT to Use:**
- Simple bug fixes (single file, obvious solution)
- Minor text/formatting changes
- Running tests or linters

### Plan Mode Workflow with think/ultrathink

1. **Start in Plan Mode** (`Shift+Tab`)

2. **Ask Claude to think deeply:**
   - "think about this problem"
   - "think harder about the best approach"
   - "ultrathink this architecture decision"

3. **Research & Analyze** (Use MCP tools)
   - `search-docs` for documentation
   - `database-schema` for data structure
   - `list-routes` for existing endpoints
   - `context7` for frontend patterns

4. **Create Detailed Plan**
   - Break down into tasks and subtasks
   - Identify dependencies
   - Consider edge cases
   - Plan testing strategy

5. **Review Plan** (Discuss approach with user)
   - Present plan for approval
   - Discuss trade-offs
   - Adjust based on feedback

6. **Exit Plan Mode** (`Shift+Tab`)

7. **Implement** (Execute with confidence)
   - Use specialized agents for complex subtasks
   - Follow the approved plan
   - Test incrementally

### Examples of Plan Mode Usage

**Simple task (NO Plan Mode):**
- "Fix typo in UserController.php line 45"

**Complex task (USE Plan Mode):**
- "Implement multi-tenant architecture with separate databases per tenant"
- "Add real-time collaboration feature with WebSockets"
- "Refactor authentication system to support OAuth2 providers"

---

## 🧪 Testing

### PHPUnit
```bash
# Run specific test (recommended after making changes)
php artisan test --filter=testMethodName

# Run specific file
php artisan test tests/Feature/ExampleTest.php

# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage
```

### Vitest
```bash
# Run all tests
npm test

# Run with coverage
npm run test:coverage

# Run in UI mode
npm run test:ui

# Run in watch mode
npm test -- --watch
```

### Playwright
```bash
# Run E2E tests
npm run test:e2e

# Run in UI mode
npm run test:e2e:ui

# Run specific test
npm run test:e2e tests/e2e/login.spec.ts
```

### Testing Rules
- Test happy paths, failure paths, and edge cases
- Use factories for model creation in tests
- Check if factory has custom states before manual setup
- Run minimal tests with filters before finalizing changes
- **NEVER remove tests without approval** - They're not temporary files!
- Every feature requires tests (unit or feature tests)
- Ask user if they want full test suite run after feature tests pass

---

## 📝 Code Standards

### PHP Standards
- `declare(strict_types=1);` in ALL PHP files
- Explicit return type declarations for all methods/functions
- Constructor property promotion for dependencies
- PHPDoc blocks for complex array shapes
- Use `config()` NEVER `env()` directly outside config files
- Curly braces for all control structures (even single line)

### TypeScript/Vue Standards
- Use Composition API (`<script setup>`)
- Proper type definitions for all functions/props
- ES modules (import/export) - NO CommonJS
- TailwindCSS classes (NO inline styles)
- Single root element per Vue component

### Laravel Specifics
- **Events ONLY** (no hooks in this project)
- FormRequest classes for validation (not inline validation)
- Eloquent relationships with return type hints
- Eager loading to prevent N+1 queries
- Named routes with `route()` function for URL generation
- Use `php artisan make:` commands for file creation

### Inertia.js Patterns
- Use `<Form>` component (preferred over useForm helper)
- Add empty states for deferred props (skeleton loaders)
- Use `resetOnSuccess`, `setDefaultsOnSuccess` for form handling
- Use `search-docs` with query 'form component' for guidance

### PrimeVue Patterns
- Check for existing components before creating new ones
- Follow dark mode conventions (`dark:` classes)
- Use PrimeIcons package for icons
- Leverage PrimeVue's built-in features before custom solutions

### TailwindCSS Patterns
- Use `gap` utilities for spacing (NOT margins)
- Follow existing conventions in project
- Extract repeated patterns into components
- Support dark mode if other pages do (`dark:` classes)

---

## 🔒 Security Principles

### Input Validation
- **Validate ALL inputs** using Laravel FormRequest classes
- Never trust user data - always validate, sanitize, and escape
- Use Laravel's built-in validation rules
- Create custom validation rules for complex scenarios

### Output Escaping
- Blade templates auto-escape by default
- Manual escaping required in Vue templates when using v-html
- Use `{{ }}` syntax in Blade (auto-escaped)
- Avoid `{!! !!}` unless absolutely necessary (unescaped)

### Authentication & Authorization
- Use Laravel Sanctum for API authentication
- Use Laravel Fortify for authentication scaffolding
- Implement gates and policies for authorization
- Never expose sensitive data in API responses

### CSRF Protection
- Enabled by default for all POST/PUT/PATCH/DELETE requests
- Ensure CSRF tokens are included in forms
- Inertia.js handles CSRF tokens automatically

### Database Security
- Use parameterized queries (Eloquent does this automatically)
- Never use raw SQL with user input without bindings
- Implement proper database permissions
- Regular backups for both SQLite and flat-files

### Content Security
- Use spatie/laravel-csp for Content Security Policy headers
- Implement proper CORS configuration
- Track user actions with spatie/laravel-activitylog
- Audit sensitive operations

### File Upload Security
- Validate file types and sizes
- Store uploads outside public directory when possible
- Scan uploads for malware when applicable
- Use signed URLs for temporary access

---

## 🎯 Performance & Token Optimization

### Context Window Management
- **This CLAUDE.md is READ on EVERY session** - Keep it lean
- Use Laravel Boost MCP tools instead of reading files manually
- Leverage specialized agents for complex tasks

### Token-Saving Best Practices

1. **Use `/clear` frequently**
   - Start fresh for new features
   - After every commit
   - When switching contexts
   - Prevents Claude from running expensive compaction summaries

2. **Be specific in requests**
   - ✅ "Fix N+1 query in PostsController:45"
   - ❌ "Optimize database queries"
   - ✅ "Add validation to UserStoreRequest for email field"
   - ❌ "Add validation to the user form"

3. **Use MCP tools for context**
   - `search-docs` - Laravel/Inertia/Vue documentation
   - `database-query` - Query database directly
   - `tinker` - Execute PHP code
   - `browser-logs` - Read browser errors
   - `list-routes` - See all routes
   - `database-schema` - Inspect database structure

4. **Reference files instead of pasting**
   - ✅ "Check the validation in app/Http/Requests/UserStoreRequest.php"
   - ❌ *pastes entire 200-line file content*

5. **Use agents for research**
   - Let specialized agents gather context
   - Agents return focused summaries
   - Reduces main conversation token usage

### Optimal Workflow Pattern
```bash
/clear                              # Clear context
"Implement X feature"               # State goal
[Claude uses MCP tools]             # Auto-fetches context via Laravel Boost
[Claude implements]                 # Writes code
composer quality && npm run quality # Validate
git commit -m "feat: X"             # Commit
/clear                              # Clear for next task
```

### Caching Strategies
- Use Laravel's cache for expensive operations
- Implement query caching for frequently accessed data
- Use Redis for session storage in production
- Cache API responses when appropriate

### Database Optimization
- Use database indexes for frequently queried columns
- Implement database query caching
- Use eager loading to prevent N+1 queries
- Consider read replicas for scaling

---

## 🎯 Common Pitfalls

1. **N+1 Queries**
   - Problem: Not using eager loading
   - Solution: Use `->with('relation')` for eager loading

2. **Skipping search-docs**
   - Problem: Writing outdated code patterns
   - Solution: Always use `search-docs` before implementing Laravel features

3. **Forgetting /clear**
   - Problem: Wastes tokens on old conversations
   - Solution: Run `/clear` after commits and before new features

4. **Missing type declarations**
   - Problem: PHP/TypeScript without explicit types
   - Solution: Always add return types and parameter types

5. **Using env() directly**
   - Problem: Returns null after config:cache
   - Solution: Always use `config('key')` instead

6. **Creating hooks instead of events**
   - Problem: This project uses event-driven architecture
   - Solution: Create Events and Listeners, NEVER hooks

7. **Inline styles instead of TailwindCSS**
   - Problem: Inconsistent styling, maintenance issues
   - Solution: Use TailwindCSS utility classes

8. **Deleting without asking**
   - Problem: Permanently loses code/files
   - Solution: Always request user approval before deletions

9. **Docs outside docs/ directory**
   - Problem: Disorganized documentation
   - Solution: Keep all .md files in `docs/` (except README.md)

10. **Not running quality checks**
    - Problem: Commits broken/unformatted code
    - Solution: Always run `composer quality && npm run quality` before commit

---

## 🎓 Post-MVP (Future Enhancements)

After MVP launch, consider implementing:

### Subdirectory-Specific CLAUDE.md Files
- `resources/js/CLAUDE.md` - Frontend-specific guidelines (Vue, Inertia, PrimeVue patterns)
- `app/Models/CLAUDE.md` - Model conventions (casts, relationships, factories)
- `tests/CLAUDE.md` - Testing patterns (PHPUnit, factories, coverage expectations)
- `app/Services/CLAUDE.md` - Service layer patterns (business logic, DTOs)

### Advanced Features to Consider
- Hierarchical CLAUDE.md files for better context-specific guidance
- Custom slash commands for common operations
- MCP integration enhancements
- Automated documentation generation
- Performance monitoring and optimization

---

**Last updated:** 2025-10-23
**Version:** 1.3 - Clarified Frontend (Blade) vs Admin Panel (Vue/Inertia) tech stacks
**Project:** PineCMS - Security & Privacy-First Flat-File Hybrid CMS
