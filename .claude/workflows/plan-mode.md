---
title: Plan Mode Usage Guide
version: 1.0
last_updated: 2025-10-31
author: PineCMS Team
status: active
related:
  - .claude/commands/dev-docs.md
  - .claude/agents/task-decomposition-expert.md
---

# Plan Mode Usage Guide

## What is Plan Mode?

Plan Mode (`Shift+Tab`) allows Claude to **think deeply** before coding:

- **think** - Standard planning
- **think harder** - More thorough analysis
- **ultrathink** - Maximum depth for complex problems

## When to Use Plan Mode

### ✅ ALWAYS Use Plan Mode For

1. **Complex multi-step implementations**
   - Features requiring >3 files
   - Database migrations with data transformations
   - Multiple service integrations

2. **Large-scale refactoring**
   - Architectural changes
   - Renaming/restructuring core modules
   - Breaking API changes

3. **Database migrations with data transformations**
   - Adding/removing columns with data migration
   - Changing relationships
   - Schema redesigns

4. **Architecture & design decisions**
   - Choosing between patterns (Repository vs. Active Record)
   - Performance optimization strategies
   - Security implementations

5. **Features requiring multiple agents/subtasks**
   - Full-stack features (backend + frontend + tests)
   - Multi-step workflows
   - Integration of multiple packages

### ❌ When NOT to Use Plan Mode

- Simple bug fixes (single file, obvious solution)
- Minor text/formatting changes
- Running tests or linters
- Reading/exploring code
- Answering simple questions

## Plan Mode Workflow

### Step 1: Start Plan Mode

Press `Shift+Tab` to enter Plan Mode.

**Choose thinking level:**

- `think` - For moderately complex tasks
- `think harder` - For complex tasks with dependencies
- `ultrathink` - For critical architectural decisions

### Step 2: Research Phase

**Use MCP tools to gather context:**

```
"I need to implement multi-tenant architecture."

[Claude in Plan Mode uses:]
- search-docs (Laravel multi-tenancy patterns)
- database-schema (current database structure)
- list-routes (existing route patterns)
- context7 (package documentation if needed)
- vibe_check (validate approach, identify assumptions)
```

### Step 3: Create Detailed Plan

**Plan should include:**

✅ **Tasks breakdown:**

```
1. Research existing patterns
2. Design database schema changes
3. Create tenant identification middleware
4. Update models with tenant scopes
5. Migrate existing data
6. Write tests for tenant isolation
7. Update documentation
```

✅ **Dependencies:**

```
- Task 3 depends on Task 2 (schema must exist)
- Task 5 depends on Task 4 (models needed for migration)
```

✅ **Edge cases:**

```
- What happens to existing data?
- How to handle tenant switching?
- Performance impact on queries?
```

✅ **Testing strategy:**

```
- Unit tests for tenant scopes
- Feature tests for tenant isolation
- E2E tests for tenant switching
```

✅ **Trade-offs:**

```
Approach A: Single database with tenant_id
  Pros: Simpler, cheaper
  Cons: Risk of data leakage

Approach B: Separate database per tenant
  Pros: Better isolation, scalable
  Cons: More complex, expensive

Recommendation: Approach A (current scale)
```

### Step 4: Review with User

**Present plan for approval:**

- Explain approach and reasoning
- Highlight trade-offs
- Ask for feedback/adjustments
- **Wait for confirmation** before proceeding

### Step 5: Exit Plan Mode & Execute

Press `Shift+Tab` again to exit Plan Mode.

**Then execute with specialized agents:**

```
Task 1: Use context-manager to explore codebase
Task 2: Use database-architect for schema design
Task 3-4: Use backend-architect for implementation
Task 6: Use test-engineer for comprehensive tests
```

## Plan Mode Examples

### Example 1: Complex Feature

**User Request:**

```
"Add real-time collaboration feature with WebSockets"
```

**Plan Mode Response:**

