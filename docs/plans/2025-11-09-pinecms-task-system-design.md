# PineCMS Task System Design

**Date**: 2025-11-09
**Author**: PineCMS Development Team
**Status**: ✅ Validated Design
**Purpose**: Development execution system for building PineCMS v1.0.0-v1.2.0

---

## 1. Overview

This document specifies the comprehensive task management system for PineCMS development. The system provides implementation-ready tasks with full specifications, enabling systematic development from foundation through v1.2.0 release.

**Key Characteristics:**

- **Purpose**: Development execution (not planning/coordination)
- **Granularity**: Hybrid (epics → feature tasks → implementation steps)
- **Detail Level**: Full implementation guides with exact code patterns
- **Dependencies**: Strict sequential order (001 → 130)
- **Integration**: TodoWrite-driven progress tracking

---

## 2. Design Decisions

### 2.1 Core Decisions

| Aspect           | Decision                       | Rationale                                                                   |
| ---------------- | ------------------------------ | --------------------------------------------------------------------------- |
| **Purpose**      | Development execution          | Solo developer needs actionable tasks to implement sequentially             |
| **Granularity**  | Hybrid (epics → tasks → steps) | Strategic overview + detailed execution guidance                            |
| **Detail Level** | Full implementation guides     | Ensures consistency with quality standards (PHPStan 8, OWASP, 80% coverage) |
| **Dependencies** | Strict sequential (001-130)    | Clear unambiguous path, prevents analysis paralysis                         |
| **Integration**  | TodoWrite checklists           | Real-time progress tracking during Claude sessions                          |

### 2.2 Architecture Principles

**YAGNI Applied:**

- No complex dependency graphs
- No project management overhead
- No multi-team coordination features
- Simple Markdown + YAML front matter

**Quality-First:**

- Every task includes test specifications
- PHPStan Level 8 compliance built-in
- OWASP security patterns required
- 80% coverage targets

**TodoWrite Integration:**

- Tasks generate checklists automatically
- Real-time tracking during implementation
- Matches existing Claude Code workflow

---

## 3. Directory Structure

```
tasks/
├── 000-OVERVIEW.md                    # Master dashboard, progress tracking
├── 001-installer-foundation/          # Epic 001 folder
│   ├── _EPIC.md                       # Epic overview, goals, task list
│   ├── 001-system-requirements.md     # Task 001
│   ├── 002-env-generator.md           # Task 002
│   ├── 003-sqlite-setup.md            # Task 003
│   └── ...                            # Tasks 004-008
├── 002-database-schema/               # Epic 002 folder
│   ├── _EPIC.md
│   ├── 009-users-table.md             # Task 009
│   ├── 010-posts-table.md             # Task 010
│   └── ...
├── 003-content-management-backend/
│   ├── _EPIC.md
│   ├── 015-post-service.md
│   └── ...
└── _TEMPLATES/
    ├── EPIC-TEMPLATE.md               # Template for new epics
    └── TASK-TEMPLATE.md               # Template for new tasks
```

**Numbering Scheme:**

- **Epics**: 001-018 (18 total across v1.0-v1.2)
- **Tasks**: 001-130 (strictly sequential across entire project)
- Tasks within epic start at epic's base number (Epic 001 = tasks 001-008, Epic 002 = tasks 009-014)

---

## 4. File Formats

### 4.1 Epic Format (`_EPIC.md`)

```yaml
---
epic_id: 001
title: Installer & Foundation
status: pending # pending | in-progress | completed
priority: critical # critical | high | medium | low
estimated_duration: 2 weeks
actual_duration: null
week: Week 1-2
version: v1.0.0
tasks_total: 8
tasks_completed: 0
---
```

**Content Sections:**

1. **Epic Goals** - High-level objectives
2. **Features Delivered** - What ships when epic completes
3. **Task List** - All tasks with checkboxes and estimates
4. **Dependencies** - What blocks/requires this epic
5. **Success Criteria** - Epic-level acceptance criteria

### 4.2 Task Format (`XXX-task-name.md`)

```yaml
---
task_id: 001
epic: 001-installer-foundation
title: Implement System Requirements Check
status: pending # pending | in-progress | completed | blocked
priority: critical
estimated_effort: 4 hours
actual_effort: null
assignee: developer
dependencies: []
tags: [installer, validation, week-1]
---
```

