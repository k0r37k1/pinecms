# Code Review Commands

Proactive code review workflow to catch errors early.
Based on: "Claude Code is a Beast - Tips from 6 Months" Reddit post.

---

## /code-review - Architectural Code Review

Launch the `code-reviewer` agent to review recently changed code for:

- Best practices adherence
- Architecture consistency
- Security flaws
- Missing implementations
- Code quality issues

**When to use:**

- After implementing a feature or significant changes
- Before creating a PR
- Periodically during long implementation sessions
- When you want a "second pair of eyes"

**Process:**

1. Identify files changed in current session (use git status/diff)
2. Launch `code-reviewer` agent with context about what was implemented
3. Agent reviews code against:
    - `.claude/instructions/` (backend, frontend, security, architecture)
    - Project patterns and conventions
    - SOLID principles
    - Laravel/Vue best practices
4. Return detailed review with:
    - Critical issues (must fix)
    - Warnings (should fix)
    - Suggestions (nice to have)
    - Positive feedback (what's done well)

**Agent prompt:**

```
Please review the following files that were recently modified:

[List of changed files]

Context:
[Brief description of what was implemented]

Check for:
- Adherence to project patterns (Services, Repositories, Events, etc.)
- Security issues (XSS, SQL injection, CSRF, etc.)
- Missing error handling
- N+1 query problems
- Inconsistent code style
- Missing tests
- Architecture violations
- Any other issues you notice

Provide specific feedback with file:line references.
```

---

## /build-and-fix - Run Builds & Fix All Errors

Systematically run all build checks and fix errors until clean.

**When to use:**

- Before committing code
- After completing a feature
- When you suspect there might be errors
- Before creating a PR

**Process:**

1. **TypeScript Check** (if frontend modified):

    ```bash
    npx tsc --noEmit
    ```

2. **PHPStan Check** (if backend modified):

    ```bash
    composer analyse
    ```

3. **For each error found:**
    - Show error
    - Explain what's wrong
    - Fix it
    - Re-run check to verify

4. **If > 10 errors:**
    - Consider launching `debugger` agent
    - Work through errors systematically

5. **Final verification:**

    ```bash
    composer quality  # PHP: format + analyze + test
    npm run quality   # JS: format + lint + type-check + test
    ```

---

## /quick-quality - Quick Quality Check

Fast quality check without full test suite (useful during development).

**Process:**

```bash
# Backend
vendor/bin/pint --dirty  # Format changed files only
composer analyse         # PHPStan

# Frontend
npx prettier --write $(git diff --name-only --diff-filter=ACM | grep -E '\.(vue|ts|js)$')
npx eslint $(git diff --name-only --diff-filter=ACM | grep -E '\.(vue|ts|js)$')
npx tsc --noEmit
```

**Output:**

- ✅ All checks passed → Ready to commit
- ❌ Errors found → Fix and re-run

---

## /pr-ready - Full Pre-PR Checklist

Complete checklist before creating a Pull Request.

**Checklist:**

```markdown
## Code Quality

- [ ] `composer quality` passes
- [ ] `npm run quality` passes
- [ ] No console.log statements
- [ ] No commented-out code
- [ ] No debug code

## Testing

- [ ] Unit tests added/updated
- [ ] Feature tests added/updated
- [ ] Manual testing completed
- [ ] Edge cases tested

## Documentation

- [ ] Code comments added where needed
- [ ] README updated (if needed)
- [ ] API docs updated (if API changes)

## Security

- [ ] No secrets in code
- [ ] Input validation present
- [ ] Error handling implemented
- [ ] CSRF protection present

## Architecture

- [ ] Follows project patterns
- [ ] No N+1 queries
- [ ] Events used (not hooks)
- [ ] Services/Repositories used correctly

## Git

- [ ] Commits follow conventional format
- [ ] Branch up to date with main
- [ ] No merge conflicts
```

**Process:**

1. Run through checklist
2. Fix any issues
3. Run `/build-and-fix` to verify
4. Create PR with `gh pr create`

---

## Workflow Example

### During Feature Development

```
[Implementing user authentication for 2 hours...]

You: /code-review

Claude: [Launches code-reviewer agent]

Agent: Reviewed 8 modified files. Found:

CRITICAL (must fix):
- app/Services/AuthService.php:42
  Missing try-catch around Socialite call. Could expose errors to users.

WARNINGS (should fix):
- resources/js/Pages/Auth/Login.vue:28
  API call not wrapped in error handling. Consider adding toast notification.

SUGGESTIONS (nice to have):
- app/Http/Controllers/AuthController.php
  Consider extracting validation to FormRequest class for better organization.

POSITIVE:
- Good use of Repository pattern
- Event-Driven architecture followed correctly
- Proper type hints throughout

Claude: Shall I fix the critical and warning issues?
```

### Before Committing

```
You: /build-and-fix

Claude: Running TypeScript check...
✅ TypeScript: No errors

Running PHPStan...
❌ PHPStan: 3 errors found

Error 1:
Parameter #1 $user of method AuthService::sendWelcomeEmail() expects App\Models\User, App\Models\User|null given.

Fix: Adding null check before sendWelcomeEmail() call

[Fixes error]

Running PHPStan again...
✅ PHPStan: No errors

All checks passed! ✅
```

### Before PR

```
You: /pr-ready

Claude: Running pre-PR checklist...

✅ composer quality passes
✅ npm run quality passes
❌ Found console.log in resources/js/Pages/Auth/Login.vue:15
✅ No commented-out code
✅ All tests pass
⚠️  No tests added for AuthService

Shall I:
1. Remove the console.log
2. Create tests for AuthService
```

---

## Best Practices from Reddit Post

1. **Review often during implementation**
    - Don't wait until the end
    - Catch big mistakes early
    - Saves headaches later

2. **Have Claude review its own code**
    - Use the `code-reviewer` agent
    - Gets a fresh perspective
    - Catches things you both might miss

3. **Never commit without quality checks**
    - Always run `/build-and-fix` minimum
    - Run `/pr-ready` before PRs
    - Zero errors left behind (#NoMessLeftBehind)

4. **Systematic error fixing**
    - Don't skip errors thinking they're "unrelated"
    - Fix them all, every time
    - Use the hooks system to catch them automatically

---

## Integration with Hooks

The Stop hook automatically runs build checks after you finish responding.
You'll see errors immediately without having to manually run checks.

**Hook benefits:**

- ✅ Automatic error detection
- ✅ No errors slip through
- ✅ Gentle reminders for error handling
- ✅ Zero manual checking needed

**Manual commands still useful for:**

- Full quality check with tests (`/pr-ready`)
- Systematic error fixing (`/build-and-fix`)
- Architecture review (`/code-review`)
