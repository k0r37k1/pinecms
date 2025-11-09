---
# Task Metadata
id: TASK-XXX  # Sequential task number (e.g., TASK-001, TASK-045, TASK-234)
title: "[Action] [Component/Feature] [Specific Detail]"  # Verb-first, specific task title
phase: "v1.0.0-week-X"  # Development phase (e.g., v1.0.0-week-1, v1.0.0-week-4, v1.1.0-week-12)
priority: medium  # critical | high | medium | low
estimated_hours: 2  # Realistic time estimate (1-4 hours for implementation-level tasks)

# Categorization
tags: [backend, frontend, database]  # Technology/domain tags for filtering
# Available tags:
# Technology: [backend, frontend, fullstack, database, api, cli]
# Laravel Layers: [migration, model, repository, service, controller, event, middleware]
# Frontend: [vue, inertia, tiptap, primevue, alpine, tailwind]
# Features: [content-management, user-system, media, taxonomy, seo, plugin, theme]
# Quality: [testing, documentation, performance, accessibility, security]

# Dependencies
dependencies:
  required: []  # Tasks that MUST be completed before this one (e.g., [TASK-001, TASK-005])
  optional: []  # Tasks that provide helpful context but aren't blocking (e.g., [TASK-010])

blocks: []  # Tasks that CANNOT start until this one is done (e.g., [TASK-020, TASK-021])
parallelizable_with: []  # Tasks that can be worked on simultaneously (e.g., [TASK-015, TASK-016])

# Agent Assignment
specialized_agents:
  recommended: fullstack-developer  # Primary agent for this task
  # Available agents: frontend-developer, backend-architect, fullstack-developer, ui-ux-designer,
  # code-reviewer, test-engineer, debugger, error-detective, context-manager,
  # task-decomposition-expert, database-architect, architect-reviewer, deployment-engineer, security-auditor
  alternative: [backend-architect]  # Fallback agents if recommended unavailable
  review_with: code-reviewer  # Agent for post-implementation review

# Software Engineering Principles
software_principles:
  - "KISS: Keep implementation simple, avoid over-engineering"
  - "DRY: Extract common patterns into reusable components"
  - "YAGNI: Only implement what's needed for v1.0.0"
  - "Single Responsibility: Each class/component has one reason to change"
  - "Open/Closed: Design for extension without modification"
# Apply SOLID, DRY, KISS, YAGNI principles specific to this task

# File Operations
files_created:
  - "path/to/new/file.php"  # Files this task will create
  - "path/to/another/file.ts"

files_modified:
  - "path/to/existing/file.php"  # Files this task will modify
  - "config/app.php"

# Acceptance Criteria
acceptance_criteria:
  - "Specific, measurable outcome that proves task completion"
  - "Tests pass with >80% code coverage"
  - "No PHPStan/ESLint errors"
  - "Follows Laravel/Vue coding standards"
  - "Documentation updated (if applicable)"

# Vibe Check Triggers
vibe_check_triggers:
  - "If implementation exceeds estimated hours by 50%"
  - "If solution feels overly complex for the problem"
  - "Before committing if acceptance criteria unclear"
  - "When tempted to add features beyond task scope"
# Moments to pause and validate approach using vibe-check MCP

# MCP Tools
mcp_tools_needed:
  - laravel-boost  # search-docs, tinker, database-query
  - context7       # PrimeVue, TipTap, Alpine docs
  - vibe-check     # Complexity validation
  - eslint         # Frontend linting
  - playwright     # E2E testing
# MCP servers required for this task

# Related Documentation
related_docs:
  - "docs/prd/05-CORE-FEATURES.md#section"  # PRD references
  - ".claude/instructions/backend.md#topic"  # Coding guidelines
  - "docs/architecture/ADR-XXX.md"           # Architecture decisions
---

# TASK-XXX: [Task Title]

## Context & Background

**Why this task exists:**
[Explain the problem this task solves, the user need it addresses, or the architectural requirement it fulfills]

**PRD Reference:**
- Section: `docs/prd/XX-FILENAME.md#section`
- Feature: [Feature name from PRD]
- Epic: [Epic/phase this belongs to]

