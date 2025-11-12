# Conventional Commit Command

Create a conventional commit following best practices.

## Conventional Commit Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Type (Required)

- **feat**: New feature for the user
- **fix**: Bug fix for the user
- **docs**: Documentation only changes
- **style**: Code style changes (formatting, missing semi-colons, etc.)
- **refactor**: Code change that neither fixes a bug nor adds a feature
- **perf**: Performance improvements
- **test**: Adding missing tests or correcting existing tests
- **build**: Changes to build system or external dependencies
- **ci**: Changes to CI configuration files and scripts
- **chore**: Other changes that don't modify src or test files
- **revert**: Reverts a previous commit

### Scope (Optional)

The scope provides additional contextual information:

- **backend**: Backend/API changes (controllers, services, models)
- **frontend**: Frontend changes (Vue components, pages)
- **database**: Database migrations, seeders
- **auth**: Authentication/authorization
- **admin**: Admin panel features
- **public**: Public-facing website
- **ui**: UI/UX improvements
- **api**: API endpoints
- **config**: Configuration changes
- **deps**: Dependency updates

### Subject (Required)

- Use imperative, present tense: "add" not "added" nor "adds"
- Don't capitalize first letter
- No period (.) at the end
- Maximum 72 characters

### Body (Optional)

- Use when commit needs more context
- Explain WHAT and WHY, not HOW
- Wrap at 72 characters per line

### Footer (Optional)

- Reference issues: `Closes #123`, `Fixes #456`
- Breaking changes: `BREAKING CHANGE: description`

## Workflow

### Step 1: Review Changes

Run these commands in parallel to understand what changed:

```bash
git status
git diff
git log -5 --oneline
```

**Analyze:**

- What files were changed?
- What functionality was added/modified/removed?
- Are there any uncommitted files that should be included?

### Step 2: Determine Commit Type

Based on the changes, determine the appropriate type:

**Decision tree:**

1. **New feature?** → `feat`
2. **Bug fix?** → `fix`
3. **Documentation only?** → `docs`
4. **Code formatting/style?** → `style`
5. **Code restructuring (no behavior change)?** → `refactor`
6. **Performance improvement?** → `perf`
7. **Test changes?** → `test`
8. **Build/dependency changes?** → `build`
9. **CI/CD changes?** → `ci`
10. **Other?** → `chore`

### Step 3: Determine Scope

Based on the affected area:

- Backend files (app/\*\*/\*.php)? → `backend`
- Frontend files (resources/js/\*\*)? → `frontend`
- Database files (database/\*\*)? → `database`
- Auth files (Auth/\*\*, Policies/\*\*)? → `auth`
- Admin panel? → `admin`
- Public website? → `public`
- Multiple areas? → Use most significant scope or omit

### Step 4: Craft Subject Line

**Good examples:**

- `feat(auth): add two-factor authentication support`
- `fix(backend): resolve N+1 query in user listing`
- `refactor(frontend): extract form validation to composable`
- `docs(readme): update installation instructions`
- `perf(database): add index to posts.published_at column`
- `test(backend): add missing service unit tests`

**Bad examples:**

- ❌ `Added new feature` (not specific, not imperative)
- ❌ `Fix bug.` (has period, not descriptive)
- ❌ `Updated some files` (too vague)
- ❌ `WIP` (not meaningful)

### Step 5: Add Body (If Needed)

Add body if:

- Change is complex and needs explanation
- Breaking change that needs context
- Multiple related changes included
- Decision rationale needs documentation

**Example:**

```
feat(auth): add two-factor authentication support

Implement TOTP-based 2FA using Google Authenticator compatible codes.
Users can enable 2FA in their account settings. Recovery codes are
generated automatically and can be downloaded as a PDF.

This change is part of the security improvements roadmap for Q1 2025.
```

### Step 6: Add Footer (If Applicable)

**Issue references:**

```
Closes #123
Fixes #456, #789
Relates to #999
```

**Breaking changes:**

```
BREAKING CHANGE: User API endpoint /api/user now requires authentication token
```

### Step 7: Stage Files

```bash
git add <files>
```

**Common patterns:**

- `git add .` - Stage all changes (use carefully!)
- `git add app/` - Stage all backend changes
- `git add resources/js/` - Stage all frontend changes
- `git add tests/` - Stage all test changes
- `git add database/migrations/*.php` - Stage specific migrations

