# Git Workflow & Commit Guidelines

## Conventional Commits (MANDATORY)

### Format

```
<type>(<scope>): <subject>

[optional body]

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
```

### Types

| Type | Description | Example |
|------|-------------|---------|
| `feat` | New feature | `feat(posts): add markdown editor` |
| `fix` | Bug fix | `fix(auth): resolve login redirect` |
| `refactor` | Code refactoring | `refactor(api): simplify PostService` |
| `test` | Add/update tests | `test(posts): add PostController tests` |
| `docs` | Documentation | `docs(readme): update install guide` |
| `style` | Formatting, spacing | `style(admin): improve spacing` |
| `chore` | Maintenance, deps | `chore(deps): update Laravel to 12` |
| `perf` | Performance | `perf(posts): optimize query loading` |
| `ci` | CI/CD changes | `ci(github): add test workflow` |
| `build` | Build system | `build(vite): update config` |
| `revert` | Revert commit | `revert: revert feat(posts)` |

### Scopes (Optional but Recommended)

- `posts`, `users`, `comments` - Feature/module
- `auth`, `api`, `admin` - Area of codebase
- `frontend`, `backend` - Layer
- `deps`, `config` - Category

## Git Safety Protocol

### NEVER Do These Without Approval:

❌ `git push --force` (especially to main/master)
❌ `git reset --hard` (destructive)
❌ `git commit --amend` (unless recent commit by you)
❌ Update git config
❌ Skip hooks (--no-verify, --no-gpg-sign)
❌ Force push to main/master

### Always Safe:

✅ `git status`
✅ `git log`
✅ `git diff`
✅ `git add`
✅ `git commit`
✅ `git push` (to feature branch)
✅ `git pull`
✅ `git checkout -b feature-name`

## Standard Commit Workflow

### 1. Check Status & Diff

```bash
# See what changed
git status

# See actual changes (staged)
git diff --staged

# See actual changes (unstaged)
git diff
```

### 2. Add Files

```bash
# Add specific files
git add app/Services/PostService.php
git add tests/Feature/PostServiceTest.php

# Add all changes (use with caution)
git add .
```

### 3. Commit with Message

**Use HEREDOC for proper formatting:**

```bash
git commit -m "$(cat <<'EOF'
feat(posts): implement markdown editor with TipTap

- Add TipTap editor component
- Configure markdown extensions
- Add image upload support
- Write comprehensive tests

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
EOF
)"
```

### 4. Verify Commit

```bash
# Check commit was created
git status

# See recent commits
git log --oneline -5
```

### 5. Push to Remote

```bash
# First time (set upstream)
git push -u origin feature-branch

# Subsequent pushes
git push
```

## Pre-Commit Hook Handling

### If Commit Fails Due to Pre-Commit Hook:

**Scenario: Pre-commit hook modifies files**

```bash
# 1. Check what was modified
git status

# 2. Check authorship
git log -1 --format='%an %ae'

# 3. Check not pushed
git status  # Should show "Your branch is ahead"

# 4. If both checks pass, can amend
git add .
git commit --amend --no-edit

# 5. If checks fail, create NEW commit
git add .
git commit -m "style: apply pre-commit hook fixes"
```

**NEVER amend commits:**
- Made by other developers
- Already pushed to remote
- That you don't own

## Creating Pull Requests

### Preparation

```bash
# 1. Check current state
git status
git diff
git log origin/main..HEAD  # See all commits in PR

# 2. Ensure branch is up to date
git pull origin main
```

### Create PR with gh CLI

```bash
gh pr create --title "feat: Add multi-tenant architecture" --body "$(cat <<'EOF'
## Summary
- Implemented tenant identification middleware
- Added tenant scopes to all models
- Migrated existing data to default tenant
- Added comprehensive tests

## Test plan
- [ ] Run full test suite
- [ ] Test tenant isolation
- [ ] Test tenant switching
- [ ] Verify existing data migrated correctly

🤖 Generated with [Claude Code](https://claude.com/claude-code)
EOF
)"
```

### PR Best Practices

✅ **Small, focused PRs** (easier to review)
✅ **Descriptive title** (conventional commit format)
✅ **Summary section** (what changed and why)
✅ **Test plan** (how to verify)
✅ **Screenshots** (for UI changes)

❌ **Avoid:**
- Mixing features and fixes
- PRs with >500 lines changed
- Undocumented breaking changes

## Branch Naming

### Convention

```
<type>/<short-description>

Examples:
feature/multi-tenant-architecture
fix/login-redirect-issue
refactor/post-service-cleanup
test/post-controller-coverage
docs/api-documentation
```

### Creating Branches

```bash
# From main
git checkout main
git pull
git checkout -b feature/new-feature

# From current branch
git checkout -b fix/bug-fix
```

## Common Scenarios

### Scenario 1: Need to Update Branch

```bash
# Save current work
git add .
git commit -m "wip: save progress"

# Update from main
git pull origin main

# Continue working
```

### Scenario 2: Accidentally Committed to Main

```bash
# Create branch from current state
git checkout -b feature/my-feature

# Reset main (if not pushed)
git checkout main
git reset --hard origin/main

# Continue on feature branch
git checkout feature/my-feature
```

### Scenario 3: Need to Undo Last Commit

```bash
# Keep changes, undo commit
git reset --soft HEAD~1

# Discard changes AND commit (DANGEROUS!)
git reset --hard HEAD~1  # Ask user first!
```

## After Commit: Context Hygiene

**ALWAYS run after committing:**

```bash
/clear  # In Claude Code
```

**Why:**
- Resets conversation context
- Prevents context pollution
- Saves tokens for next session
- Ensures fresh start

## Commit Message Examples

### Good Examples

```bash
# Feature with body
git commit -m "feat(auth): implement two-factor authentication

- Add TOTP support using otplib
- Create QR code generation endpoint
- Add backup codes functionality
- Write comprehensive tests for 2FA flow

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"

# Simple fix
git commit -m "fix(posts): resolve N+1 query in PostController"

# Refactoring
git commit -m "refactor(services): extract PostService from controller"

# Tests
git commit -m "test(auth): add comprehensive login tests"
```

### Bad Examples

```bash
# ❌ Too vague
git commit -m "fix stuff"

# ❌ No type
git commit -m "updated PostController"

# ❌ Too detailed in subject
git commit -m "fix the bug where users couldn't login because of redirect"
# Should be: fix(auth): resolve login redirect issue

# ❌ Multiple changes
git commit -m "feat: add posts and fix auth and update tests"
# Should be separate commits
```

## Checklist Before Committing

- [ ] Quality checks passed (`composer quality && npm run quality`)
- [ ] All tests passing
- [ ] No debug code (console.log, dd, dump)
- [ ] Conventional commit message format
- [ ] Only related changes in commit
- [ ] Co-author attribution added

**Then:**

```bash
git commit -m "..."
/clear  # IMPORTANT!
```
