---
task_id: 021
epic: 003-content-management-backend
title: Concurrent Editing Protection
status: pending
priority: medium
estimated_effort: 5 hours
actual_effort: null
assignee: backend-architect
dependencies: [015, 016]
tags: [backend, optimistic-locking, conflict-resolution, week-4]
---

# Task 021: Concurrent Editing Protection

## 📋 Overview

Implement optimistic locking to detect and prevent concurrent editing conflicts. Uses lock_version column to track changes and provide conflict resolution UI when multiple users edit the same content.

## 🎯 Acceptance Criteria

- [ ] Optimistic locking with lock_version column
- [ ] Conflict detection on save
- [ ] ConflictException with details
- [ ] Merge conflict resolution UI
- [ ] Show diff between versions
- [ ] Option to overwrite or merge changes
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Add lock_version Column

**Migration**: Already in 010-content-schema (posts, pages tables)

**Column**: `lock_version` (integer, default 0)

**Usage**:

- Increment on every update
- Compare before save
- Throw exception if mismatch

### Step 2: Create ConcurrentEditingService

**File**: `app/Services/Content/ConcurrentEditingService.php`

**Methods**:

- checkLock(Model $content, int $currentVersion): bool
- detectConflict(Model $content, int $expectedVersion): ?array
- resolveConflict(Model $content, array $userChanges, string $strategy): Model
- getLockInfo(Model $content): array

**Business Logic**:

- Compare lock_version with expected version
- If mismatch: fetch current state and user state
- Generate diff between versions
- Provide merge strategies: overwrite, merge, manual

### Step 3: Create ConflictException

**File**: `app/Exceptions/ConcurrentEditingException.php`

**Properties**:

- currentVersion (int)
- expectedVersion (int)
- currentContent (array)
- userContent (array)
- diff (array)

**Response**:

```json
{
  "error": "ConcurrentEditingException",
  "message": "Content was modified by another user",
  "data": {
    "current_version": 5,
    "expected_version": 4,
    "current_content": {...},
    "user_content": {...},
    "diff": [...]
  }
}
```

### Step 4: Integration with Services

**PostService::updatePost()**:

```php
if (!$this->concurrentEditingService->checkLock($post, $data['lock_version'])) {
    $conflict = $this->concurrentEditingService->detectConflict(
        $post,
        $data['lock_version']
    );
    throw new ConcurrentEditingException($conflict);
}

DB::transaction(function () use ($post, $data) {
    $post->lock_version++; // Increment lock version
    $post->update($data);
});
```

### Step 5: Frontend Conflict Resolution UI

**Component**: `resources/js/Components/ConflictResolutionModal.vue`

**Features**:

- Show diff between current and user version
- Highlight changes (additions, deletions)
- Three merge strategies:
    - **Overwrite**: Discard current, use user version
    - **Keep Current**: Discard user changes
    - **Merge Manually**: Side-by-side editor

**Props**:

- currentContent (object)
- userContent (object)
- diff (array)

### Step 6: Lock Info API

**Endpoint**: `GET /api/admin/content/{type}/{id}/lock-info`

**Response**:

```json
{
    "lock_version": 5,
    "last_modified_by": "John Doe",
    "last_modified_at": "2025-11-09 10:30:00",
    "is_locked": false
}
```

## 🧪 Testing Requirements

**Unit Tests**:

- `tests/Unit/Services/ConcurrentEditingServiceTest.php`
    - Test checkLock returns false on mismatch
    - Test detectConflict generates diff
    - Test resolveConflict with overwrite strategy
    - Test getLockInfo returns details

**Feature Tests**:

- `tests/Feature/Content/ConcurrentEditingTest.php`
    - Test conflict detected on simultaneous update
    - Test exception thrown with diff
    - Test merge strategies work correctly
    - Test lock_version incremented

**Frontend Tests**:

- `tests/vitest/components/ConflictResolutionModal.spec.ts`
    - Test modal displays diff
    - Test merge strategies
    - Test user can choose resolution

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.7 (Concurrent Editing)
- **Timeline**: Week 4 (v1.0.0)

**Architecture:**

- **Pattern**: Optimistic Locking
- **Strategy**: lock_version column
- **Conflict Resolution**: User-driven

**Quality Requirements:**

- **Accuracy**: 100% conflict detection
- **UX**: Clear diff visualization
- **Testing**: > 80% coverage

**Related Tasks:**

- **Previous**: 015-post-crud-service, 016-page-crud-service
- **Next**: 022-custom-fields
- **Depends On**: 010-content-schema

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing

- [ ] Unit tests passing (8+ test cases)
- [ ] Feature tests passing (4+ scenarios)
- [ ] Frontend tests passing (3+ scenarios)

### UX

- [ ] Conflict modal clear and intuitive
- [ ] Diff highlighting accurate
- [ ] Merge strategies documented

### Documentation

- [ ] Service methods documented
- [ ] Conflict resolution strategies explained
- [ ] API endpoints documented

## ✅ Verification Steps

```bash
# Test concurrent editing
php artisan tinker
>>> $post = Post::first();
>>> $version1 = $post->lock_version;

# Simulate another user's update
>>> $post->update(['title' => 'Updated by User 1']);
>>> $version2 = $post->lock_version; // Should be $version1 + 1

# Try updating with old version
>>> $service = app(PostService::class);
>>> $service->updatePost($post, ['lock_version' => $version1, 'title' => 'Updated by User 2'], null);
# Should throw ConcurrentEditingException

# Quality check
composer quality
npm run quality
```
