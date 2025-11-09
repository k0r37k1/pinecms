---
epic_id: 003
title: Content Management Backend
status: pending
priority: critical
estimated_duration: 3 weeks
actual_duration: null
week: Week 4-6
version: v1.0.0
tasks_total: 12
tasks_completed: 0
---

# Epic 003: Content Management Backend

## 🎯 Epic Goals

- Implement complete CRUD operations for posts and pages
- Build service layer for business logic (validation, slug generation, scheduling)
- Create repository layer for hybrid storage (SQLite + Flat-File)
- Implement revision system for version control
- Enable content scheduling (publish/unpublish dates)
- Add concurrent editing protection (optimistic locking)
- Build auto-save functionality for draft protection
- Implement custom fields system

## 📦 Features Delivered

- Post CRUD (Create, Read, Update, Delete, Duplicate)
- Page CRUD with similar functionality
- Revision management (create, restore, compare, cleanup)
- Content scheduling (timezone-aware, visit-triggered or cron)
- Auto-save (configurable intervals: 30s/60s/120s)
- Slug management (auto-generate, custom, uniqueness validation)
- Lock version handling for concurrent editing
- Custom fields (text, number, boolean, date/time)
- Bulk actions (delete, status change, category assignment)
- Content search and filtering

## 📋 Tasks (12 total)

- [ ] 015 - Post CRUD Service & Repository (8h)
- [ ] 016 - Page CRUD Service & Repository (6h)
- [ ] 017 - Revision System Implementation (7h)
- [ ] 018 - Content Scheduler Service (6h)
- [ ] 019 - Auto-Save Implementation (5h)
- [ ] 020 - Slug Management Service (4h)
- [ ] 021 - Concurrent Editing Protection (5h)
- [ ] 022 - Custom Fields System (7h)
- [ ] 023 - Bulk Actions Service (5h)
- [ ] 024 - Content Search & Filtering (6h)
- [ ] 025 - Post/Page Controllers (8h)
- [ ] 026 - Form Request Validation (5h)

## 🔗 Dependencies

**Blocks:**
- Epic 004 (TipTap Editor Integration)
- Epic 007 (Theme System & Frontend)
- Epic 009 (Admin Panel & Settings)

**Requires:**
- Epic 002 (Database Schema & Models) - Must have Post/Page models

## ✅ Epic Success Criteria

- [ ] Posts and pages can be created, edited, deleted via API
- [ ] Hybrid storage (SQLite + Flat-File) working seamlessly
- [ ] Revisions created automatically on save (max 10 per post)
- [ ] Restore to any revision works correctly
- [ ] Scheduled posts publish automatically (visit-triggered or cron)
- [ ] Auto-save prevents content loss (30s interval default)
- [ ] Slugs generated automatically, editable, unique validation
- [ ] Concurrent editing detected and conflict resolution offered
- [ ] Custom fields can be added, edited, deleted
- [ ] Bulk actions execute without errors
- [ ] Content search returns relevant results
- [ ] All 12 tasks completed with tests passing
- [ ] Quality gates passing (PHPStan Level 8, 80% coverage)

## 📚 References

**PRD Specifications:**
- Feature: `docs/prd/05-CORE-FEATURES.md` Section 2.2 (Content Management)
- Timeline: Week 4-6 (v1.0.0)

**Architecture:**
- Pattern: Layered (Controllers → Services → Repositories → Models)
- Storage: Hybrid (SQLite metadata + flat-file content)
- Events: PostCreated, PostUpdated, PostDeleted, PostPublished

**Quality Requirements:**
- Security: Input validation, authorization checks, XSS prevention
- Performance: Queries < 100ms, auto-save debounced
- Testing: > 80% coverage, feature tests for all CRUD operations
- Data Integrity: Transaction-wrapped saves, atomic file writes

**Acceptance Criteria:**
- No data loss during auto-save or concurrent editing
- Revisions stored efficiently (content snapshots)
- Scheduled posts trigger reliably (95%+ accuracy)
- Slug conflicts resolved gracefully (append -2, -3, etc.)