```
I'll create a detailed plan for real-time collaboration:

## Research (using search-docs)
- Laravel Broadcasting documentation
- Laravel Echo configuration
- WebSocket server options (Pusher vs. Soketi)

## Tasks Breakdown
1. Choose WebSocket provider (Soketi recommended)
2. Install Laravel Echo Server
3. Configure broadcasting
4. Create presence channels
5. Implement user presence tracking
6. Add collaborative cursor positions
7. Handle conflict resolution
8. Test with multiple users

## Dependencies
- Task 2 → Task 3 (server before config)
- Task 4 → Task 5 (channels before tracking)

## Edge Cases
- User disconnects mid-edit
- Concurrent edits to same content
- Network latency handling

## Testing Strategy
- Unit: Event broadcasting
- Feature: Presence tracking
- E2E: Multi-user scenarios

## Estimated Complexity
- Files to create: ~8
- External dependencies: 2 (soketi, laravel-echo)
- Time estimate: 3-4 hours

Shall I proceed with this approach?
```

### Example 2: Architectural Decision

**User Request:**

```
"Should we use Repository pattern or stick with Eloquent?"
```

**Plan Mode Response (ultrathink):**

```
I'll analyze this architectural decision:

## Current Situation
- Laravel 12 project
- Medium complexity (50+ models)
- Team size: 3 developers
- Timeline: Active development

## Option A: Repository Pattern
Pros:
- Abstraction over data layer
- Easier to swap ORMs (unlikely needed)
- Testable with mocks
Cons:
- Additional complexity
- More files to maintain
- Laravel already provides testable patterns

## Option B: Eloquent (Laravel Way)
Pros:
- Less boilerplate
- Laravel conventions
- Excellent testing support (factories, in-memory SQLite)
- Query scopes for reusability
Cons:
- Tight coupling to Eloquent
- Less abstraction

## Recommendation: Eloquent with Query Scopes
Reasoning:
1. Laravel 12 has excellent testing features
2. Repository adds complexity without clear benefit
3. Query scopes provide reusability
4. Team is familiar with Eloquent
5. Can refactor to Repository later if needed (YAGNI)

Example pattern:
```php
// Model with scopes (Eloquent approach)
class Post extends Model {
    public function scopePublished($query) {
        return $query->whereNotNull('published_at');
    }
}

// Usage
Post::published()->latest()->get();
```

Is this analysis aligned with your needs?

```

## Tips for Effective Planning

### 1. Be Specific in Requests

❌ Bad: "Build a feature"
✅ Good: "Plan implementation of two-factor authentication with SMS and authenticator app support"

### 2. Use Right Thinking Level

- **think** - Standard complexity (most cases)
- **think harder** - High complexity, multiple dependencies
- **ultrathink** - Critical decisions, security, performance

### 3. Interrupt if Needed

Press `ESC` during planning if:
- Plan is going in wrong direction
- Assumptions are incorrect
- Simpler approach exists

### 4. Always Get User Approval

**Never proceed from Plan Mode without:**
- Presenting complete plan
- Explaining trade-offs
- Getting explicit "yes, proceed"

## Common Plan Mode Mistakes

### Mistake #1: Planning Simple Tasks
❌ Using Plan Mode for "Fix typo in HomeController"
✅ Direct implementation for obvious fixes

### Mistake #2: Not Using MCP Tools
❌ Guessing about codebase structure
✅ Using `database-schema`, `list-routes`, `search-docs`

### Mistake #3: Skipping User Review
❌ Planning then immediately coding
✅ Present plan → Get approval → Then code

### Mistake #4: Incomplete Plans
❌ "1. Create model, 2. Done"
✅ Detailed steps, dependencies, edge cases, tests

## Plan Mode Checklist

Before exiting Plan Mode:

- [ ] All tasks identified and ordered
- [ ] Dependencies clearly stated
- [ ] Edge cases considered
- [ ] Testing strategy defined
- [ ] Trade-offs explained
- [ ] User approval received
- [ ] Specialized agents identified (if needed)

**Only then proceed with implementation.**
