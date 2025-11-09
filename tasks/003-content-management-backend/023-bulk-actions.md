---
task_id: 023
epic: 003-content-management-backend
title: Bulk Actions Service
status: pending
priority: medium
estimated_effort: 5 hours
actual_effort: null
assignee: backend-architect
dependencies: [015, 016]
tags: [backend, bulk-operations, week-5]
---

# Task 023: Bulk Actions Service

## 📋 Overview

Implement bulk operations for posts and pages including delete, status change, category assignment, and tag assignment. Supports selection of multiple items with transaction-wrapped execution and rollback on failure.

## 🎯 Acceptance Criteria

- [ ] BulkActionService for batch operations
- [ ] Support bulk delete (soft delete)
- [ ] Support bulk status change (draft/published/archived)
- [ ] Support bulk category assignment
- [ ] Support bulk tag assignment
- [ ] Transaction-wrapped execution
- [ ] Progress tracking for large batches
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create BulkActionService

**File**: `app/Services/Content/BulkActionService.php`

**Methods**:
- bulkDelete(array $ids, string $contentType): int
- bulkUpdateStatus(array $ids, string $status, string $contentType): int
- bulkAssignCategory(array $ids, int $categoryId, string $contentType): int
- bulkAssignTags(array $ids, array $tagIds, string $contentType): int
- bulkDuplicate(array $ids, string $contentType): int

**Business Logic**:
- Validate IDs exist and user has permission
- Execute in database transaction
- Rollback on any failure
- Return count of affected rows
- Dispatch BulkActionCompleted event

### Step 2: Bulk Delete

**Implementation**:
```php
public function bulkDelete(array $ids, string $contentType): int
{
    return DB::transaction(function () use ($ids, $contentType) {
        $model = $this->getModel($contentType);

        $items = $model::whereIn('id', $ids)->get();

        foreach ($items as $item) {
            Gate::authorize('delete', $item);
        }

        $count = $model::whereIn('id', $ids)->delete();

        event(new BulkActionCompleted('delete', $contentType, $count));

        return $count;
    });
}
```

### Step 3: Bulk Status Change

**Implementation**:
```php
public function bulkUpdateStatus(array $ids, string $status, string $contentType): int
{
    return DB::transaction(function () use ($ids, $status, $contentType) {
        $model = $this->getModel($contentType);

        $items = $model::whereIn('id', $ids)->get();

        foreach ($items as $item) {
            Gate::authorize('update', $item);
        }

        $count = $model::whereIn('id', $ids)->update([
            'status' => $status,
            'updated_at' => now(),
        ]);

        return $count;
    });
}
```

### Step 4: Bulk Category/Tag Assignment

**Implementation**:
```php
public function bulkAssignCategory(array $ids, int $categoryId, string $contentType): int
{
    return DB::transaction(function () use ($ids, $categoryId, $contentType) {
        $model = $this->getModel($contentType);

        $count = $model::whereIn('id', $ids)->update([
            'category_id' => $categoryId,
            'updated_at' => now(),
        ]);

        return $count;
    });
}
```

### Step 5: Create API Endpoint

**Route**: `POST /api/admin/content/bulk`

**Controller**: `app/Http/Controllers/Admin/BulkActionController.php`

**Request**:
```json
{
  "action": "delete|update_status|assign_category|assign_tags",
  "content_type": "post|page",
  "ids": [1, 2, 3, 4, 5],
  "params": {
    "status": "published",
    "category_id": 10,
    "tag_ids": [5, 6, 7]
  }
}
```

**Response**:
```json
{
  "success": true,
  "count": 5,
  "message": "5 posts deleted successfully"
}
```

### Step 6: Frontend Bulk Selection

**Component**: `resources/js/Components/BulkActionToolbar.vue`

**Features**:
- Select all checkbox
- Individual checkboxes
- Bulk action dropdown
- Execute button
- Confirmation modal for destructive actions

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Services/BulkActionServiceTest.php`
  - Test bulkDelete soft deletes items
  - Test bulkUpdateStatus changes status
  - Test bulkAssignCategory assigns category
  - Test bulkAssignTags assigns tags
  - Test transaction rollback on error
  - Test authorization checked for each item

**Feature Tests**:
- `tests/Feature/Content/BulkActionsTest.php`
  - Test bulk delete via API
  - Test bulk status change
  - Test bulk category assignment
  - Test unauthorized user rejected
  - Test partial selection works

**Frontend Tests**:
- `tests/vitest/components/BulkActionToolbar.spec.ts`
  - Test select all functionality
  - Test action dropdown
  - Test confirmation modal

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.9 (Bulk Actions)
- **Timeline**: Week 5 (v1.0.0)

**Architecture:**
- **Pattern**: Service Layer + Transactions
- **Authorization**: Gate checks per item
- **Events**: BulkActionCompleted

**Quality Requirements:**
- **Performance**: < 1 second for 100 items
- **Safety**: Transaction rollback on failure
- **Testing**: > 80% coverage

**Related Tasks:**
- **Previous**: 015-post-crud-service, 016-page-crud-service
- **Next**: 024-content-search
- **Depends On**: 010-content-schema

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing
- [ ] Unit tests passing (10+ test cases)
- [ ] Feature tests passing (5+ scenarios)
- [ ] Frontend tests passing (3+ scenarios)

### Security
- [ ] Authorization checked per item
- [ ] Transaction rollback prevents partial updates
- [ ] Input validation on IDs

### Documentation
- [ ] Service methods documented
- [ ] API endpoint documented
- [ ] Supported actions documented

## ✅ Verification Steps

```bash
# Test bulk delete
php artisan tinker
>>> $service = app(BulkActionService::class);
>>> $count = $service->bulkDelete([1, 2, 3], 'post');
>>> $count; // Should be 3

# Test bulk status change
>>> $service->bulkUpdateStatus([4, 5, 6], 'published', 'post');

# Test via API
curl -X POST http://localhost:8000/api/admin/content/bulk \
  -H "Content-Type: application/json" \
  -d '{"action":"delete","content_type":"post","ids":[1,2,3]}'

# Quality check
composer quality
npm run quality
```
