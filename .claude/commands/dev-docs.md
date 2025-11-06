# Dev Docs Commands

Dev docs workflow to prevent Claude from "losing the plot" during large tasks.
Based on: "Claude Code is a Beast - Tips from 6 Months" Reddit post.

---

## /dev-docs - Create Strategic Plan

Create a comprehensive strategic plan for a feature or task. This command should be used at the START of any large task.

**When to use:**

- Starting a new feature implementation
- Planning a refactoring effort
- Before any multi-step task (3+ steps)

**Process:**

1. Research the codebase thoroughly
2. Analyze project structure and existing patterns
3. Create a structured plan with:
    - Executive summary
    - Implementation phases
    - Detailed tasks checklist
    - Potential risks
    - Success metrics
    - Timeline estimate

**Output format:**

```markdown
# [Task Name] - Strategic Plan

## Executive Summary

[Brief overview of what we're building and why]

## Current State

[What exists now, what needs to change]

## Goals

[What we want to achieve]

## Implementation Phases

### Phase 1: [Phase Name]

**Goal:** [Phase objective]

**Tasks:**

1. [ ] Task 1
2. [ ] Task 2

**Key Files:**

- path/to/file.ext - Purpose

**Risks:**

- Risk description and mitigation

### Phase 2: [Phase Name]

...

## Success Criteria

- [ ] Criterion 1
- [ ] Criterion 2

## Timeline

- Phase 1: X days
- Phase 2: Y days
- Total: Z days

## Next Steps

1. Review and approve plan
2. Run `/create-dev-docs [task-name]` to create dev doc files
3. Begin Phase 1 implementation
```

---

## /create-dev-docs - Create Dev Doc Files

After plan approval, create the 3-file dev docs system in `~/dev/active/[task-name]/`.

**Arguments:**

- `task-name` - Kebab-case task identifier (e.g., `user-authentication`, `payment-integration`)

**Creates:**

```
~/dev/active/[task-name]/
├── [task-name]-plan.md      # The approved strategic plan
├── [task-name]-context.md   # Key files, decisions, notes
└── [task-name]-tasks.md     # Checklist (auto-updated)
```

**Process:**

1. Create task directory
2. Save approved plan to `[task-name]-plan.md`
3. Extract key context to `[task-name]-context.md`:
    - Important file paths with descriptions
    - Architectural decisions made
    - External dependencies
    - Integration points
4. Create initial tasks checklist in `[task-name]-tasks.md`:
    - Break down phases into actionable items
    - Add checkboxes for tracking
    - Include next steps

**Context file format:**

```markdown
# [Task Name] - Context

**Last Updated:** [Timestamp]

## Key Files

### Backend

- `app/Http/Controllers/XController.php` - Handles X requests
- `app/Services/XService.php` - Business logic for X

### Frontend

- `resources/js/Pages/X/Index.vue` - Main X page
- `resources/js/Components/X/XForm.vue` - X form component

## Architectural Decisions

1. **Decision:** Using Event-Driven approach for X
   **Reason:** Decouples X from Y, allows future extensions
   **Files:** `app/Events/XEvent.php`, `app/Listeners/XListener.php`

## Dependencies

- Package X v2.0 - Used for Y functionality
- Service Z API - Provides data for A

## Integration Points

- Database: `x_table`, `y_table` (migrations in `database/migrations/`)
- API Endpoints: `/api/x`, `/api/y` (routes in `routes/api.php`)
- Frontend Routes: `/x`, `/x/{id}` (Inertia pages)

## Notes

- [Any important observations, gotchas, or reminders]
```

**Tasks file format:**

```markdown
# [Task Name] - Tasks

**Last Updated:** [Timestamp]
**Status:** In Progress / Completed

## Phase 1: [Phase Name]

- [ ] Task 1
- [ ] Task 2
- [x] Task 3 (completed)

## Phase 2: [Phase Name]

- [ ] Task 1
- [ ] Task 2

## Next Steps

1. Complete Phase 1 tasks
2. Review and test
3. Move to Phase 2
```

---

## /update-dev-docs - Update Before Compaction

Before running out of context or compacting conversation, update dev docs with:

- Current progress
- New tasks discovered
- Important decisions made
- Next steps

**Process:**

1. Find the active task directory in `~/dev/active/`
2. Update `[task-name]-context.md`:
    - Add any new key files discovered
    - Document decisions made during implementation
    - Note any important observations
3. Update `[task-name]-tasks.md`:
    - Mark completed tasks as `[x]`
    - Add newly discovered tasks
    - Update "Next Steps" section
4. Update timestamps

**After updating:**
Run `/clear` to compact conversation. In the new session, say:

- "Continue with [task-name]"
- Claude will read the dev docs and pick up exactly where it left off

---

## /continue-task - Continue Existing Task

Resume work on an existing task from dev docs.

**Process:**

1. List tasks in `~/dev/active/`
2. Ask which task to continue
3. Read all 3 dev doc files
4. Summarize current status
5. Ask what to work on next

---

## Workflow Example

### Starting New Task

```
You: I want to implement user authentication with OAuth
Claude: Let me create a strategic plan...
[Claude creates comprehensive plan]

You: Looks good!
You: /create-dev-docs user-oauth-auth

Claude: Created dev docs in ~/dev/active/user-oauth-auth/
- user-oauth-auth-plan.md
- user-oauth-auth-context.md
- user-oauth-auth-tasks.md

Ready to start Phase 1!
```

### During Implementation

```
[Working on feature... context getting low]

You: /update-dev-docs

Claude: Updated dev docs:
- Marked 5 tasks complete
- Added 2 new tasks discovered
- Documented decision to use Sanctum for tokens
- Updated next steps

Context: 15% remaining. Ready to compact!

You: /clear
[New session]

You: Continue with user-oauth-auth

Claude: [Reads dev docs]
Last session completed Phase 1 tasks. Next:
- Implement OAuth callback handling
- Add token refresh logic
- Create frontend login flow

What would you like to work on?
```

---

## Best Practices

1. **ALWAYS create dev docs for tasks 3+ steps**
2. **Update dev docs before compacting** - Don't lose progress!
3. **Implement in small chunks** - 1-2 tasks at a time, then review
4. **Mark tasks complete immediately** - Don't batch completions
5. **Document decisions as you go** - Future you will thank you
6. **Use `/clear` proactively** - Don't wait till 5% context left

---

## Why This Works

From the Reddit post author:

> "Before using this system, I had many times when I all of a sudden realized
> that Claude had lost the plot and we were no longer implementing what we had
> planned out 30 minutes earlier because we went off on some tangent."

> "Once I'm done with [creating dev docs], I'm pretty much set to have Claude
> fully implement the feature without getting lost or losing track of what it
> was doing, even through an auto-compaction."

The dev docs system prevents:

- ❌ Claude forgetting the plan mid-implementation
- ❌ Going off on tangents and losing focus
- ❌ Losing progress during context compaction
- ❌ Repeating work or missing tasks

And enables:

- ✅ Clear, trackable progress
- ✅ Seamless continuation across sessions
- ✅ Confidence that nothing is forgotten
- ✅ Easy handoff between sessions or team members
