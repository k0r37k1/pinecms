# PineCMS Task System Design

**Date:** 2025-11-09
**Status:** ✅ Approved
**Type:** Task Management System
**Scope:** v1.0.0 MVP + Plugin System (~670 tasks)

---

## Executive Summary

This document defines the comprehensive task breakdown system for PineCMS development. The system provides **670 implementation-level tasks** organized hierarchically with explicit dependencies, agent assignments, and quality gates.

**Key Characteristics:**
- **Maximum comprehensiveness** - Ultra-detailed breakdown with 1-4 hour granularity
- **Implementation-level tasks** - Each task is immediately actionable
- **Hybrid format** - Markdown with YAML front matter (Laravel-familiar)
- **Explicit dependencies** - Required, optional, blocks, parallelizable mappings
- **Agent integration** - 14 specialized Claude Code agents specified per task
- **TDD mandatory** - RED-GREEN-REFACTOR workflow enforced
- **Quality gates** - composer/npm quality checks before completion

**Coverage:**
- v1.0.0 Core: ~500 tasks (10-11 weeks, ~95 features)
- Plugin System: ~170 tasks (v1.1.0, ~17 plugin features)
- Total: 670 tasks covering critical path to working CMS with plugins

---

## 1. Overall Structure

### 1.1 Task Organization

```
tasks/
├── 00-foundation/           # Week 1-2: Installer & Setup (45 tasks)
├── 01-content-management/   # Week 3-5: Posts, Pages, Media (125 tasks)
├── 02-user-system/          # Week 6: Users, Auth, Themes (65 tasks)
├── 03-taxonomy/             # Week 7: Categories, Tags (55 tasks)
├── 04-admin-panel/          # Week 8: Dashboard, Settings (80 tasks)
├── 05-import-export/        # Week 9: Migration Tools (50 tasks)
├── 06-seo-privacy/          # Week 10: SEO, Security, Backup (80 tasks)
├── 07-plugin-system/        # Week 11-13: Plugin Architecture (90 tasks)
├── 08-official-plugins/     # Week 11-13: Newsletter, Multi-lang, SEO+ (80 tasks)
├── _TEMPLATES/              # Reusable task templates
└── README.md                # Master index with navigation
```

### 1.2 Task Density

- **Average:** 40-60 tasks per week
- **Granularity:** 1-4 hours per task
- **Daily capacity:** 2-3 tasks per developer
- **Total estimated hours:** ~1,800-2,200 hours (v1.0.0 + plugins)

---

## 2. Task File Format

### 2.1 YAML Front Matter Structure

```yaml
---
id: TASK-045
title: "Implement TipTap Bold/Italic/Strike Extensions"
phase: "v1.0.0-week-4"
priority: critical
estimated_hours: 3
tags: [frontend, vue, tiptap, editor]

dependencies:
  required: [TASK-040, TASK-042]
  optional: [TASK-038]

blocks: [TASK-046, TASK-047]
parallelizable_with: [TASK-048, TASK-049]

specialized_agents:
  recommended: frontend-developer
  alternative: [fullstack-developer]
  review_with: code-reviewer

software_principles:
  - "DRY: Reuse extension base class"
  - "KISS: Use TipTap built-ins"
  - "YAGNI: Defer advanced features to v1.1.0"
  - "Single Responsibility: One extension per formatting type"

files_created:
  - "resources/js/Components/Editor/Extensions/BoldExtension.ts"
  - "resources/js/Components/Editor/Extensions/ItalicExtension.ts"

files_modified:
  - "resources/js/Components/Editor/TipTapEditor.vue"
  - "package.json"

acceptance_criteria:
  - "Bold formatting works with Cmd+B / Ctrl+B"
  - "Markdown shortcuts work (**bold**, *italic*)"
  - "Vitest tests pass with >80% coverage"
  - "ESLint shows no errors"

vibe_check_triggers:
  - "If implementation >100 lines per extension"
  - "If tempted to add features beyond basic formatting"
  - "Before commit if acceptance criteria unclear"

mcp_tools_needed:
  - context7 # TipTap documentation
  - eslint # Linting
  - vibe-check # Complexity check
  - playwright # E2E testing

related_docs:
  - "docs/prd/05-CORE-FEATURES.md#tiptap-editor"
  - ".claude/instructions/frontend.md#vue-composition-api"
---
```

