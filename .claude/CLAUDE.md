# PineCMS - Security & Privacy-First Flat-File Hybrid CMS

## Project Overview
Laravel-based flat-file hybrid CMS with Vue 3, Inertia.js, Alpine.js, TailwindCSS, and TipTap editor. Event-driven architecture with strict security and privacy focus.

**Stack:** PHP 8.3 | Laravel 12 | Vue 3 | Inertia.js | Alpine.js | TailwindCSS 4 | Vite

---

## Common Commands

### PHP/Backend
```bash
composer install              # Install PHP dependencies
composer test                 # Run PHPUnit tests with coverage
composer test:unit           # Run unit tests only
composer test:feature        # Run feature tests only
composer test:mutation       # Run Infection mutation tests (min 80% MSI)
composer analyse             # Run PHPStan static analysis
composer format              # Format code with Laravel Pint
composer format:check        # Check code formatting
composer rector              # Preview Rector refactorings
composer rector:fix          # Apply Rector refactorings
composer deptrac             # Check architecture dependencies
composer security            # Security vulnerability check
composer quality             # Run format, analyse, deptrac, test
composer ci                  # Full CI pipeline
```

### JavaScript/Frontend
```bash
npm install                  # Install Node dependencies
npm run dev                  # Start Vite dev server
npm run build                # Build for production
npm run preview              # Preview production build
npm run test                 # Run Vitest unit tests
npm run test:ui              # Run Vitest with UI
npm run test:coverage        # Generate test coverage report
npm run test:e2e             # Run Playwright E2E tests
npm run test:e2e:ui          # Run Playwright with UI
npm run lint                 # Lint JavaScript/TypeScript
npm run lint:fix             # Auto-fix linting issues
npm run lint:css             # Lint CSS files
npm run lint:css:fix         # Auto-fix CSS linting issues
npm run format               # Format code with Prettier
npm run format:check         # Check code formatting
npm run type-check           # TypeScript type checking
npm run quality              # Run all quality checks
```

---

## MCP Server Usage

### ALWAYS Use Before Coding
**YOU MUST fetch documentation BEFORE implementing any feature.**
- **Laravel-specific content**: Use `laravel-mcp-companion` FIRST (official Laravel docs, packages)
- **Other libraries**: Use `context7` (Vue, Inertia, TipTap, Alpine, etc.)

This prevents hallucinated APIs and ensures version-correct implementations.

### Available MCP Servers

#### Project-Specific (Enabled in .mcp.json)
- **eslint**: Lint JS/TS after code changes, before commits
- **chrome-devtools**: Browser automation, performance analysis, debugging

#### Global MCP Servers (Use as needed)
- **laravel-mcp-companion**: **PRIORITY** for Laravel docs, packages, and ecosystem (use FIRST for Laravel)
- **context7**: Get library docs (Vue, Inertia, TipTap, Alpine, other non-Laravel libraries)
- **gitmcp**: Research GitHub repos, study implementations
- **firecrawl-mcp**: Deep web scraping, documentation extraction
- **brave-search**: Quick web searches for latest info
- **playwright**: Cross-browser E2E testing
- **coderabbitai**: AI-powered code reviews

### Task-Based MCP Selection

**Writing New Feature:**
1. `laravel-mcp-companion` - Fetch Laravel docs/packages FIRST (if Laravel-related)
2. `context7` - Fetch other library/framework docs (Vue, Inertia, etc.)
3. Write code following documentation
4. `eslint` - Lint JS/TS code
5. `chrome-devtools` - Test in browser (if frontend)

**Debugging:**
1. `chrome-devtools` - Inspect browser state, console
2. `eslint` - Check code quality
3. `laravel-mcp-companion` - Verify Laravel API usage
4. `context7` - Verify other library API usage

**Research:**
1. `laravel-mcp-companion` - Laravel-specific research (docs, packages, features)
2. `brave-search` / `firecrawl-mcp` - Web research
3. `gitmcp` - Study similar implementations
4. `context7` - Official documentation (non-Laravel)

---

## Code Style & Standards

### PHP
- **PHP 8.3 strict types**: `declare(strict_types=1);` in all PHP files
- **PSR-12 coding standard**: Enforced by Laravel Pint
- **Dependency Injection**: Use Laravel's container, constructor injection
- **Events over Hooks**: ALWAYS use Events, NEVER use Hooks
- **Repository Pattern**: Data access via Repositories
- **Service Layer**: Business logic in Services
- **DTOs**: Data transfer between layers
- **Type Safety**: Strict types, typehints, return types
- **Security First**: Validate all inputs, escape outputs

