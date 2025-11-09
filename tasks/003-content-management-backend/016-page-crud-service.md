---
task_id: 016
epic: 003-content-management-backend
title: Page CRUD Service & Repository
status: pending
priority: critical
estimated_effort: 6 hours
actual_effort: null
assignee: backend-architect
dependencies: [015]
tags: [backend, service-layer, hybrid-storage, week-4]
---

# Task 016: Page CRUD Service & Repository

## 📋 Overview

Implement complete CRUD service and repository for pages with hybrid storage (SQLite metadata + flat-file Markdown content). Pages share similar patterns with posts but support hierarchical parent-child relationships and custom templates.

## 🎯 Acceptance Criteria

- [ ] PageService with all CRUD methods
- [ ] PageRepository for data access
- [ ] Hybrid storage (SQLite + flat-file) working
- [ ] Transaction-wrapped saves (rollback on file write failure)
- [ ] Events dispatched (PageCreated, PageUpdated, PageDeleted)
- [ ] Parent-child relationship management
- [ ] Template selection support
- [ ] Slug auto-generation from title
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create PageRepository

**File**: `app/Repositories/PageRepository.php`

**Methods**:
- findById(int $id): ?Page
- findBySlug(string $slug): ?Page
- findChildren(int $parentId): Collection
- create(array $data, string $content): Page
- update(Page $page, array $data, ?string $content): bool
- delete(Page $page): bool
- paginate(int $perPage, array $filters): LengthAwarePaginator

**Hybrid Storage Logic**:
- Save metadata to SQLite (title, slug, parent_id, template, status)
- Save content to flat-file (`storage/content/pages/slug.md`)
- Wrap in database transaction
- Rollback DB if file write fails

### Step 2: Create PageService

**File**: `app/Services/Content/PageService.php`

**Methods**:
- createPage(array $data, string $content): Page
- updatePage(Page $page, array $data, ?string $content): Page
- deletePage(Page $page): bool
- duplicatePage(Page $page): Page
- publishPage(Page $page): bool
- unpublishPage(Page $page): bool
- reorderChildren(int $parentId, array $order): bool

**Business Logic**:
- Auto-generate slug from title (Str::slug)
- Validate slug uniqueness within same parent
- Validate parent-child relationship (no circular references)
- Generate excerpt if not provided (first 160 chars)
- Increment lock_version on update
- Dispatch events

### Step 3: Hierarchical Validation

**Business Rules**:
- Maximum nesting depth: 3 levels
- Cannot set self as parent
- Cannot set descendant as parent (circular reference)
- Soft delete children when parent deleted (cascade)
- Update child paths when parent slug changes

### Step 4: Implement Event Dispatching

**Files**:
- `app/Events/PageCreated.php`
- `app/Events/PageUpdated.php`
- `app/Events/PageDeleted.php`

**Event Properties**:
- public Page $page
- public User $user (who performed action)
- public array $changes (for Updated event)

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Services/PageServiceTest.php`
  - Test createPage with slug generation
  - Test updatePage with lock_version increment
  - Test slug uniqueness within parent
  - Test parent-child validation
  - Test circular reference prevention
  - Test reorderChildren

- `tests/Unit/Repositories/PageRepositoryTest.php`
  - Test hybrid storage save
  - Test transaction rollback on file failure
  - Test findChildren
  - Test pagination with filters

**Feature Tests**:
- `tests/Feature/Content/PageCrudTest.php`
  - Test complete create page flow
  - Test update page flow
  - Test delete page (soft delete with cascade)
  - Test duplicate page
  - Test publish/unpublish
  - Test hierarchical operations

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.2.3 (Pages CRUD)
- **Timeline**: Week 4 (v1.0.0)

**Architecture:**
- **Pattern**: Repository Pattern (`docs/prd/04-ARCHITECTURE.md` Section 9)
- **Storage**: Hybrid (SQLite + Flat-File)
- **Events**: PageCreated, PageUpdated, PageDeleted

**Quality Requirements:**
- **Security**: Input sanitization, authorization checks
- **Performance**: Save operation < 200ms
- **Testing**: > 80% coverage

**Related Tasks:**
- **Previous**: 015-post-crud-service
- **Next**: 017-revision-system
- **Blocks**: 025-post-page-controllers
- **Depends On**: 010-content-schema, 015-post-crud-service

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing
- [ ] Unit tests passing (15+ test cases)
- [ ] Feature tests passing (6+ scenarios)
- [ ] Edge cases covered (circular refs, max depth, cascade deletes)
- [ ] Transaction rollback tested

### Security
- [ ] Input sanitization (strip tags from title)
- [ ] Slug sanitization (no special chars)
- [ ] File path validation (no directory traversal)
- [ ] Parent-child validation (prevent circular refs)

### Documentation
- [ ] Service methods documented
- [ ] Hierarchical rules documented
- [ ] Event payloads documented

## ✅ Verification Steps

```bash
# Test page creation
php artisan tinker
>>> $page = app(PageService::class)->createPage([
...   'title' => 'About Us',
...   'parent_id' => null,
...   'template' => 'default',
...   'status' => 'published'
... ], '# About Content');
>>> $page->id;
>>> File::exists(storage_path("content/pages/about-us.md"));

# Test hierarchical relationship
>>> $child = app(PageService::class)->createPage([
...   'title' => 'Our Team',
...   'parent_id' => $page->id
... ], '# Team Content');
>>> $child->parent->title; // Should be "About Us"

# Quality check
composer quality
```