**⚠️ Never commit:**

- `.env` files (use `.env.example` instead)
- `node_modules/`
- `vendor/`
- IDE-specific files
- Sensitive data (API keys, passwords, secrets)
- Debug files (`*.log`, `.DS_Store`)

### Step 8: Create Commit

Use HEREDOC format for proper multiline commit messages:

```bash
git commit -m "$(cat <<'EOF'
<type>(<scope>): <subject>

<body>

<footer>

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
EOF
)"
```

**Example:**

```bash
git commit -m "$(cat <<'EOF'
feat(auth): add two-factor authentication support

Implement TOTP-based 2FA using Google Authenticator compatible codes.
Users can enable 2FA in their account settings. Recovery codes are
generated automatically and can be downloaded as PDF.

Closes #123

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
EOF
)"
```

### Step 9: Verify Commit

```bash
git log -1
git status
```

**Check:**

- ✅ Commit message follows conventional format
- ✅ Subject line is clear and descriptive
- ✅ No typos in commit message
- ✅ Working directory is clean (or has expected uncommitted files)

### Step 10: Push (Optional)

If ready to push:

```bash
git push
```

Or if setting upstream for first push:

```bash
git push -u origin <branch-name>
```

## Examples for PineCMS

### New Features

```
feat(admin): add drag-and-drop page reordering
feat(backend): implement content versioning system
feat(frontend): add markdown preview in editor
feat(database): add soft deletes to posts table
```

### Bug Fixes

```
fix(auth): resolve session timeout causing logout loop
fix(backend): prevent N+1 queries in posts index
fix(frontend): correct date formatting in post list
fix(admin): fix missing validation on page create form
```

### Refactoring

```
refactor(backend): extract content parsing to service
refactor(frontend): consolidate form validation logic
refactor(database): optimize eager loading relationships
```

### Documentation

```
docs(readme): update installation steps for PHP 8.3
docs(api): add missing endpoint documentation
docs(architecture): document event-driven design decisions
```

### Testing

```
test(backend): add unit tests for UserService
test(frontend): add e2e tests for login flow
test(integration): add tests for file upload
```

### Performance

```
perf(backend): add caching to navigation menu
perf(database): add composite index for search queries
perf(frontend): lazy-load admin panel components
```

### Chores

```
chore(deps): update Laravel to 12.x
chore(config): update PHPStan to level 8
chore: remove obsolete example tests
```

## Special Cases

### Multiple Changes in One Commit

If changes span multiple types, use the most significant one:

```
feat(backend): add user roles and permissions system

- Add Role and Permission models with relationships
- Create authorization policies for all resources
- Add middleware for permission checking
- Update user interface to show role badges

This implements the authorization layer described in ADR-003.

Closes #45, #67, #89
```

### Reverting Commits

```
revert: feat(auth): add two-factor authentication

This reverts commit abc123def456.

Reason: 2FA implementation causes session conflicts on mobile devices.
Will be reimplemented after session handling refactor.

Reopens #123
```

### Breaking Changes

```
feat(api)!: change user endpoint response format

BREAKING CHANGE: The /api/user endpoint now returns paginated results
with metadata. Update API clients to handle the new response structure:

Before: { users: [...] }
After: { data: [...], meta: {...}, links: {...} }

Migration guide: https://docs.pinecms.com/api/v2-migration

Closes #234
```

## Pre-Commit Checklist

Before creating commit, verify:

- [ ] All changes are intentional (no debug code, console.logs)
- [ ] Tests are passing (`composer test`, `npm test`)
- [ ] Code is formatted (`vendor/bin/pint`, `npm run format`)
- [ ] No linting errors (`npm run lint`)
- [ ] No type errors (`npm run type-check`, `composer analyse`)
- [ ] No secrets or sensitive data in commit
- [ ] Commit message follows conventional format
- [ ] Commit message is clear and descriptive

**Quick check:**

```bash
composer quality  # Backend
npm run quality   # Frontend
```

## Notes

- Use `/pr-ready` for full pre-PR verification
- Use `/build-and-fix` if quality checks fail
- Commit early, commit often (small, focused commits are better)
- One commit = one logical change
- Follow the repository's existing commit message style

---

**Usage:** `/commit`