### JavaScript/TypeScript
- **ES Modules**: Use `import/export`, NOT CommonJS `require`
- **TypeScript**: Use types for all function parameters and returns
- **Vue 3 Composition API**: Use `<script setup>` syntax
- **Destructure imports**: `import { ref, computed } from 'vue'`
- **TailwindCSS**: Use utility classes, avoid inline styles
- **Alpine.js**: Use for simple interactivity
- **Inertia.js**: Use for page navigation, form handling

### General
- **Naming**: camelCase (JS/TS), snake_case (PHP)
- **Comments**: Document WHY, not WHAT
- **Functions**: Small, focused, single responsibility
- **Error Handling**: Graceful degradation, user-friendly messages
- **Security**: Never trust user input, always validate

---

## Architecture & Design

### Event-Driven Architecture
- **Events**: Located in `app/Events/`
- **Listeners**: Located in `app/Listeners/`
- **IMPORTANT**: Use Events for cross-cutting concerns (logging, notifications, audit)
- **NO HOOKS**: This project uses Events, not Hooks

### Layer Architecture
```
Controllers (HTTP) → Services (Business Logic) → Repositories (Data Access)
                  ↓
                DTOs (Data Transfer)
                  ↓
                Events (Side Effects)
```

### Security Principles
- **Content Security Policy**: Configured via Spatie CSP
- **Secure Headers**: Configured via bepsvpt/secure-headers
- **Input Validation**: Use Laravel's validation in Requests
- **Output Escaping**: Blade auto-escapes, manual escaping where needed
- **Authentication Logging**: Track auth attempts via rappasoft/laravel-authentication-log
- **Activity Logging**: Track user actions via spatie/laravel-activitylog
- **Permissions**: Role-based via spatie/laravel-permission

### Key Technologies
- **Flat-File Content**: YAML Front Matter + Markdown via Spatie/CommonMark
- **Image Processing**: Intervention Image (v3)
- **JWT**: Firebase PHP-JWT for API authentication
- **Encryption**: Laravel Encryption component

---

## Workflow Best Practices

### Development Workflow
1. **Plan First**: Ask me to "think hard" and create a plan before coding
2. **Research**: Use `laravel-mcp-companion` (Laravel) or `context7` (other libs) to fetch documentation before implementation
3. **Test-Driven**: Write tests first when possible
4. **Implement**: Write clean, documented code
5. **Quality Check**: Run linters, formatters, static analysis
6. **Test**: Run relevant test suites
7. **Commit**: Clear, atomic commits

### Testing Workflow (Recommended)
1. Ask me to write tests (mention "TDD" explicitly)
2. Run tests and confirm they fail
3. Commit tests
4. Write code to pass tests
5. Run tests until all pass
6. Run mutation tests (Infection)
7. Commit implementation

### Before Every Commit
```bash
# PHP
composer format              # Format code
composer analyse             # Static analysis
composer test                # Run tests

# JavaScript
npm run format               # Format code
npm run lint:fix             # Fix linting issues
npm run type-check           # Type check
npm run test                 # Run tests
```

---

## Testing Strategy

### PHP Testing
- **PHPUnit**: Unit and Feature tests
- **Mockery**: Mocking dependencies
- **Infection**: Mutation testing (min 80% MSI, 90% covered MSI)
- **Coverage**: Generate HTML reports via `composer test:coverage`

### JavaScript Testing
- **Vitest**: Unit tests for Vue components, composables
- **Playwright**: E2E tests for user workflows
- **Testing Library**: Vue Testing Library for component tests
- **Coverage**: V8 coverage via `npm run test:coverage`

### Quality Gates
- **PHPStan**: Level max, strict rules
- **Deptrac**: Architecture boundaries
- **Security Checker**: Vulnerability scanning
- **Type Coverage**: TypeScript strict mode

---

## Git & GitHub

### Commit Messages
- Clear, concise, descriptive
- Format: `<type>: <description>`
- Types: feat, fix, refactor, test, docs, style, chore
- Example: `feat: add user authentication logging`

### Workflow
- Atomic commits (one logical change per commit)
- Feature branches from main
- Pull requests for review
- Use `gh` CLI for GitHub operations

### GitHub CLI Examples
```bash
gh issue view 123            # View issue details
gh pr create                 # Create pull request
gh pr view                   # View current PR
gh pr checks                 # Check CI status
```

---

## Environment & Configuration