**Content Sections:**

1. **Overview** - Task purpose and context
2. **Acceptance Criteria** - Testable requirements (checkboxes)
3. **Implementation Steps** - Detailed step-by-step guide with:
    - Exact file paths
    - Complete code structures
    - Database schemas
    - Validation rules
    - Event dispatching
4. **Testing Requirements** - Unit, Feature, Browser test specs
5. **Related Documentation** - PRD, Architecture, Quality refs
6. **Verification Steps** - Commands to run before marking complete

---

## 5. Implementation Patterns

### 5.1 Backend Pattern (Laravel 12)

**Every backend task follows:**

```
1. Migration (database/migrations/)
   - Schema: tables, columns, types, indexes, foreign keys
   - Encrypted columns specified (CipherSweet)

2. Model (app/Models/)
   - declare(strict_types=1); mandatory
   - Fillable, casts, relationships
   - Event dispatching (PostCreated, PostUpdated)

3. Service (app/Services/)
   - Business logic layer
   - Type hints (PHP 8.3+)
   - Error handling
   - Event dispatching

4. Repository (app/Repositories/) - if complex queries
   - Data access abstraction
   - Query optimization (eager loading, N+1 prevention)

5. Request (app/Http/Requests/)
   - Validation rules
   - Authorization logic
   - Custom error messages

6. Controller (app/Http/Controllers/)
   - HTTP handling only
   - Delegates to services
   - Returns Inertia::render()

7. Policy (app/Policies/)
   - Authorization logic
   - RBAC enforcement
```

### 5.2 Frontend Pattern (Vue 3.5 + Inertia 2.x)

**Every frontend task follows:**

```
1. Inertia Page (resources/js/Pages/)
   - Composition API (<script setup>)
   - PrimeVue components
   - <Form> component for forms
   - Type-safe props

2. Vue Component (resources/js/Components/)
   - Reusable components
   - TypeScript interfaces
   - Props validation
   - Emits declaration

3. Pinia Store (resources/js/Stores/) - if global state needed
   - State management
   - Actions
   - Getters
```

### 5.3 Testing Pattern

**Every task includes:**

```
1. Unit Tests (60% coverage target)
   tests/Unit/Services/
   tests/Unit/Models/

2. Feature Tests (30% coverage target)
   tests/Feature/Controllers/
   tests/Feature/API/

3. Browser Tests (10% coverage - critical flows)
   tests/Browser/

4. Frontend Tests (Vitest)
   resources/js/**/__tests__/
```

---

## 6. Quality Gates

### 6.1 Per-Task Checklist

Every task includes:

```markdown
## ✅ Quality Gates

### Code Quality

- [ ] PHPStan Level 8 passes (`composer analyse`)
- [ ] Laravel Pint formatted (`vendor/bin/pint`)
- [ ] ESLint passes (`npm run lint`)
- [ ] No console.log statements
- [ ] declare(strict_types=1); in all PHP files

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
```

### 6.2 Verification Commands

```bash
# Before marking task complete:
composer quality  # Runs: pint, analyse, test
npm run quality   # Runs: format, lint, type-check, test
```

---

## 7. Git Workflow Integration

### 7.1 Per-Task Workflow

```markdown
## Starting a Task

1. `git checkout -b task-001-system-requirements`
2. Mark task status: `in-progress`
3. Generate TodoWrite checklist

## During Implementation

- Commit frequently with conventional commits
- Example: `feat(installer): add PHP version check`
- Hooks catch quality issues automatically

## Completing a Task

1. Run: `composer quality && npm run quality`
2. All tests pass + PHPStan Level 8 clean
3. Mark task status: `completed`
4. Commit: `feat(installer): complete system requirements check`
5. Continue to next task (or PR if epic complete)
```

### 7.2 Conventional Commit Format

```
<type>(<scope>): <subject>

Types: feat, fix, docs, style, refactor, test, chore
Scope: installer, posts, media, auth, etc.
Subject: Imperative mood, lowercase, no period
```

---

## 8. PRD Mapping

### 8.1 Version Breakdown

