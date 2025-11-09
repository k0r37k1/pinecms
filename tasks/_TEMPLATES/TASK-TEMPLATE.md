---
task_id: XXX
epic: XXX-epic-name
title: Task Title
status: pending # pending | in_progress | completed | blocked
priority: critical # critical | high | medium | low
estimated_effort: X hours
actual_effort: null
assignee: developer
dependencies: []
tags: [tag1, tag2, week-X]
---

# Task XXX: Task Title

## 📋 Overview

Brief description of what this task accomplishes and why it's needed.

## 🎯 Acceptance Criteria

- [ ] Criterion 1
- [ ] Criterion 2
- [ ] Criterion 3

## 🏗️ Implementation Steps

### Step 1: Step Title

**File**: `path/to/File.php`

**Description**: What this step does.

**Implementation**:

```php
// Code example or structure
```

**TodoWrite checklist** (auto-generated when task starts):

- Create file
- Implement logic
- Add tests

### Step 2: Step Title

**File**: `path/to/File.php`

**Description**: What this step does.

**Implementation**:

```php
// Code example or structure
```

## 🧪 Testing Requirements

**Unit Tests:**

- `tests/Unit/Path/FileTest.php`
    - Test scenario 1
    - Test scenario 2

**Feature Tests:**

- `tests/Feature/Path/FeatureTest.php`
    - Test scenario 1
    - Test scenario 2

**Browser Tests** (if applicable):

- `tests/Browser/FlowTest.php`
    - Critical user flow

**Frontend Tests** (if applicable):

- `resources/js/**/__tests__/Component.test.ts`
    - Component behavior test

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/XX-XXXXX.md` Section X.X
- **Timeline**: Week X-Y (vX.X.X)
- **Success Criteria**: [Criteria from PRD]

**Architecture:**

- **Pattern**: [Pattern Name] (`docs/prd/04-ARCHITECTURE.md`)
- **Events**: [Event Names] (`docs/prd/06-PLUGIN-ECOSYSTEM.md`)
- **Storage**: [Storage Approach]

**Quality Requirements:**

- **Security**: [OWASP Requirements] (`docs/prd/09-QUALITY-REQUIREMENTS.md`)
- **Performance**: [Performance Targets]
- **Testing**: [Coverage Requirements]
- **Accessibility**: [WCAG Requirements if applicable]

**Related Tasks:**

- **Next**: XXX-next-task
- **Blocks**: XXX-blocked-task
- **Depends On**: XXX-prerequisite-task

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes (`composer analyse`)
- [ ] Laravel Pint formatted (`vendor/bin/pint`)
- [ ] ESLint passes (`npm run lint`)
- [ ] No console.log statements
- [ ] `declare(strict_types=1);` in all PHP files

### Testing

- [ ] Unit tests written and passing
- [ ] Feature tests written and passing
- [ ] Test coverage > 80% for new code
- [ ] Browser tests for critical flows (if applicable)

### Security

- [ ] OWASP Top 10 compliance verified
- [ ] Input validation implemented
- [ ] Output escaping verified (Blade/Vue)
- [ ] Authorization checks in place
- [ ] CSRF protection enabled

### Documentation

- [ ] PHPDoc comments added
- [ ] Complex logic explained
- [ ] README updated if needed

## ✅ Verification Steps

Run these commands before marking task complete:

```bash
# Backend quality check
composer quality

# Frontend quality check
npm run quality

# Manual verification
# 1. [Verification step 1]
# 2. [Verification step 2]
```

## 🔄 Git Workflow

```bash
# Start task
git checkout -b task-XXX-task-name

# Implement with frequent commits
git commit -m "feat(scope): implement feature"

# Before completing
composer quality && npm run quality

# Complete task
git commit -m "feat(scope): complete task-XXX-task-name"
```