### Required Environment Variables
```bash
APP_ENV=local
APP_DEBUG=true
APP_KEY=                     # Generate via: php artisan key:generate

DB_CONNECTION=sqlite         # Flat-file primary, DB optional
DB_DATABASE=/path/to/db.sqlite

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Security
SECURE_HEADERS_ENABLED=true
CSP_ENABLED=true

# Content
CONTENT_PATH=storage/content
MARKDOWN_PARSER=commonmark
```

### Configuration Files
- `.env` - Environment variables (NOT in git)
- `.env.example` - Example config (in git)
- `config/` - Laravel config files
- `vite.config.js` - Vite configuration
- `phpstan.neon` - PHPStan config
- `pint.json` - Laravel Pint config
- `deptrac.yaml` - Deptrac architecture rules
- `infection.json5` - Mutation testing config

---

## Common Issues & Solutions

### Vite Not Starting
- Check Node version: `node -v` (needs >=20.0.0)
- Clear cache: `rm -rf node_modules/.vite`
- Reinstall: `npm install`

### PHPStan Memory Issues
- Already configured: `--memory-limit=2G`
- Generate baseline if needed: `composer analyse:baseline`

### Infection Mutation Tests Failing
- Check MSI threshold: min 80%, covered min 90%
- Generate baseline: Add to `infection.json5`

### TypeScript Errors
- Run type check: `npm run type-check`
- Check `tsconfig.json` paths
- Ensure all `.ts` files have proper types

---

## Dependencies

### Core PHP Dependencies
- Laravel Illuminate Components (12.x)
- Spatie Laravel Permission, ActivityLog, CSP, YAML Front Matter
- Inertia.js Laravel
- League CommonMark
- Intervention Image
- Firebase PHP-JWT

### Core JavaScript Dependencies
- Vue 3, Vue Router, Vue i18n
- Inertia.js Vue3
- Alpine.js (with plugins: collapse, focus, intersect, mask, persist)
- TipTap Editor (with extensions)
- Pinia (state management)
- VueUse
- Axios

### Development Tools
- PHPStan, Rector, PHPUnit, Mockery, Infection
- Laravel Pint (PHP CS Fixer)
- ESLint, Stylelint, Prettier
- Vitest, Playwright
- Vite, Tailwind CSS

---

## Documentation

### Documentation Location
**IMPORTANT**: All documentation (.md files) MUST be stored in `docs/` directory.
- Check if `docs/` exists before creating docs
- Organize in subdirectories: `docs/api/`, `docs/guides/`, etc.
- Exception: `README.md` can stay in project root

### Documentation Standards
- Use Markdown
- Include code examples
- Document API endpoints, request/response formats
- Document Events, Services, Repositories
- Keep up-to-date with code changes

---

## Troubleshooting Tips

### Performance Issues
- Enable Opcache in production
- Run `composer dump-autoload --optimize`
- Minimize Vite bundle size
- Use lazy loading for Vue components

### Security Debugging
- Check CSP violations in browser console
- Review `storage/logs/laravel.log`
- Use `php-debugbar` in development
- Enable Tracy/Whoops for detailed errors

### Database Issues
- This is primarily a flat-file CMS
- Database is optional for auth/permissions
- Content stored in `storage/content/` as YAML + Markdown

---

## Resources

### Official Documentation
- Laravel: https://laravel.com/docs/12.x
- Vue 3: https://vuejs.org/guide/
- Inertia.js: https://inertiajs.com/
- Alpine.js: https://alpinejs.dev/
- TipTap: https://tiptap.dev/docs/editor/introduction
- TailwindCSS: https://tailwindcss.com/docs

### Package Documentation
- Spatie Laravel Permission: https://spatie.be/docs/laravel-permission/
- Spatie Laravel ActivityLog: https://spatie.be/docs/laravel-activitylog/
- Spatie Laravel CSP: https://spatie.be/docs/laravel-csp/
- League CommonMark: https://commonmark.thephpleague.com/

### Internal Documentation
- Architecture decisions: `docs/architecture/`
- API documentation: `docs/api/`
- Security guidelines: `docs/security/`

---

## IMPORTANT Reminders

1. **YOU MUST use `laravel-mcp-companion` for Laravel OR `context7` for other libraries BEFORE writing any code**
2. **ALWAYS use Events, NEVER use Hooks**
3. **Run tests BEFORE committing**
4. **Security first: validate inputs, escape outputs**
5. **Follow PSR-12 (PHP) and project ESLint config (JS/TS)**
6. **Think hard before complex implementations**
7. **Write tests first when possible (TDD)**
8. **Document in `docs/` directory only**
9. **Flat-file first, database optional**
10. **Keep dependencies up-to-date and audit for security**