**v1.0.0 MVP (95 features → ~90 tasks, 10 epics, Weeks 1-11):**

| Epic | Title                      | Week | Tasks | Features                                      |
| ---- | -------------------------- | ---- | ----- | --------------------------------------------- |
| 001  | Installer & Foundation     | 1-2  | 8     | System requirements, .env, SQLite, admin user |
| 002  | Database Schema & Models   | 3    | 6     | Users, Posts, Pages, Categories, Tags, Media  |
| 003  | Content Management Backend | 3-4  | 12    | Post CRUD, Service layer, Events, Policies    |
| 004  | TipTap Editor Integration  | 4    | 8     | Editor setup, Markdown, Live preview          |
| 005  | Media Library System       | 4-5  | 10    | Upload security, Image processing, Metadata   |
| 006  | User Management & Auth     | 5-6  | 9     | Auth, RBAC, Invite system, Sessions           |
| 007  | Theme System & Frontend    | 6    | 8     | Blade templates, Alpine.js, Theme switcher    |
| 008  | Categories & Tags          | 7    | 6     | Hierarchical categories, Tag management       |
| 009  | Admin Panel & Settings     | 8    | 10    | Dashboard, Settings tabs, Backup system       |
| 010  | Import/Export & SEO        | 9-10 | 12    | WordPress/Ghost import, SEO basics            |

**v1.1.0 (54 features → ~30 tasks, 4 epics, Weeks 12-14):**

| Epic | Title                   | Week  | Tasks | Features                                  |
| ---- | ----------------------- | ----- | ----- | ----------------------------------------- |
| 011  | Comments & Search       | 10-11 | 8     | Comment system, TNTSearch, CMD+K palette  |
| 012  | Advanced Media & Editor | 12    | 6     | File types, Bulk actions, TipTap advanced |
| 013  | Plugin System           | 13    | 10    | Event-driven plugins, Management UI       |
| 014  | Workflow System         | 13    | 6     | Draft→Review→Published, Notifications     |

**v1.2.0 (10 features → ~10 tasks, 2 epics, Weeks 14-16):**

| Epic | Title                  | Week | Tasks | Features                               |
| ---- | ---------------------- | ---- | ----- | -------------------------------------- |
| 015  | Import/Export Enhanced | 14   | 5     | Medium/Substack import, Error handling |
| 016  | Advanced Features      | 15   | 5     | Redirects, Menu builder, Widget system |

**Official Plugins (17 features → ~12 tasks, 2 epics, Parallel Weeks 10-13):**

| Epic | Title                 | Week  | Tasks | Features                                     |
| ---- | --------------------- | ----- | ----- | -------------------------------------------- |
| 017  | Newsletter Plugin     | 10-13 | 6     | Subscriber management, Campaign scheduling   |
| 018  | Multi-Language & SEO+ | 10-13 | 6     | Translation UI, Schema.org, Advanced sitemap |

**Total: 18 epics, ~130 tasks**

---

## 9. TodoWrite Integration

### 9.1 Automatic Checklist Generation

When starting an epic, Claude generates:

```javascript
TodoWrite([
    {
        content: 'System Requirements Check',
        status: 'in-progress',
        activeForm: 'Implementing system requirements check',
    },
    {
        content: 'Environment Configuration Generator',
        status: 'pending',
        activeForm: 'Generating environment configuration',
    },
    // ... remaining epic tasks
]);
```

### 9.2 Real-Time Progress Tracking

- As tasks complete, TodoWrite status updates
- Claude tracks current task during implementation
- User sees progress in real-time
- Blockers surfaced immediately

---

## 10. Reference System

### 10.1 Cross-References Per Task

Every task links to:

```markdown
## 📚 References

### PRD Specifications

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.1
- **Timeline**: Week 1-2 (v1.0.0 MVP)
- **Success Criteria**: Installation < 5 minutes

### Architecture

- **Pattern**: Service Layer (`docs/prd/04-ARCHITECTURE.md`)
- **Events**: SystemInstalled (`docs/prd/06-PLUGIN-ECOSYSTEM.md`)
- **Storage**: SQLite + Flat-File Hybrid

### Quality Requirements

- **Security**: Input validation (`docs/prd/09-QUALITY-REQUIREMENTS.md`)
- **Performance**: < 1s requirements check
- **Testing**: 80% coverage required
- **Accessibility**: N/A (installer internal)

### Related Tasks

- **Next**: 002-env-generator
- **Blocks**: None
- **Depends On**: None
```

