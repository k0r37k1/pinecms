---
task_id: 019
epic: 003-content-management-backend
title: Auto-Save Implementation
status: pending
priority: high
estimated_effort: 5 hours
actual_effort: null
assignee: fullstack-developer
dependencies: [015, 016]
tags: [backend, frontend, auto-save, week-4]
---

# Task 019: Auto-Save Implementation

## 📋 Overview

Implement automatic draft saving every 30/60/120 seconds (configurable) to prevent content loss. Backend service handles save logic while frontend manages debouncing and UI indicators.

## 🎯 Acceptance Criteria

- [ ] AutoSaveService for backend logic
- [ ] Configurable interval (30s/60s/120s)
- [ ] Debounced saving on frontend
- [ ] Visual indicator (saving/saved/error)
- [ ] Save draft without validation
- [ ] Restore unsaved changes on reload
- [ ] Disable during manual save
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create AutoSaveService

**File**: `app/Services/Content/AutoSaveService.php`

**Methods**:

- saveDraft(Model $content, array $data, string $content): bool
- getLastAutoSave(int $contentId, string $type): ?array
- clearAutoSave(Model $content): bool

**Business Logic**:

- Save without validation (draft state)
- Update only content and metadata
- Don't increment lock_version
- Don't dispatch events
- Store timestamp of last auto-save

### Step 2: Create API Endpoint

**Route**: `POST /api/admin/content/auto-save`

**Controller**: `app/Http/Controllers/Admin/AutoSaveController.php`

**Request**:

```json
{
    "type": "post",
    "id": 123,
    "title": "Draft Title",
    "content": "# Draft Content",
    "metadata": {}
}
```

**Response**:

```json
{
    "success": true,
    "timestamp": "2025-11-09 10:30:00",
    "message": "Draft saved"
}
```

### Step 3: Frontend Auto-Save Composable

**File**: `resources/js/composables/useAutoSave.ts`

**Implementation**:

```typescript
export function useAutoSave(contentId, contentType) {
    const { interval } = useSettings(); // 30s, 60s, 120s
    const isSaving = ref(false);
    const lastSaved = ref(null);
    const error = ref(null);

    const debouncedSave = useDebounceFn(async (data) => {
        isSaving.value = true;
        try {
            await axios.post('/api/admin/content/auto-save', data);
            lastSaved.value = new Date();
            error.value = null;
        } catch (e) {
            error.value = e.message;
        } finally {
            isSaving.value = false;
        }
    }, interval * 1000);

    watch([title, content], () => {
        if (!isManualSaving.value) {
            debouncedSave({ contentId, contentType, title, content });
        }
    });

    return { isSaving, lastSaved, error };
}
```

### Step 4: Visual Indicators

**Component**: `resources/js/Components/AutoSaveIndicator.vue`

**States**:

- Saving... (spinner icon)
- Saved at HH:MM:SS (checkmark icon)
- Error saving (warning icon)
- Auto-save disabled (no icon)

**Position**: Top-right corner of editor

### Step 5: Settings Integration

**File**: `config/pinecms.php`

**Config**:

```php
'auto_save' => [
    'enabled' => env('AUTO_SAVE_ENABLED', true),
    'interval' => env('AUTO_SAVE_INTERVAL', 60), // seconds
],
```

## 🧪 Testing Requirements

**Unit Tests**:

- `tests/Unit/Services/AutoSaveServiceTest.php`
    - Test saveDraft without validation
    - Test getLastAutoSave retrieval
    - Test clearAutoSave
    - Test auto-save doesn't increment lock_version

**Feature Tests**:

- `tests/Feature/Content/AutoSaveTest.php`
    - Test API endpoint saves draft
    - Test auto-save persists data
    - Test retrieve last auto-save on reload
    - Test error handling

**Frontend Tests**:

- `tests/vitest/composables/useAutoSave.spec.ts`
    - Test debounced saving
    - Test interval configuration
    - Test disable during manual save
    - Test error handling

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.5 (Auto-Save)
- **Timeline**: Week 4 (v1.0.0)

**Architecture:**

- **Pattern**: Service Layer + Composable
- **API**: POST /api/admin/content/auto-save
- **Storage**: SQLite (draft state)

**Quality Requirements:**

- **Performance**: Save operation < 100ms
- **UX**: Debounced (no save spam)
- **Testing**: > 80% coverage

**Related Tasks:**

- **Previous**: 015-post-crud-service, 016-page-crud-service
- **Next**: 020-slug-management
- **Depends On**: 010-content-schema

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] ESLint passes on TypeScript

### Testing

- [ ] Unit tests passing (8+ test cases)
- [ ] Feature tests passing (4+ scenarios)
- [ ] Frontend tests passing (4+ scenarios)

### UX

- [ ] Visual indicator works correctly
- [ ] Debouncing prevents spam
- [ ] Error messages clear
- [ ] Settings configurable

### Documentation

- [ ] Service methods documented
- [ ] Composable usage documented
- [ ] API endpoint documented

## ✅ Verification Steps

```bash
# Test auto-save API
curl -X POST http://localhost:8000/api/admin/content/auto-save \
  -H "Content-Type: application/json" \
  -d '{"type":"post","id":1,"title":"Draft","content":"# Content"}'

# Test in browser
npm run dev
# Open post editor
# Type content
# Wait 60 seconds
# Check indicator shows "Saved at HH:MM:SS"

# Quality check
composer quality
npm run quality
```
