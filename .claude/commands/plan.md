# Feature Planning Command

Create a detailed implementation plan for: **$ARGUMENTS**

## 1. Research Phase

- Search existing codebase patterns using Grep/Glob
- Check for similar features already implemented
- Review relevant documentation using `search-docs` tool (Laravel Boost)
- Check PineCMS architecture guidelines (`.claude/instructions/architecture.md`)

## 2. Architecture Phase

Analyze and design:

- **Affected Files**: List all files that need to be created/modified
- **Database Schema**: Plan migrations, models, relationships (if applicable)
- **API/Routes**: Design endpoints, controllers, form requests
- **Events**: Plan event-driven architecture (PineCMS uses Events, NOT Hooks!)
- **Services/Repositories**: Plan business logic separation
- **Frontend Components**: Vue components, Inertia pages (if applicable)

## 3. Task Breakdown

Break down into 5-10 manageable subtasks:

- Use clear, specific task descriptions
- Estimate complexity: Simple / Medium / Complex
- Identify dependencies between tasks
- Order tasks by dependency chain (what must be done first?)

**Format each task as:**

```
Task X: [Action] [Component] [Details]
Complexity: [Simple/Medium/Complex]
Dependencies: [None / Task Y, Task Z]
Files: [list relevant files with paths]
```

## 4. Risk Assessment

Identify potential risks:

- **Performance**: N+1 queries, large data sets, slow operations
- **Security**: Input validation, authorization, XSS/CSRF risks
- **Breaking Changes**: API changes, database migrations, backward compatibility
- **Testing**: Complex test scenarios, integration test requirements

For each risk, provide mitigation strategies.

## 5. Dev Docs Creation

After planning is complete:

1. Ask user if they want to create dev-docs structure
2. If yes, use `/dev-docs [feature-name]` command to create:
    - `[feature-name]-plan.md` - This strategic plan
    - `[feature-name]-context.md` - Key files, decisions, gotchas
    - `[feature-name]-tasks.md` - Checklist (TodoWrite format)

## 6. TodoWrite Tracking

Create TodoWrite todos for EACH subtask:

- Mark all tasks as "pending" initially
- First task should be marked "in_progress" when starting implementation
- Update status as work progresses

## Output Format Requirements

Provide a comprehensive plan with:

- ✅ Clear numbered steps
- ✅ File paths with line numbers (e.g., `app/Services/UserService.php:45`)
- ✅ Specific tool/command recommendations
- ✅ Estimated time per task
- ✅ Database schema changes (if any)
- ✅ Event flows (event → listener → side effects)
- ✅ Testing strategy (unit tests, feature tests, e2e tests)

## PineCMS-Specific Considerations

**Remember:**

- ❌ NO custom Hooks - Use Laravel Events only
- ✅ Hybrid Storage: SQLite (relational) + Flat-File (content)
- ✅ Frontend: Vue 3 Composition API + Inertia 2.x + PrimeVue
- ✅ Backend: Laravel 12 + PHP 8.3
- ✅ Follow Spatie Laravel Guidelines for code standards
- ✅ SOLID principles + Event-Driven Architecture
- ✅ Always use `declare(strict_types=1);` in PHP files
- ✅ Never use `env()` directly - Always use `config()`

## Example Output Structure

```markdown
# Feature Plan: [Feature Name]

## Overview

[Brief description of the feature and its purpose]

## Research Findings

- Existing pattern X found in Y
- Similar feature Z implemented using approach A

## Architecture Design

### Database Changes

- Migration: create\_[table]\_table
- Model: App\Models\[Model]
- Relationships: [list]

### Backend Components

1. Controller: App\Http\Controllers\[Name]Controller
2. Service: App\Services\[Name]Service
3. Repository: App\Repositories\[Name]Repository (if needed)
4. Events: App\Events\[Name]Created, [Name]Updated
5. Listeners: App\Listeners\Handle[Name]Created

### Frontend Components

1. Page: resources/js/Pages/[Name]/Index.vue
2. Components: resources/js/Components/[Name]/[Component].vue
3. Composables: resources/js/composables/use[Name].ts

### Routes

- GET /[resource] - index
- POST /[resource] - store
- GET /[resource]/{id} - show
- PUT /[resource]/{id} - update
- DELETE /[resource]/{id} - destroy

## Task Breakdown

### Task 1: Create Database Migration

**Complexity:** Simple
**Dependencies:** None
**Estimated Time:** 15 minutes
**Files:**

- database/migrations/[timestamp]_create_[table]\_table.php
  **Commands:**
- php artisan make:migration create\_[table]\_table

[... continue for all tasks ...]

## Risk Assessment

### Performance Risks

- **Risk:** N+1 query when loading [relation]
- **Mitigation:** Use eager loading with `->with(['relation'])`

### Security Risks

- **Risk:** Unvalidated user input in [field]
- **Mitigation:** Form Request validation + sanitization

## Testing Strategy

### Unit Tests

- Test [Service] methods in isolation
- Mock dependencies

### Feature Tests

- Test complete flow: create → update → delete
- Test authorization and validation

### E2E Tests (if applicable)

- Test user journey through frontend

## Next Steps

1. Review this plan for accuracy
2. Get user approval
3. Create dev-docs structure (optional)
4. Start with Task 1
```

---

**Usage:** `/plan user authentication system`
**Usage:** `/plan blog post editor with markdown support`
**Usage:** `/plan multi-tenant permissions system`