### 2.2 Task Content Structure

```markdown
# TASK-045: Implement TipTap Bold/Italic/Strike Extensions

## Context & Background
[Why this task exists, PRD reference, architectural context]

## Prerequisites
[Required/optional dependencies, knowledge needed]

## Implementation Steps
[Detailed step-by-step with code examples, timing estimates]

## Testing Strategy
[Unit/E2E tests, coverage targets]

## Common Pitfalls
[Known issues, mistakes to avoid]

## Vibe Check Moments
[When to pause and validate approach]

## Definition of Done
[Checklist of completion criteria]

## Next Steps After Completion
[What this unblocks, who to notify]
```

---

## 3. Epic Organization

### 3.1 Standard Epic Structure

```
tasks/01-content-management/
├── _OVERVIEW.md              # Epic summary (5-10 pages)
├── _DEPENDENCIES.md          # Visual dependency graph (Mermaid)
├── _COMPLETION.md            # Epic-level DoD
├── 01-database-schema/
│   ├── TASK-100-create-posts-migration.md
│   ├── TASK-101-create-posts-model.md
│   └── ...
├── 02-repositories/
├── 03-services/
├── 04-controllers/
├── 05-frontend/
└── 06-testing/
```

### 3.2 Epic Documentation

**_OVERVIEW.md:**
- Epic goal & user value
- Week timeline (from roadmap)
- Feature count from PRD
- Key architectural decisions
- Team assignments

**_DEPENDENCIES.md:**
- Mermaid task relationship diagram
- Critical path highlighted
- Parallel work opportunities
- External dependencies

**_COMPLETION.md:**
- Epic acceptance criteria
- Quality gates (coverage, performance)
- Migration checklist
- Handoff notes

---

## 4. Naming Conventions

### 4.1 Task ID Numbering

```
000-099:   Foundation & Installer
100-299:   Content Management
  100-149: Database Schema & Models
  150-199: Repositories & Services
  200-249: Controllers & Routes
  250-299: Frontend Components
300-399:   User System & Theme
400-499:   Taxonomy
500-599:   Admin Panel & Settings
600-699:   Import/Export
700-799:   SEO, Privacy, Backup
800-899:   Plugin System
900-999:   Official Plugins
```

### 4.2 File Naming Pattern

```
TASK-{number}-{kebab-case-description}.md

Examples:
TASK-001-create-web-installer-route.md
TASK-045-implement-tiptap-bold-italic.md
TASK-234-create-post-list-component.md
```

### 4.3 Tag Taxonomy

```yaml
# Technology: [backend, frontend, fullstack, database, api]
# Laravel Layers: [migration, model, repository, service, controller, event]
# Frontend: [vue, inertia, tiptap, primevue, alpine, tailwind]
# Features: [content-management, user-system, media, taxonomy, seo]
# Quality: [testing, documentation, performance, accessibility, security]
# Priority: [critical, high, medium, low]
```

---

## 5. Development Workflow Integration

### 5.1 TDD Workflow (Mandatory)

```markdown
### 1. RED - Write Failing Test (30 min)
php artisan test --filter=test_name
Expected: ❌ FAIL

### 2. GREEN - Minimal Implementation (1.5 hours)
php artisan test --filter=test_name
Expected: ✅ PASS

### 3. REFACTOR - Improve Code (45 min)
composer quality
Expected: All checks pass
```

### 5.2 Quality Gates

```bash
# Backend
composer quality  # PHPStan L8 + Pint + PHPUnit

# Frontend
npm run quality   # ESLint + Prettier + Vitest

# E2E
npx playwright test
```

### 5.3 MCP Tool Usage

Each task specifies which MCP servers to use:
- **laravel-boost:** search-docs, tinker, database-query, browser-logs
- **filesystem:** Flat-file operations
- **context7:** PrimeVue, TipTap, Alpine docs
- **vibe-check:** Tunnel vision prevention
- **eslint, playwright, chrome-devtools:** Testing/debugging

---

## 6. Post-Task Documentation

### 6.1 Completion Summary Format

