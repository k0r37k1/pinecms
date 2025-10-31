---
title: Quality Gates & Self-Review (Metacognitive Oversight)
version: 1.0
last_updated: 2025-10-31
author: PineCMS Team
status: active
related:
  - .claude/commands/quality.md
  - .claude/commands/code-review.md
---

# Quality Gates & Self-Review (Metacognitive Oversight)

**Purpose:** Prevent overengineering, tunnel vision, and misalignment through structured self-review checkpoints.

## Pre-Implementation Gate (MANDATORY)

**BEFORE writing ANY significant code:**

### 1. QPLAN - Architecture Check

- Have I checked **sibling files** for existing patterns?
- Am I **reusing existing** components/services/utilities?
- Is this the **minimal change** approach?
- Does this follow **Laravel conventions**?
- Do I need **new dependencies**? (Get user approval!)

**Use MCP tools:** `search-docs`, `database-schema`, `list-routes`, `vibe_check` (for complex features)

### 2. Alignment Verification

Present to user BEFORE coding:
- **Understanding:** What I think you want
- **In Scope:** What I will implement
- **Out of Scope:** What I won't implement
- **Assumptions:** What I'm assuming

**Wait for confirmation.**

### 3. Hypothesis Testing (For Complex Tasks)

Present multiple approaches with:
- Confidence levels (Low/Medium/High)
- Pros/Cons for each approach
- Recommended option with reasoning

**Get user input before proceeding.**

## Implementation Gate (DURING Coding)

### 1. Test-Driven Development (When Applicable)

**RED → GREEN → REFACTOR:**
1. Write failing test
2. Minimal implementation to pass
3. Optimize with safety net

### 2. Complexity Checks

**Trigger user consultation if:**
- Creating **>3 new files** → "This adds X files. Is this complexity justified?"
- Adding **new package** → "I need package X for Y. Approved?"
- **Deviating from conventions** → "Existing code uses pattern A, but I'm using B because [reason]. OK?"

### 3. Feature Creep Detection

If adding features **not in original request:**
- **Flag them explicitly:** "I'm adding feature X (not requested) because Y"
- **Explain necessity:** Technical requirement vs. nice-to-have
- **Get approval** before implementing

## Post-Implementation Gate (BEFORE Finalizing)

### 1. QCHECK - Skeptical Senior Engineer Review

**Review every MAJOR code change:**

#### A. Writing Functions Best Practices
- ✅ Single Responsibility: Each function does ONE thing?
- ✅ Naming: Descriptive? (`isRegisteredForDiscounts` not `discount()`)
- ✅ Parameters: < 4 parameters?
- ✅ Return Types: Explicit type declarations?
- ✅ Error Handling: Proper try-catch, validation, edge cases?
- ✅ `declare(strict_types=1);` in PHP files?

#### B. Writing Tests Best Practices
- ✅ Coverage: Happy path + failure path + edge cases?
- ✅ Isolation: Tests don't depend on each other?
- ✅ Factories: Using model factories, not manual setup?
- ✅ Assertions: Clear, specific assertions?
- ✅ Naming: Test names describe what they verify?

#### C. Implementation Best Practices
- ✅ KISS: Simplest solution that works?
- ✅ DRY: No repeated code patterns?
- ✅ YAGNI: Building only what's needed NOW?
- ✅ Existing Patterns: Following project conventions?
- ✅ Events: Using Events (not hooks)?
- ✅ FormRequest: Validation in FormRequest classes (not inline)?
- ✅ Eager Loading: No N+1 queries?

### 2. QCODE - Pre-Commit Verification

**ALWAYS run before finalizing:**
```bash
composer quality  # Laravel: Pint + PHPStan + PHPUnit
npm run quality   # JS: Prettier + ESLint + TypeScript + Vitest
```

**If tests fail or linting fails, FIX before proceeding.**

### 3. QUX - User Experience Testing

**Test these scenarios:**
- Primary user flows
- Error scenarios (validation failures, network errors)
- Edge cases (empty states, loading states, permission denied)
- Accessibility (keyboard navigation, screen reader, dark mode)

## Continuous Gates (Throughout Development)

### 1. Context Hygiene

**Use `/clear` frequently:**
- After every commit
- Before starting new feature
- When conversation >50 messages
- If Claude seems confused/repetitive

**Why:** Prevents context pollution and token waste

### 2. Interrupt Mechanism

**Press ESC to interrupt if:**
- Plan is drifting from user intent
- Complexity is escalating unexpectedly
- Solution feels wrong

**Double-tap ESC** to edit previous prompt

### 3. Verification Principle

**NEVER speculate about code you haven't read.**

❌ Bad: "The validation is probably in UserController"
✅ Good: *Reads UserController* "The validation is in UserStoreRequest:15"

**Always verify assumptions** by reading actual files.

### 4. Regular Self-Critique

After each major step, ask:
- Is this still the best approach?
- Have I discovered new information that changes the plan?
- Am I experiencing tunnel vision?
- Should I consult the user before continuing?

## Pattern Learning (Self-Improving Feedback Loop)

### Common Mistakes Log

Use `vibe_learn` MCP tool to log mistakes and prevent recurrence.

#### Mistake #1: Skipping search-docs
- ❌ Problem: Wrote outdated Inertia v1 pattern
- ✅ Solution: ALWAYS use `search-docs` before implementing
- 🛠️ Tool: Laravel Boost MCP `search-docs`
- 📚 Reference: Laravel Boost rules "Searching Documentation"

#### Mistake #2: Not using factories in tests
- ❌ Problem: Manually creating models in tests
- ✅ Solution: Check for factory states, use factories
- 🛠️ Tool: Read factory files before testing
- 📚 Reference: Testing Instructions "Use Factories"

#### Mistake #3: N+1 queries
- ❌ Problem: Forgot eager loading
- ✅ Solution: Use `->with('relation')` for eager loading
- 🛠️ Tool: `database-query` to check actual queries
- 📚 Reference: Architecture "N+1 Prevention"

*[More patterns will be added as they're discovered]*

## Quality Gates Summary

```
Pre-Implementation:  QPLAN + Alignment + Hypothesis Testing
        ↓
Implementation:      TDD + Complexity Checks + Feature Creep Detection
        ↓
Post-Implementation: QCHECK + QCODE + QUX
        ↓
Continuous:          /clear + ESC Interrupts + Verification + Self-Critique
        ↓
Learning:            Update Pattern Learning Log
```

**Result:** Higher quality code, fewer iterations, better alignment with user intent.
