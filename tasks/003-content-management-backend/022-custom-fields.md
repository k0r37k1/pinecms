---
task_id: 022
epic: 003-content-management-backend
title: Custom Fields System
status: pending
priority: medium
estimated_effort: 7 hours
actual_effort: null
assignee: fullstack-developer
dependencies: [015, 016]
tags: [backend, frontend, custom-fields, week-5]
---

# Task 022: Custom Fields System

## 📋 Overview

Build flexible custom fields system for posts and pages supporting text, number, boolean, date/time, and select field types. Stored as JSON in metadata column with validation and UI builder.

## 🎯 Acceptance Criteria

- [ ] CustomFieldService for management
- [ ] Support 5 field types (text, number, boolean, date, select)
- [ ] JSON storage in metadata column
- [ ] Field validation (required, min/max, regex)
- [ ] Custom field builder UI (drag & drop)
- [ ] Render fields in post/page editor
- [ ] Export custom fields with content
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create CustomFieldService

**File**: `app/Services/Content/CustomFieldService.php`

**Methods**:

- createField(string $key, string $type, array $config): array
- updateField(string $contentType, string $key, array $config): bool
- deleteField(string $contentType, string $key): bool
- getFields(string $contentType): array
- validateFieldValue(array $field, mixed $value): bool

**Field Types**:

- `text` - Single line text input
- `textarea` - Multi-line text
- `number` - Integer or decimal
- `boolean` - Checkbox (true/false)
- `date` - Date picker
- `datetime` - Date + time picker
- `select` - Dropdown with options

### Step 2: Field Configuration Schema

**Storage**: `settings` table → `custom_fields_post` / `custom_fields_page`

**Format**:

```json
{
    "author_bio": {
        "type": "textarea",
        "label": "Author Bio",
        "required": false,
        "default": "",
        "validation": {
            "maxLength": 500
        }
    },
    "featured_score": {
        "type": "number",
        "label": "Featured Score",
        "required": false,
        "default": 0,
        "validation": {
            "min": 0,
            "max": 100
        }
    },
    "publication_date": {
        "type": "date",
        "label": "Original Publication Date",
        "required": false
    }
}
```

### Step 3: Field Value Storage

**Model**: `Post` / `Page` → `metadata` column (JSON)

**Format**:

```json
{
    "author_bio": "John is a software engineer...",
    "featured_score": 85,
    "publication_date": "2025-11-09"
}
```

**Access**:

```php
$post->metadata['author_bio'] ?? '';
```

### Step 4: Validation

**Implementation**:

```php
public function validateFieldValue(array $field, mixed $value): bool
{
    match ($field['type']) {
        'text' => is_string($value) && strlen($value) <= ($field['validation']['maxLength'] ?? 255),
        'number' => is_numeric($value) && $value >= ($field['validation']['min'] ?? PHP_INT_MIN),
        'boolean' => is_bool($value),
        'date' => $this->isValidDate($value),
        'select' => in_array($value, $field['options'] ?? []),
        default => true,
    };
}
```

### Step 5: Custom Field Builder UI

**Page**: `resources/js/Pages/Admin/Settings/CustomFields.vue`

**Features**:

- Add new field (modal)
- Configure field type and validation
- Drag & drop to reorder fields
- Delete field (with confirmation)
- Preview field rendering

**Component**: `resources/js/Components/CustomFieldBuilder.vue`

**Field Configuration Modal**:

- Field key (slug format)
- Field label (display name)
- Field type (dropdown)
- Required (checkbox)
- Default value (input)
- Validation rules (conditional based on type)

### Step 6: Render Fields in Editor

**Component**: `resources/js/Components/CustomFieldRenderer.vue`

**Props**:

- fields (array of field configs)
- values (object with current values)
- errors (validation errors)

**Output**: Renders appropriate input component for each field type

## 🧪 Testing Requirements

**Unit Tests**:

- `tests/Unit/Services/CustomFieldServiceTest.php`
    - Test createField with all types
    - Test validateFieldValue for each type
    - Test validation rules (required, min/max, regex)
    - Test getFields retrieves config

**Feature Tests**:

- `tests/Feature/Content/CustomFieldsTest.php`
    - Test add custom field to post type
    - Test save post with custom field values
    - Test validation errors on invalid values
    - Test delete custom field
    - Test custom fields exported with content

**Frontend Tests**:

- `tests/vitest/components/CustomFieldBuilder.spec.ts`
    - Test field creation modal
    - Test drag & drop reordering
    - Test field deletion
- `tests/vitest/components/CustomFieldRenderer.spec.ts`
    - Test renders correct input type
    - Test validation error display

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.8 (Custom Fields)
- **Timeline**: Week 5 (v1.0.0)

**Architecture:**

- **Storage**: JSON in metadata column
- **Validation**: Service layer + Form Requests
- **UI**: Vue components with PrimeVue inputs

**Quality Requirements:**

- **Flexibility**: Support 5+ field types
- **Performance**: Field validation < 50ms
- **Testing**: > 80% coverage

**Related Tasks:**

- **Previous**: 015-post-crud-service, 016-page-crud-service
- **Next**: 023-bulk-actions
- **Depends On**: 010-content-schema

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] ESLint passes on TypeScript

### Testing

- [ ] Unit tests passing (12+ test cases)
- [ ] Feature tests passing (5+ scenarios)
- [ ] Frontend tests passing (5+ scenarios)

### UX

- [ ] Field builder intuitive
- [ ] Drag & drop works smoothly
- [ ] Validation errors clear

### Documentation

- [ ] Field types documented
- [ ] Validation rules documented
- [ ] Usage examples provided

## ✅ Verification Steps

```bash
# Test custom field creation
php artisan tinker
>>> $service = app(CustomFieldService::class);
>>> $service->createField('author_bio', 'textarea', [
...   'label' => 'Author Bio',
...   'required' => false,
...   'validation' => ['maxLength' => 500]
... ]);

# Test field value validation
>>> $service->validateFieldValue([
...   'type' => 'number',
...   'validation' => ['min' => 0, 'max' => 100]
... ], 85);
=> true

# Quality check
composer quality
npm run quality
```
