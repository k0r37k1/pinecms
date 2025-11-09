# Epic Overview: [Epic Name]

**Epic ID:** `XX-epic-name`
**Phase:** `v1.0.0-week-X-Y` or `v1.1.0-week-Z`
**Status:** 🔄 In Progress | ✅ Complete | 📋 Planned
**Priority:** Critical | High | Medium | Low

---

## Executive Summary

**What this epic delivers:**
[1-2 sentence summary of the user-facing value and technical outcome]

**User Value:**
- [Key benefit 1 for end users]
- [Key benefit 2 for end users]
- [Key benefit 3 for end users]

**Technical Value:**
- [Architecture improvement 1]
- [Infrastructure improvement 2]
- [Foundation for future features]

---

## Timeline & Scope

### Development Timeline

**Planned Duration:** X weeks
**Start Date:** 2025-XX-XX (Week Y)
**Target Completion:** 2025-XX-XX (Week Z)

**Milestones:**
- **Week X:** [Major milestone 1]
- **Week Y:** [Major milestone 2]
- **Week Z:** [Final completion & review]

### Task Breakdown

**Total Tasks:** XX tasks
**Estimated Hours:** XX-XX hours (X-X hours per task average)

**Task Categories:**
- Database Schema & Models: XX tasks (XX hours)
- Repositories & Services: XX tasks (XX hours)
- Controllers & Routes: XX tasks (XX hours)
- Frontend Components: XX tasks (XX hours)
- Testing & Quality: XX tasks (XX hours)

**Daily Capacity:** 2-3 tasks per developer (assuming 1-4 hour task granularity)

---

## Feature Scope

### Core Features (from PRD)

**PRD Reference:** `docs/prd/XX-FILENAME.md`

**Features Included in This Epic:**
1. **[Feature Name 1]** (`TASK-XXX` to `TASK-YYY`)
   - User Story: As a [user type], I want to [action] so that [benefit]
   - Acceptance Criteria: [Key requirement]
   - Related PRD Section: `docs/prd/XX-FILE.md#section`

2. **[Feature Name 2]** (`TASK-ZZZ` to `TASK-WWW`)
   - User Story: As a [user type], I want to [action] so that [benefit]
   - Acceptance Criteria: [Key requirement]
   - Related PRD Section: `docs/prd/XX-FILE.md#section`

3. **[Feature Name 3]** (`TASK-AAA` to `TASK-BBB`)
   - User Story: As a [user type], I want to [action] so that [benefit]
   - Acceptance Criteria: [Key requirement]
   - Related PRD Section: `docs/prd/XX-FILE.md#section`

### Features Explicitly Out of Scope
- [Feature deferred to v1.1.0]
- [Feature deferred to v1.2.0]
- [Feature not needed until plugin system ready]

---

## Key Architectural Decisions

### Architecture Decision Records (ADRs)

**Related ADRs:**
- `docs/architecture/ADR-XXX.md` - [Decision title]
- `docs/architecture/ADR-YYY.md` - [Decision title]

### Design Patterns

**Patterns Applied in This Epic:**
1. **Repository Pattern** (Data Access Layer)
   - Why: Decouple business logic from data storage
   - Where: All model interactions go through repositories
   - Example: `App\Repositories\PostRepository`

2. **Service Layer Pattern** (Business Logic)
   - Why: Keep controllers thin, centralize business rules
   - Where: Complex operations, multi-step workflows
   - Example: `App\Services\ContentManagementService`

3. **Event-Driven Architecture** (Side Effects)
   - Why: Decouple actions from consequences
   - Where: Post-save operations, notifications, logging
   - Example: `App\Events\PostCreated` → `App\Listeners\UpdateSitemap`

