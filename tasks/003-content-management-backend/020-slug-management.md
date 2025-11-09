---
task_id: 020
epic: 003-content-management-backend
title: Slug Management Service
status: pending
priority: medium
estimated_effort: 4 hours
actual_effort: null
assignee: backend-architect
dependencies: [015, 016]
tags: [backend, service-layer, validation, week-4]
---

# Task 020: Slug Management Service

## 📋 Overview

Build comprehensive slug management system with auto-generation from titles, custom slug support, uniqueness validation, and conflict resolution. Handles URL-safe transformation and duplicate detection.

## 🎯 Acceptance Criteria

- [ ] SlugService with generation and validation
- [ ] Auto-generate from title (Str::slug)
- [ ] Custom slug support (manual override)
- [ ] Uniqueness validation (append -2, -3 for duplicates)
- [ ] URL-safe transformation (lowercase, hyphens, no special chars)
- [ ] Historical slug tracking (redirects)
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create SlugService

**File**: `app/Services/Content/SlugService.php`

**Methods**:

- generate(string $title, ?string $customSlug = null): string
- validate(string $slug, string $contentType, ?int $excludeId = null): bool
- makeUnique(string $slug, string $contentType, ?int $excludeId = null): string
- sanitize(string $slug): string
- trackChange(Model $content, string $oldSlug, string $newSlug): void

**Business Logic**:

- Generate from title: `Str::slug($title)`
- Sanitize: lowercase, replace spaces with hyphens, remove special chars
- Check uniqueness: query database for existing slug
- If duplicate: append -2, -3, -4 until unique
- Track old slug for 301 redirects

### Step 2: Slug Validation Rules

**Validation**:

- Minimum length: 3 characters
- Maximum length: 200 characters
- Allowed: lowercase letters, numbers, hyphens
- Not allowed: spaces, underscores, special chars
- Not allowed: reserved words (admin, api, login, etc.)

**Reserved Slugs**:

```php
private const RESERVED = [
    'admin', 'api', 'login', 'logout', 'register',
    'dashboard', 'settings', 'media', 'search'
];
```

### Step 3: Uniqueness Checking

**Implementation**:

```php
public function makeUnique(string $slug, string $contentType, ?int $excludeId = null): string
{
    $baseSlug = $slug;
    $counter = 2;

    while (!$this->validate($slug, $contentType, $excludeId)) {
        $slug = "{$baseSlug}-{$counter}";
        $counter++;
    }

    return $slug;
}
```

### Step 4: Historical Slug Tracking

**Model**: `app/Models/SlugHistory.php`

**Columns**:

- sluggable_type, sluggable_id (polymorphic)
- old_slug, new_slug
- created_at

**Purpose**: Create 301 redirects for old URLs

### Step 5: Integration with PostService/PageService

**PostService::createPost()**:

```php
$slug = $this->slugService->generate(
    $data['title'],
    $data['slug'] ?? null
);
$data['slug'] = $this->slugService->makeUnique($slug, 'post');
```

**PostService::updatePost()**:

```php
if ($data['slug'] !== $post->slug) {
    $this->slugService->trackChange($post, $post->slug, $data['slug']);
}
```

## 🧪 Testing Requirements

**Unit Tests**:

- `tests/Unit/Services/SlugServiceTest.php`
    - Test generate from title
    - Test sanitize removes special chars
    - Test makeUnique appends counter
    - Test validate rejects reserved words
    - Test custom slug override
    - Test trackChange creates history

**Feature Tests**:

- `tests/Feature/Content/SlugTest.php`
    - Test slug auto-generated on post create
    - Test duplicate slug gets -2 appended
    - Test custom slug accepted
    - Test slug change creates redirect
    - Test reserved words rejected

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.6 (Slug Management)
- **Timeline**: Week 4 (v1.0.0)

**Architecture:**

- **Pattern**: Service Layer
- **Storage**: SQLite (slug_history table)
- **Validation**: Laravel Form Requests

**Quality Requirements:**

- **Performance**: Slug generation < 50ms
- **Uniqueness**: 100% conflict resolution
- **Testing**: > 80% coverage

**Related Tasks:**

- **Previous**: 015-post-crud-service, 016-page-crud-service
- **Next**: 021-concurrent-editing
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
- [ ] Edge cases covered (reserved words, max counter)

### Security

- [ ] Slug sanitization prevents injection
- [ ] Reserved words blocked
- [ ] URL-safe characters only

### Documentation

- [ ] Service methods documented
- [ ] Reserved words list maintained
- [ ] Slug format explained

## ✅ Verification Steps

```bash
# Test slug generation
php artisan tinker
>>> $slugService = app(SlugService::class);
>>> $slugService->generate('My Awesome Post');
=> "my-awesome-post"

# Test uniqueness
>>> $slugService->makeUnique('my-awesome-post', 'post');
=> "my-awesome-post-2"

# Test sanitization
>>> $slugService->sanitize('Hello World! @#$%');
=> "hello-world"

# Quality check
composer quality
```