### 10.2 Documentation Hierarchy

```
Tasks Reference:
  ↓
PRD Documents (docs/prd/):
  - 05-CORE-FEATURES.md (Feature specs)
  - 04-ARCHITECTURE.md (Design patterns)
  - 09-QUALITY-REQUIREMENTS.md (Standards)
  - 10-RELEASE-ROADMAP.md (Timeline)
  ↓
Claude Instructions (.claude/instructions/):
  - backend.md (Laravel patterns)
  - frontend.md (Vue patterns)
  - testing.md (Test strategies)
  - security.md (OWASP compliance)
  ↓
Project Root:
  - CLAUDE.md (Core rules)
  - Spatie Laravel Guidelines
```

---

## 11. Task Generation Strategy

### 11.1 Upfront vs. Just-in-Time

**Decision**: Generate all ~130 tasks upfront

**Rationale:**

- Clear roadmap visibility
- Accurate effort estimation
- Prevents scope creep
- Enables timeline validation
- PRD already detailed enough

### 11.2 Generation Process

1. **Epic Generation** (18 epics)
    - Map PRD sections to epics
    - Define epic goals and features
    - Estimate task count per epic

2. **Task Generation** (~130 tasks)
    - Break epics into implementable tasks
    - Write full implementation guides
    - Specify test requirements
    - Add cross-references

3. **Validation**
    - Verify sequential numbering
    - Check dependency chains
    - Validate effort estimates
    - Review against PRD

---

## 12. Success Metrics

### 12.1 System Success Criteria

**The task system succeeds if:**

- ✅ Developer can implement any task without additional research
- ✅ Quality gates pass on first try (PHPStan, tests, coverage)
- ✅ Tasks complete within estimated effort (±20%)
- ✅ Zero ambiguity or missing information
- ✅ TodoWrite tracking provides accurate progress
- ✅ Epic completion triggers deployable increments

### 12.2 Task Quality Metrics

**Each task measured by:**

- Time to complete vs. estimate
- Quality gate pass rate (first try)
- Number of clarification requests
- Rework cycles needed

---

## 13. Maintenance & Evolution

### 13.1 Task Updates

**When to update tasks:**

- Blocker discovered (mark status: blocked)
- Effort estimate significantly off (adjust, document why)
- Implementation approach changed (update steps, note reason)
- PRD updated (cascade changes to affected tasks)

### 13.2 Adding New Tasks

**Process:**

1. Identify need during implementation
2. Create task file from template
3. Insert in sequence (may need renumbering)
4. Update epic task count
5. Update 000-OVERVIEW.md

---

## 14. Implementation Timeline

### 14.1 Task System Creation

**Effort**: ~6-8 hours to generate all 130 tasks

**Steps:**

1. Create directory structure (30 min)
2. Generate 18 epic files (2 hours)
3. Generate ~130 task files (4-5 hours)
4. Create 000-OVERVIEW.md (30 min)
5. Create templates (30 min)
6. Validation pass (30 min)

### 14.2 Development Timeline

**Using this system:**

- v1.0.0 MVP: 10-11 weeks (~90 tasks)
- v1.1.0: +3-4 weeks (~30 tasks)
- v1.2.0: +2-3 weeks (~10 tasks)

**Total: 15-18 weeks as planned in PRD**

---

## 15. Conclusion

This task system design provides:

- ✅ **Implementation-ready tasks** with full specifications
- ✅ **Quality-first approach** with built-in gates
- ✅ **TodoWrite integration** for real-time tracking
- ✅ **Sequential simplicity** avoiding complexity
- ✅ **PRD alignment** ensuring completeness

**Next Steps:**

1. Validate this design document ✅ DONE
2. Generate all 130 tasks
3. Begin development at Task 001

---

**Document Status**: ✅ Design Validated & Approved
**Ready for**: Task Generation Phase
**Estimated Generation Time**: 6-8 hours