```markdown
---
task_id: TASK-001
completed_date: 2025-11-15
completed_by: fullstack-developer (agent)
actual_hours: 2.5
estimated_hours: 2.0
variance: +0.5 hours
---

# Task Completion Summary

## What Was Built
[Files created/modified, database changes]

## Implementation Decisions
[Why certain approaches, trade-offs, alternatives rejected]

## Testing Results
[Test count, coverage, quality checks]

## Challenges & Solutions
[Problems encountered, how solved]

## Knowledge Gained
[Lessons learned, tips for future tasks]

## Next Developer Notes
[Handoff notes, dependencies for upcoming tasks]
```

---

## 7. Master Index & Navigation

### 7.1 README.md Structure

```markdown
# PineCMS Task System - Master Index

## Quick Navigation
- By Phase (Week 1-2, Week 3-5, etc.)
- By Priority (Core Features, Plugin Ecosystem, UX-UI)
- By Technology (Backend, Frontend, Hybrid)
- By Agent Type (frontend-developer, backend-architect, etc.)

## Task Status Dashboard
[Progress bars, completion percentages]

## Critical Path Tasks (60 tasks)
[Must-complete-on-schedule tasks]

## Blockers & Dependencies
[Currently blocked, parallel work opportunities]

## Search & Filters
[grep/find commands for task discovery]
```

---

## 8. Task Generation Strategy

### 8.1 Four-Phase Approach

**Phase 1: Automated Skeleton (1-2 hours)**
- Parse PRDs → extract features → map to tasks
- Generate 670 files with complete YAML
- Create folder structure
- Generate epic documentation (_OVERVIEW, _DEPENDENCIES, _COMPLETION)

**Phase 2: Content Enrichment (3-5 hours per epic)**
- Fill implementation steps with code examples
- Add testing strategies
- Document common pitfalls
- Define acceptance criteria

**Phase 3: Dependency Mapping (2-3 hours)**
- Analyze dependencies across tasks
- Create Mermaid diagrams
- Identify critical path (60 tasks)
- Mark parallelizable clusters

**Phase 4: Validation (1-2 hours)**
- Check all YAML complete
- Verify no circular dependencies
- Validate file paths
- Confirm hour estimates match timeline

### 8.2 Ongoing Maintenance

**Weekly Review (Every Friday):**
- Update status dashboard
- Identify blockers for next week
- Adjust critical path if delays
- Generate burndown chart

**Version Control:**
```bash
git add tasks/
git commit -m "docs(tasks): add Week X tasks (TASK-XXX to TASK-YYY)"
```

---

## 9. Success Criteria

### 9.1 Task System Quality

- ✅ All 670 tasks have complete YAML front matter
- ✅ All tasks have implementation steps with code examples
- ✅ All tasks specify MCP tools and agents
- ✅ All tasks have acceptance criteria
- ✅ No circular dependencies
- ✅ Critical path identified (60 tasks)
- ✅ Estimated hours total matches timeline (1,800-2,200 hours)

### 9.2 Coverage

- ✅ All ~95 v1.0.0 core features covered
- ✅ All ~17 plugin features covered (Newsletter, Multi-lang, SEO+)
- ✅ All weeks (1-13) have task breakdown
- ✅ All priority areas addressed (Core, Plugin, UX-UI)

### 9.3 Usability

- ✅ Master index navigable (multiple views)
- ✅ Tasks discoverable (grep/find commands work)
- ✅ Dependencies clear (Mermaid diagrams)
- ✅ Progress trackable (status dashboard)

---

## 10. Next Steps

After design approval:

1. **Write Implementation Plan** (superpowers:writing-plans)
   - Detailed step-by-step task generation plan
   - Batch execution strategy
   - Review checkpoints

2. **Create Task Folder Structure**
   - Generate 9 epic folders
   - Create _TEMPLATES directory
   - Initialize README.md

3. **Generate Tasks in Batches**
   - Start with 00-foundation (45 tasks)
   - Review and refine
   - Continue with 01-content-management (125 tasks)
   - Iterate through all 9 epics

4. **Validate & Deploy**
   - Run quality checks
   - Generate dependency graphs
   - Commit to git
   - Begin development

---

**Design Status:** ✅ Complete and Approved
**Ready for:** Implementation Plan (superpowers:writing-plans)
**Estimated Time to Generate All Tasks:** 15-25 hours (across 4 phases)
