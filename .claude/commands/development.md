# Development Commands

## Laravel Artisan Commands

**Use `list-artisan-commands` MCP tool to see all available commands with options.**

### Common Commands

```bash
# Generate files (ALWAYS use make commands)
php artisan make:model Post -mfs  # Model + Migration + Factory + Seeder
php artisan make:controller PostController
php artisan make:request StorePostRequest
php artisan make:class Services/PostService
php artisan make:event PostPublished
php artisan make:listener SendPostNotification

# Database
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear

# Queue
php artisan queue:work
php artisan queue:listen

# Development
php artisan serve
php artisan tinker
```

### Important Flags

**Always use `--no-interaction` for Artisan commands:**
```bash
php artisan migrate --no-interaction
php artisan make:model Post --no-interaction
```

**Why:** Ensures commands work without user input (important for AI execution).

## NPM/Build Commands

```bash
# Development
npm run dev  # Vite dev server with HMR

# Production build
npm run build

# Combined (PHP + Vite)
composer run dev  # Runs both PHP server + Vite

# Clean builds
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

## Git Workflow

### Conventional Commits

```bash
# Format: <type>(<scope>): <subject>

git commit -m "feat(posts): add markdown editor with TipTap"
git commit -m "fix(auth): resolve login redirect issue"
git commit -m "refactor(api): simplify PostService logic"
git commit -m "test(posts): add comprehensive PostController tests"
git commit -m "docs(readme): update installation instructions"
git commit -m "style(admin): improve button spacing"
git commit -m "chore(deps): update Laravel to 12.x"
```

### Types

- `feat` - New feature
- `fix` - Bug fix
- `refactor` - Code refactoring
- `test` - Adding/updating tests
- `docs` - Documentation changes
- `style` - Formatting, spacing (not CSS)
- `chore` - Maintenance, dependencies

### Commit with Co-Author

```bash
git commit -m "feat: implement feature

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```

## Laravel Boost Commands

```bash
# Update AI guidelines
php artisan boost:update

# Check Laravel Boost status
php artisan about
```

## Database Inspection

**Use MCP tools instead of manual commands:**

```
database-schema        # View complete database schema
database-query         # Run SELECT queries
database-connections   # List configured connections
```

## Debugging

**Use MCP tools:**

```
tinker                 # Execute PHP in Laravel context
browser-logs           # Read browser console errors
last-error             # Get last backend exception
```

## Context Management

```bash
# Clear Claude Code context (IMPORTANT!)
/clear

# Use after:
# - Every commit
# - Before new feature
# - When conversation >50 messages
# - If Claude seems confused
```

## Troubleshooting

### Vite Error "Unable to locate file in Vite manifest"

```bash
# Solution 1: Build assets
npm run build

# Solution 2: Start dev server
npm run dev

# Or ask user to run:
composer run dev
```

### MCP Configuration Issues

```bash
# Debug MCP servers
claude --mcp-debug

# List MCP servers
claude mcp list
```