4. **[Other Pattern]**
   - Why: [Reason for choosing this pattern]
   - Where: [Where it's applied]
   - Example: [Concrete example]

### Technology Stack

**Backend:**
- PHP 8.3
- Laravel 12
- SQLite/MySQL (hybrid storage)
- [Other backend tech specific to this epic]

**Frontend:**
- Vue 3.5 Composition API
- Inertia.js 2.x
- PrimeVue
- TailwindCSS 4.1
- [Other frontend tech specific to this epic]

**Testing:**
- PHPUnit (backend)
- Vitest (frontend)
- Playwright (E2E)

### Database Schema Changes

**New Tables:**
- `table_name` - [Purpose]
- `another_table` - [Purpose]

**Modified Tables:**
- `existing_table` - [What changed]

**Migrations:**
- Created in tasks: `TASK-XXX`, `TASK-YYY`
- Migration files: `YYYY_MM_DD_HHMMSS_create_table_name_table.php`

**ERD Reference:** `docs/architecture/database-schema.md#epic-name`

---

## Team Assignments

### Recommended Specialized Agents

**Primary Agents for This Epic:**
- **backend-architect** (XX tasks) - Database schema, models, repositories
- **frontend-developer** (XX tasks) - Vue components, Inertia pages
- **fullstack-developer** (XX tasks) - End-to-end feature implementation
- **test-engineer** (XX tasks) - Test suite creation, coverage

**Support Agents:**
- **code-reviewer** - Post-implementation reviews
- **debugger** - Issue investigation
- **security-auditor** - Security validation (if applicable)

### Work Distribution

**Backend-Heavy Tasks:** `TASK-XXX` to `TASK-YYY` (XX tasks)
- Focus: Database, Services, Repositories, Events
- Agent: backend-architect or fullstack-developer

**Frontend-Heavy Tasks:** `TASK-ZZZ` to `TASK-WWW` (XX tasks)
- Focus: Vue components, Inertia pages, PrimeVue integration
- Agent: frontend-developer or fullstack-developer

**Hybrid Tasks:** `TASK-AAA` to `TASK-BBB` (XX tasks)
- Focus: Complete features spanning backend + frontend
- Agent: fullstack-developer

**Testing Tasks:** `TASK-CCC` to `TASK-DDD` (XX tasks)
- Focus: Unit, Feature, E2E tests
- Agent: test-engineer or primary feature implementer

---

## Dependencies & Blockers

### External Dependencies

**Must Complete Before Starting:**
- [ ] Epic: `XX-previous-epic` (provides foundation)
- [ ] Task: `TASK-XXX` from different epic (provides shared component)
- [ ] Infrastructure: [Database setup, environment config, etc.]

**Optional Dependencies (Helpful Context):**
- Epic: `YY-related-epic` (similar patterns)
- Documentation: `docs/architecture/ADR-ZZZ.md` (design rationale)

### Blocking Other Work

**This Epic Blocks:**
- Epic: `ZZ-dependent-epic` (needs features from this epic)
- Task: `TASK-WWW` in other epic (depends on API endpoints)

### Critical Path Tasks

**Tasks That Must Complete On Schedule:**
- `TASK-XXX` - [Why critical]
- `TASK-YYY` - [Why critical]
- `TASK-ZZZ` - [Why critical]

**Estimated Critical Path Duration:** X days (Y% of epic duration)

---

## Quality Gates & Acceptance Criteria

### Epic-Level Acceptance Criteria

**Must Meet All of These:**
- [ ] All XX tasks completed and tests passing
- [ ] Code coverage >80% for new code
- [ ] No PHPStan Level 8 errors
- [ ] No ESLint errors or warnings
- [ ] All feature acceptance criteria met (see PRD)
- [ ] Documentation updated (README, architecture docs)
- [ ] No security vulnerabilities introduced (OWASP Top 10 check)
- [ ] Performance benchmarks met (if applicable)
- [ ] Accessibility standards met (WCAG 2.1 AA, if applicable)

### Quality Checks

**Backend Quality:**
```bash
composer quality  # PHPStan L8 + Pint + PHPUnit
# Expected: All checks pass
```

**Frontend Quality:**
```bash
npm run quality   # ESLint + Prettier + TypeScript + Vitest
# Expected: All checks pass
```

**E2E Tests:**
```bash
npx playwright test
# Expected: All workflows complete successfully
```

### Performance Benchmarks

**If Applicable:**
- Page load time: <XX ms
- Database query time: <XX ms
- API response time: <XX ms
- Frontend bundle size: <XX KB

---

## Risk Assessment

### High-Risk Areas

**Risk 1: [Technical Risk]**
- **Likelihood:** High | Medium | Low
- **Impact:** High | Medium | Low
- **Mitigation:** [How to reduce risk]
- **Contingency:** [Backup plan if risk occurs]

**Risk 2: [Schedule Risk]**
- **Likelihood:** High | Medium | Low
- **Impact:** High | Medium | Low
- **Mitigation:** [How to reduce risk]
- **Contingency:** [Backup plan if risk occurs]

### Complexity Hotspots

**Complex Tasks (>3 hours or high uncertainty):**
- `TASK-XXX` - [Why complex, how to approach]
- `TASK-YYY` - [Why complex, how to approach]

**Vibe Check Recommended:**
- Before starting: `TASK-XXX`, `TASK-YYY`
- During implementation: If any task exceeds estimate by 50%
- Before completing epic: Final architecture review

---

## Success Metrics

### Development Metrics

**Velocity:**
- Target: X tasks per week
- Target: Y hours per week

**Quality:**
- Test coverage: >80%
- Bug rate: <X bugs per 100 tasks
- Rework rate: <Y% of tasks need revision

### User Impact Metrics

**After Epic Completion:**
- Users can [action 1]
- Users can [action 2]
- Users experience [improvement]

**Measurement:**
- [How to measure success]
- [Key performance indicator]

---

## Handoff & Next Steps

### Epic Completion Checklist

- [ ] All tasks marked as completed
- [ ] All tests passing (unit, feature, E2E)
- [ ] All quality gates passed
- [ ] Documentation updated
- [ ] Code reviewed by `code-reviewer` agent
- [ ] Epic acceptance criteria verified
- [ ] Completion summary created (`_COMPLETION.md`)
- [ ] Dependent teams notified

### What This Epic Unblocks

**Immediate Next Steps:**
- Epic: `ZZ-next-epic` can now start
- Task: `TASK-WWW` in other epic now unblocked

**Future Phases:**
- This epic provides foundation for [v1.1.0 feature]
- This epic enables [v1.2.0 feature]

### Team Notifications

**After Completion, Notify:**
- [Team/role] about [what they need to know]
- [Stakeholder] about [deliverable ready for review]

---

## Resources & References

### Documentation
- PRD: `docs/prd/XX-FILENAME.md`
- Architecture: `docs/architecture/ADR-XXX.md`
- Coding Standards: `.claude/instructions/backend.md`, `.claude/instructions/frontend.md`
- Testing Guidelines: `.claude/workflows/tdd.md`

### Related Epics
- Previous: `XX-previous-epic`
- Parallel: `YY-parallel-epic`
- Next: `ZZ-next-epic`

### Task Files
- Task list: `tasks/XX-epic-name/`
- Dependencies: `tasks/XX-epic-name/_DEPENDENCIES.md`
- Completion: `tasks/XX-epic-name/_COMPLETION.md` (created after completion)

---

**Template Version:** 1.0
**Last Updated:** 2025-11-09
**Related Design:** `docs/plans/2025-11-09-pinecms-task-system-design.md`