**Architectural Context:**
[How this fits into PineCMS architecture - which layer (Service, Repository, Controller), which system (Content Management, User System, Plugin System), any relevant ADRs]

**Dependencies:**
- **Required Tasks:** [Why these must be done first - what they provide]
- **Optional Tasks:** [How these provide helpful context]

---

## Prerequisites

### Knowledge Required
- [ ] Familiarity with [technology/framework]
- [ ] Understanding of [architectural pattern]
- [ ] Experience with [specific tool/library]

### Setup Required
- [ ] Environment: [PHP 8.3, Node 20+, etc.]
- [ ] Dependencies: [packages that must be installed]
- [ ] Database: [migrations that must be run]
- [ ] Configuration: [.env variables needed]

### Files to Review Before Starting
1. `path/to/existing/code.php` - [Why: contains pattern to follow]
2. `path/to/related/feature.ts` - [Why: shows similar implementation]
3. `docs/architecture/ADR-XXX.md` - [Why: explains design decision]

---

## Implementation Steps

### Step 1: [Action] (Estimated: 30 min)

**What to do:**
[Clear, specific instructions for this step]

**Code example:**
```php
// Example implementation showing expected pattern
namespace App\Services;

use App\Repositories\ExampleRepository;

final class ExampleService
{
    public function __construct(
        private readonly ExampleRepository $repository
    ) {}

    public function doSomething(string $input): Result
    {
        // Implementation here
    }
}
```

**TDD Workflow:**
1. **RED:** Write failing test first
   ```php
   public function test_example_service_does_something(): void
   {
       $service = new ExampleService($this->repository);
       $result = $service->doSomething('input');
       $this->assertTrue($result->isSuccess());
   }
   ```
2. **GREEN:** Minimal implementation to pass
3. **REFACTOR:** Improve code quality

**Expected output:**
[What should exist after this step - files created, tests passing, etc.]

### Step 2: [Action] (Estimated: 45 min)

**What to do:**
[Next step instructions]

**MCP Tool Usage:**
```bash
# Use Laravel Boost to search documentation
search-docs query="topic" packages=["laravel/framework"]

# Use tinker to test implementation
tinker code="App\Services\ExampleService::make()->doSomething('test')"
```

**Expected output:**
[Validation criteria for this step]

### Step 3: [Action] (Estimated: 30 min)

**What to do:**
[Final implementation step]

**Quality Check:**
```bash
# Backend quality
composer quality  # PHPStan L8 + Pint + PHPUnit

# Frontend quality (if applicable)
npm run quality   # ESLint + Prettier + Vitest + TypeScript
```

**Expected output:**
[All quality checks pass, acceptance criteria met]

---

## Testing Strategy

### Unit Tests
**File:** `tests/Unit/Services/ExampleServiceTest.php`

**Coverage:**
- [ ] Happy path: Normal successful operation
- [ ] Edge cases: Empty input, null values, boundary conditions
- [ ] Error cases: Invalid input, exceptions, failures

**Example test:**
```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\ExampleService;
use Tests\TestCase;

final class ExampleServiceTest extends TestCase
{
    public function test_example_service_handles_valid_input(): void
    {
        // Arrange
        $service = new ExampleService();

        // Act
        $result = $service->doSomething('valid-input');

        // Assert
        $this->assertTrue($result->isSuccess());
    }

    public function test_example_service_handles_invalid_input(): void
    {
        // Test error path
    }
}
```

### Feature Tests (if applicable)
**File:** `tests/Feature/ExampleFeatureTest.php`

**Coverage:**
- [ ] HTTP endpoint returns expected response
- [ ] Database state changes correctly
- [ ] Events are dispatched
- [ ] Authorization enforced

### E2E Tests (if frontend involved)
**File:** `tests/Browser/ExampleE2ETest.php`

**Coverage:**
- [ ] User can complete workflow
- [ ] UI updates correctly
- [ ] Error states displayed

**Coverage Target:** >80% for new code

