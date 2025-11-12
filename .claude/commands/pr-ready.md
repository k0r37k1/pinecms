# PR Ready Command

Verify this branch is ready for a Pull Request.

## Overview

This command performs a comprehensive pre-PR checklist to ensure:

- ✅ All quality gates pass
- ✅ Git status is clean and ready
- ✅ Documentation is updated
- ✅ Security considerations are addressed
- ✅ Testing is adequate

## Checklist Workflow

### 1. Quality Gates ✓

Run all quality checks and verify they pass:

#### Backend Quality

```bash
composer analyse    # PHPStan level 8
vendor/bin/pint --test  # Code style
composer test       # PHPUnit
```

**Required:**

- ✅ 0 PHPStan errors
- ✅ 0 Pint violations (or auto-fixed)
- ✅ All tests passing (green)

#### Frontend Quality

```bash
npm run type-check  # TypeScript
npm run lint        # ESLint
npm test            # Vitest
```

**Required:**

- ✅ 0 TypeScript errors
- ✅ 0 ESLint errors
- ✅ All tests passing (green)

#### Quick Quality Check

Alternatively, run combined commands:

```bash
composer quality    # Backend: format, analyze, test
npm run quality     # Frontend: format, lint, type-check, test
```

Both should complete successfully without errors.

### 2. Git Status ✓

Verify git repository state:

```bash
git status
git log -5 --oneline
git diff main...HEAD  # Compare with main branch
```

**Required:**

- [ ] All intended changes are committed
- [ ] Working directory is clean (no untracked/uncommitted files)
- [ ] Branch is up to date with main/master
- [ ] No merge conflicts
- [ ] All commits follow conventional commit format
- [ ] Commit messages are clear and descriptive

#### Check for Merge Conflicts

```bash
git fetch origin main
git merge-base HEAD origin/main
git diff --check
```

If behind main, rebase or merge:

```bash
git pull origin main --rebase  # Preferred
# OR
git merge origin/main          # Alternative
```

#### Review Commit Messages

```bash
git log main..HEAD --oneline
```

Each commit should:

- Follow conventional commit format: `type(scope): subject`
- Have clear, descriptive subjects
- Use imperative mood ("add" not "added")
- Be focused (one logical change per commit)

### 3. Documentation ✓

Check if documentation needs updates:

#### CHANGELOG.md

If this is a notable change:

```bash
cat CHANGELOG.md | head -n 20
```

**Add entry if:**

- New feature (feat)
- Bug fix that affects users
- Breaking change
- Security fix
- Performance improvement

**Format:**

```markdown
## [Unreleased]

### Added

- New feature description (#123)

### Fixed

- Bug fix description (#456)

### Changed

- Breaking change description (#789)
```

#### README.md

Check if README needs updates:

```bash
cat README.md
```

**Update if:**

- Installation steps changed
- New dependencies added
- New configuration required
- New features affect setup/usage

#### API Documentation

If API changes were made:

```bash
ls routes/*.php
```

**Verify:**

- All new endpoints are documented
- Request/response examples are accurate
- Authentication requirements are clear
- Rate limiting is documented

#### Code Comments

**Review changed files for:**

- [ ] PHPDoc blocks for public methods
- [ ] Complex logic has explanatory comments
- [ ] TODO/FIXME comments are addressed or tracked
- [ ] No commented-out code (remove or document why)

### 4. Security ✓

Perform security checks:

#### No Secrets in Code

```bash
git diff main...HEAD | grep -i "password\|api_key\|secret\|token"
```

**Verify:**

- [ ] No hardcoded passwords, API keys, secrets
- [ ] No `.env` file changes committed
- [ ] No credentials in comments or strings
- [ ] Secrets use `config()` helper, not `env()`

#### Input Validation

Review changed files for user input:

**Check:**

- [ ] All user input is validated (Form Requests)
- [ ] File uploads have type/size validation
- [ ] SQL queries use parameter binding (no raw queries)
- [ ] Data is sanitized before output

#### Authorization Checks

For controllers/endpoints:

**Verify:**

- [ ] Authorization checks in place (policies, gates)
- [ ] Middleware applied correctly
- [ ] Permission checks for sensitive operations
- [ ] User can only access their own data

#### CSRF Protection

For forms:

**Verify:**

- [ ] Inertia forms include CSRF token (automatic)
- [ ] Blade forms use `@csrf` directive
- [ ] API routes in `api.php` (stateless, no CSRF)

#### XSS Prevention

For views/components:

**Verify:**

- [ ] User content is escaped (Blade `{{ }}`, Vue `{{ }}`)
- [ ] Raw HTML only with trusted content
- [ ] No `v-html` with user content
- [ ] Content Security Policy configured

### 5. Testing ✓

Verify test coverage is adequate:

#### Test Coverage Report

```bash
composer test:coverage  # If available
npm run test:coverage   # If available
```

**Review:**

- [ ] New features have unit tests
- [ ] Critical paths have feature tests
- [ ] Edge cases are tested
- [ ] Error scenarios are tested

#### Test Quality

**Check:**

