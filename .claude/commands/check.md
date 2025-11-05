---
allowed-tools: all
description: Verify code quality, run tests, and ensure production readiness (Zero Tolerance)
---

# 🚨 CRITICAL: FIX ALL ERRORS - NOT JUST REPORT THEM! 🚨

**THIS IS A FIXING TASK, NOT A REPORTING TASK!**

When you run `/check`, you MUST:

1. **IDENTIFY** all errors, warnings, and issues
2. **FIX EVERY SINGLE ONE** - not just list them!
3. **USE MULTIPLE AGENTS** to fix issues in parallel
4. **DO NOT STOP** until ALL checks show ✅ GREEN

**FORBIDDEN BEHAVIORS:**

- ❌ "Here are the issues" → NO! FIX THEM!
- ❌ "Tests are failing because..." → NO! MAKE THEM PASS!
- ❌ Stopping after listing → NO! KEEP WORKING!

---

## 🛑 MANDATORY PRE-FLIGHT CHECK

1. Re-read `.claude/CLAUDE.md` RIGHT NOW
2. Check current state
3. Verify you're not declaring "done" prematurely

---

## Step 1: PineCMS Quality Pipeline

### PHP Quality Checks

```bash
composer quality
# Runs: Pint + PHPStan + Deptrac + PHPUnit
```

**What gets checked:**

- ✅ Code formatting (Laravel Pint)
- ✅ Static analysis (PHPStan level 8)
- ✅ Architecture (Deptrac)
- ✅ All tests pass (PHPUnit)

### JavaScript Quality Checks

```bash
npm run quality
# Runs: Prettier + ESLint + Stylelint + TypeCheck + Vitest
```

**What gets checked:**

- ✅ Code formatting (Prettier)
- ✅ Linting (ESLint)
- ✅ CSS linting (Stylelint)
- ✅ TypeScript checks
- ✅ All tests pass (Vitest)

---

## Step 2: PineCMS Specific Checks

### 🔒 Security Validation

- [ ] No `env()` direct usage - use `config()` instead
- [ ] CSRF protection on all forms
- [ ] Input validation using Form Requests
- [ ] No hardcoded secrets
- [ ] Security headers configured (CSP, Secure Headers)
- [ ] CipherSweet encryption working

### 📁 Flat-File Content Integrity

- [ ] YAML front matter valid in all content files
- [ ] Markdown syntax correct
- [ ] No orphaned content files
- [ ] Content directory structure correct
- [ ] File permissions appropriate

### 🎨 Frontend Standards

- [ ] Vue 3 Composition API only (NO Options API)
- [ ] TypeScript for all components
- [ ] PrimeVue components properly imported
- [ ] No inline styles (TailwindCSS utilities only)
- [ ] Inertia forms use `useForm` composable

### ⚡ Performance

- [ ] No N+1 queries (eager loading used)
- [ ] No database queries in computed properties
- [ ] Appropriate caching configured
- [ ] Images optimized (Intervention Image)
- [ ] Assets compiled and minified

---

## Step 3: Laravel Best Practices

### Code Quality Checklist

- [ ] No raw SQL - use Eloquent/Query Builder
- [ ] No direct `$_GET`/`$_POST` - use Request validation
- [ ] Type hints on ALL methods
- [ ] Early returns to reduce nesting
- [ ] Meaningful variable names (`$userId` not `$id`)
- [ ] Self-documenting code (NO inline comments)
- [ ] Enum classes with methods (getLabel, getColor, etc.)
- [ ] Proper Eloquent relationships with return types
- [ ] Controllers thin - delegate to Services
- [ ] Proper separation of concerns

### Testing Requirements

- [ ] Feature tests for all endpoints
- [ ] Unit tests for complex business logic
- [ ] E2E tests with Playwright for critical flows
- [ ] Test coverage > 80%
- [ ] NO skipped tests without justification
- [ ] Tests are meaningful (test behavior, not implementation)

---

## Step 4: Architecture Validation

### Event-Driven Architecture

- [ ] Use Events for side effects (NO Hooks!)
- [ ] Listeners properly registered
- [ ] Events dispatched correctly
- [ ] Queued where appropriate

### Hybrid Storage (SQLite + Flat-File)

- [ ] Relational data in SQLite
- [ ] Content in flat files (Markdown + YAML)
- [ ] Sync mechanisms working
- [ ] No data duplication

### Dependencies

- [ ] All packages up-to-date
- [ ] No unused dependencies
- [ ] Security vulnerabilities checked
- [ ] Composer lock file committed

---

## Failure Response Protocol

When issues are found:

1. **SPAWN MULTIPLE AGENTS** immediately:

```
"Found 15 PHP linting issues and 3 test failures. Spawning agents:
- Agent 1: Fix PHP linting in Services
- Agent 2: Fix TypeScript errors in Components
- Agent 3: Fix failing tests
Working in parallel..."
```

2. **FIX EVERYTHING** - Address EVERY issue
3. **VERIFY** - Re-run all checks
4. **REPEAT** - Keep going until ✅ GREEN
5. **NO EXCUSES**:
   - ❌ "It's just formatting" → Auto-format NOW
   - ❌ "It's a false positive" → Prove it or fix it
   - ❌ "It works fine" → Not enough, fix it
   - ❌ "Other code does this" → Fix that too

---

## Final Verification

Code is ready ONLY when:

✅ `composer quality` - PASSES (zero warnings)
✅ `npm run quality` - PASSES (zero warnings)
✅ All tests pass (PHP + JavaScript + E2E)
✅ No security issues
✅ No performance problems
✅ All checklist items verified
✅ Feature works end-to-end

---

## Final Commitment

I will now:

- ✅ Run ALL checks to identify issues
- ✅ SPAWN AGENTS to fix issues in parallel
- ✅ Keep working until EVERYTHING passes
- ✅ Not stop until all checks show ✅ GREEN

I will NOT:

- ❌ Just report issues without fixing
- ❌ Skip any checks
- ❌ Declare "good enough"
- ❌ Stop while ANY issues remain

**REMEMBER: This is a FIXING task!**

**Executing comprehensive validation and FIXING ALL ISSUES NOW...**
