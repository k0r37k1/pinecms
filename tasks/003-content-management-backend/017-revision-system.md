---
task_id: 017
epic: 003-content-management-backend
title: Revision System Implementation
status: pending
priority: high
estimated_effort: 7 hours
actual_effort: null
assignee: backend-architect
dependencies: [015, 016]
tags: [backend, version-control, service-layer, week-4]
---

# Task 017: Revision System Implementation

## 📋 Overview

Build comprehensive revision system for posts and pages that automatically creates snapshots on every save. Supports restore, comparison, and cleanup of old revisions. Maximum 10 revisions per content item.

## 🎯 Acceptance Criteria

- [ ] Revision model with polymorphic relationship
- [ ] Automatic revision creation on save
- [ ] Restore to previous revision
- [ ] Compare revisions (diff highlighting)
- [ ] List all revisions for content
- [ ] Delete old revisions (keep max 10)
- [ ] Revision metadata (author, timestamp, changes summary)
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create RevisionService

**File**: `app/Services/Content/RevisionService.php`

**Methods**:
- createRevision(Model $content, string $changesSummary): Revision
- restoreRevision(Revision $revision): Model
- compareRevisions(Revision $from, Revision $to): array
- getRevisions(Model $content): Collection
- pruneRevisions(Model $content, int $keep = 10): int

**Business Logic**:
- Store complete content snapshot as JSON
- Track fields changed (title, content, status, etc.)
- Generate changes summary automatically
- Maintain maximum revision count
- Dispatch RevisionCreated event

### Step 2: Implement Polymorphic Relationship

**Files**:
- `app/Models/Revision.php`

**Relationships**:
```php
public function revisionable(): MorphTo
{
    return $this->morphTo();
}
```

**Columns**:
- revisionable_type, revisionable_id
- snapshot (JSON - complete content state)
- changes_summary (string - "Updated title and content")
- created_by (user who made change)
- created_at

### Step 3: Auto-Create Revisions

**Integration Points**:
- PostService::updatePost() → createRevision()
- PageService::updatePage() → createRevision()
- Trigger on status change (draft → published)
- Trigger on content change
- Skip revision if no meaningful changes detected

### Step 4: Restore Functionality

**Restore Logic**:
- Load revision snapshot
- Apply to current content
- Create new revision (marking as "Restored from revision #X")
- Update lock_version
- Dispatch ContentRestored event

### Step 5: Diff Comparison

**File**: `app/Services/Content/DiffService.php`

**Methods**:
- diff(string $oldContent, string $newContent): array
- highlightChanges(array $diff): string (HTML output)

**Implementation**:
- Use sebastian/diff package
- Generate unified diff format
- Highlight additions (green) and deletions (red)
- Support markdown and HTML output

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Services/RevisionServiceTest.php`
  - Test createRevision with snapshot
  - Test restoreRevision updates content
  - Test pruneRevisions keeps max 10
  - Test compareRevisions generates diff
  - Test getRevisions returns sorted list

**Feature Tests**:
- `tests/Feature/Content/RevisionTest.php`
  - Test revision created on post update
  - Test restore to previous version
  - Test revision list pagination
  - Test automatic pruning
  - Test changes summary generation

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.3 (Revision System)
- **Timeline**: Week 4 (v1.0.0)

**Architecture:**
- **Pattern**: Polymorphic Relationships
- **Storage**: SQLite (revision metadata + JSON snapshot)
- **Events**: RevisionCreated, ContentRestored

**Quality Requirements:**
- **Performance**: Revision creation < 50ms
- **Storage**: Prune old revisions automatically
- **Testing**: > 80% coverage

**Related Tasks:**
- **Previous**: 015-post-crud-service, 016-page-crud-service
- **Next**: 018-content-scheduler
- **Depends On**: 010-content-schema

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing
- [ ] Unit tests passing (12+ test cases)
- [ ] Feature tests passing (5+ scenarios)
- [ ] Edge cases covered (max revisions, restore conflicts)

### Performance
- [ ] Revision creation < 50ms
- [ ] Pruning efficient (batch deletes)
- [ ] Snapshot JSON indexed for search

### Documentation
- [ ] Service methods documented
- [ ] Revision workflow documented
- [ ] Restore process explained

## ✅ Verification Steps

```bash
# Test revision creation
php artisan tinker
>>> $post = Post::first();
>>> $revisionService = app(RevisionService::class);
>>> $revision = $revisionService->createRevision($post, 'Initial version');
>>> $revision->snapshot; // Should contain complete post data

# Test restore
>>> $revisionService->restoreRevision($revision);
>>> $post->fresh()->title; // Should match revision snapshot

# Test pruning
>>> $post->revisions()->count(); // Should be <= 10

# Quality check
composer quality
```