- [ ] Tests are not brittle (don't rely on specific data)
- [ ] Tests use factories for models
- [ ] Tests clean up after themselves
- [ ] Tests are fast (no unnecessary sleeps)

#### Manual Testing

If UI changes:

**Verify:**

- [ ] Tested in browser (desktop)
- [ ] Tested responsive design (mobile)
- [ ] Tested in supported browsers
- [ ] No console errors in browser

### 6. Performance ✓

Quick performance check:

#### N+1 Query Prevention

For Eloquent queries:

```bash
git diff main...HEAD | grep -A 5 "::all()\|::get()"
```

**Verify:**

- [ ] Eager loading used for relationships (`->with()`)
- [ ] No N+1 queries in loops
- [ ] Pagination used for large datasets

#### Database Indexes

For new migrations:

**Check:**

- [ ] Foreign keys have indexes
- [ ] Frequently queried columns have indexes
- [ ] Composite indexes for multi-column queries

#### Frontend Bundle Size

If significant frontend changes:

```bash
npm run build
ls -lh public/build/assets/
```

**Check:**

- [ ] No unexpectedly large bundle sizes
- [ ] Code splitting used for large components
- [ ] Images optimized

### 7. Breaking Changes ✓

Check if PR introduces breaking changes:

**Identify:**

- [ ] Database schema changes requiring migration
- [ ] API response format changes
- [ ] Configuration changes
- [ ] Dependency version bumps (major versions)
- [ ] Removed public methods/functions

**If breaking changes:**

- [ ] Document in CHANGELOG.md under `### Changed`
- [ ] Add `BREAKING CHANGE:` in commit footer
- [ ] Use `!` in commit type: `feat(api)!: ...`
- [ ] Create migration guide (if needed)
- [ ] Update version number appropriately

## Final Verification Commands

Run these commands and ensure all pass:

```bash
# Backend
composer quality
echo "✅ Backend quality check passed"

# Frontend
npm run quality
echo "✅ Frontend quality check passed"

# Git status
git status
git log -5 --oneline
git diff main...HEAD --stat
```

## Output Format

### ✅ Ready for PR

```markdown
## PR Readiness Report ✅

### Quality Gates

✅ Backend: All checks passing
✅ Frontend: All checks passing

### Git Status

✅ All changes committed
✅ Working directory clean
✅ Up to date with main
✅ 5 conventional commits
✅ No merge conflicts

### Documentation

✅ CHANGELOG.md updated
✅ README.md reviewed (no changes needed)
✅ PHPDoc blocks added for new methods
✅ No TODO/FIXME comments

### Security

✅ No secrets in code
✅ Input validation in place
✅ Authorization checks added
✅ CSRF protection verified

### Testing

✅ 12 new tests added
✅ All tests passing (100% success rate)
✅ Coverage: 85% (meets minimum 80%)
✅ Manual testing completed

### Performance

✅ Eager loading used for relationships
✅ Database indexes added
✅ No N+1 queries detected

### Breaking Changes

✅ No breaking changes

---

## Suggested PR Details

**Title:**
`feat(auth): add two-factor authentication support`

**Description:**

## Summary

Implements TOTP-based two-factor authentication using Google Authenticator compatible codes.

## Changes

- Add 2FA models and migrations
- Create 2FA setup/verify controllers
- Add Vue components for 2FA management
- Include recovery code generation and download
- Add comprehensive test coverage

## Testing

- ✅ Unit tests for 2FA service
- ✅ Feature tests for 2FA flow
- ✅ Manual testing in Chrome, Firefox, Safari
- ✅ Mobile responsive design verified

## Screenshots

[Include if UI changes]

Closes #123
```

---

### ❌ Not Ready for PR

```markdown
## PR Readiness Report ❌

### Issues Found

❌ **Backend Quality (3 issues)**

- PHPStan: 2 errors in UserService.php
- Tests: 1 failing test in AuthTest.php

❌ **Git Status (2 issues)**

- 3 files with uncommitted changes
- Branch is 5 commits behind main

❌ **Documentation (1 issue)**

- CHANGELOG.md not updated

❌ **Security (1 issue)**

- Missing authorization check in UserController::destroy

### Action Required

1. Run `/build-and-fix` to fix quality issues
2. Commit remaining changes
3. Rebase on main: `git pull origin main --rebase`
4. Update CHANGELOG.md
5. Add authorization check to UserController

Run `/pr-ready` again after addressing these issues.
```

## PineCMS-Specific Checks

### Hybrid Storage

If content/markdown files changed:

- [ ] Flat-file storage paths are correct
- [ ] File permissions set appropriately
- [ ] Backup/restore tested

### Event-Driven Architecture

If events/listeners added:

- [ ] Events dispatched in correct places
- [ ] Listeners are registered in EventServiceProvider
- [ ] Side effects are isolated in listeners
- [ ] **NO custom Hooks used** (Laravel Events only!)

### Laravel 12 Conventions

- [ ] Uses new streamlined structure
- [ ] Middleware registered in `bootstrap/app.php`
- [ ] Commands auto-register (no manual registration)
- [ ] Casts use `casts()` method (not `$casts` property)

### Spatie Guidelines

- [ ] Happy path last (early returns)
- [ ] Type declarations on all methods
- [ ] `declare(strict_types=1);` in all PHP files
- [ ] Docblocks for public methods

## Quick Commands

```bash
# Full quality check
composer quality && npm run quality

# Git status check
git status && git log -5 --oneline

# Documentation check
cat CHANGELOG.md | head -n 30

# Security check (no secrets)
git diff main...HEAD | grep -i "password\|api_key\|secret\|token"

# Test coverage
composer test:coverage && npm run test:coverage
```

## Notes

- Use `/commit` for conventional commits before PR
- Use `/build-and-fix` if quality checks fail
- Use `/check` for lighter pre-commit verification
- PR template will be auto-populated from commits
- Squashing commits? Wait until after PR approval

---

**Usage:** `/pr-ready`