**Run tests:**
```bash
php artisan test --filter=ExampleServiceTest
npm run test -- ExampleComponent.spec.ts
```

---

## Common Pitfalls

### Pitfall 1: [Common Mistake]
**Problem:** [What developers typically do wrong]
**Solution:** [Correct approach]
**Example:**
```php
// ❌ WRONG - Anti-pattern
public function wrong(): void
{
    // Bad implementation
}

// ✅ CORRECT - Proper pattern
public function correct(): void
{
    // Good implementation
}
```

### Pitfall 2: N+1 Query Issues
**Problem:** Loading related models in loops
**Solution:** Use eager loading
```php
// ❌ WRONG
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name; // N+1 query
}

// ✅ CORRECT
$posts = Post::with('author')->get();
foreach ($posts as $post) {
    echo $post->author->name; // Single query
}
```

### Pitfall 3: [Technology-Specific Issue]
**Problem:** [What goes wrong]
**Solution:** [How to avoid]

---

## Vibe Check Moments

### When to Pause & Validate

1. **If implementation exceeds 150 lines**
   - Question: "Can this be split into smaller, focused classes?"
   - Use: `vibe_check` MCP to analyze complexity

2. **If adding features beyond scope**
   - Question: "Is this needed for v1.0.0 or can it wait?"
   - Refer to: YAGNI principle, PRD scope

3. **If tests are hard to write**
   - Question: "Is the design too coupled? Should I refactor?"
   - Consider: Dependency injection, interface abstraction

4. **Before committing**
   - Checklist:
     - [ ] All acceptance criteria met?
     - [ ] Tests passing with >80% coverage?
     - [ ] No quality check errors?
     - [ ] Documentation updated?
     - [ ] Follows software principles?

### Vibe Check Command
```bash
# Use vibe-check MCP to validate approach
vibe_check goal="Implement [feature]" plan="[approach]" uncertainties=["concern1", "concern2"]
```

---

## Definition of Done

### Task Completion Checklist

- [ ] **Implementation Complete**
  - All files created/modified as specified
  - Code follows Laravel/Vue patterns
  - No TODOs or placeholder code left

- [ ] **Tests Written & Passing**
  - Unit tests for all logic paths
  - Feature tests for HTTP endpoints (if applicable)
  - E2E tests for user workflows (if applicable)
  - Coverage >80% for new code

- [ ] **Quality Checks Passed**
  - `composer quality` passes (PHPStan L8, Pint, PHPUnit)
  - `npm run quality` passes (ESLint, Prettier, TypeScript, Vitest)
  - No security vulnerabilities introduced
  - No performance regressions

- [ ] **Documentation Updated**
  - Inline PHPDoc blocks for classes/methods
  - Inline JSDoc for TypeScript functions
  - README updated (if public API changed)
  - Architecture docs updated (if design changed)

- [ ] **Code Review**
  - Self-review using `code-reviewer` agent
  - All acceptance criteria verified
  - Software principles applied correctly
  - No over-engineering or premature optimization

- [ ] **Git Commit**
  - Conventional commit message format
  - All changes staged correctly
  - Commit hooks pass (tests, linting, spell check)

### Quality Gate
```bash
# Must pass before marking complete
composer quality && npm run quality
```

---

## Next Steps After Completion

### Immediate Unblocked Tasks
- **TASK-XXX:** [Task that was blocked by this one]
- **TASK-YYY:** [Another dependent task]

### Follow-up Tasks
- **TASK-ZZZ:** [Related task in same epic]

### Team Notifications
- Notify: [Team member/role] about [what they need to know]
- Update: [Project board/status] to reflect completion

### Post-Completion Documentation
Create completion summary at `tasks/TASK-XXX-COMPLETED.md`:
- What was built (files created/modified)
- Implementation decisions (why certain approaches)
- Challenges encountered (problems solved)
- Knowledge gained (lessons learned)
- Handoff notes (next developer needs to know)

---

**Template Version:** 1.0
**Last Updated:** 2025-11-09
**Related Design:** `docs/plans/2025-11-09-pinecms-task-system-design.md`
