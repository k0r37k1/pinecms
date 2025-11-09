---
task_id: 015
epic: 003-content-management-backend
title: Post CRUD Service & Repository
status: pending
priority: critical
estimated_effort: 8 hours
actual_effort: null
assignee: backend-architect
dependencies: [010, 014]
tags: [backend, service-layer, hybrid-storage, week-4]
---

# Task 015: Post CRUD Service & Repository

## 📋 Overview

Implement complete CRUD service and repository for posts with hybrid storage (SQLite metadata + flat-file Markdown content). Includes business logic for slug generation, status management, and event dispatching.

## 🎯 Acceptance Criteria

- [ ] PostService with all CRUD methods
- [ ] PostRepository for data access
- [ ] Hybrid storage (SQLite + flat-file) working
- [ ] Transaction-wrapped saves (rollback on file write failure)
- [ ] Events dispatched (PostCreated, PostUpdated, PostDeleted)
- [ ] Slug auto-generation from title
- [ ] Reading time calculation (200 words/min)
- [ ] Excerpt auto-generation (first 160 chars)
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create PostRepository

**File**: `app/Repositories/PostRepository.php`

**Methods**:
- findById(int $id): ?Post
- findBySlug(string $slug): ?Post
- create(array $data, string $content): Post
- update(Post $post, array $data, ?string $content): bool
- delete(Post $post): bool
- paginate(int $perPage, array $filters): LengthAwarePaginator

**Hybrid Storage Logic**:
- Save metadata to SQLite (title, slug, status, timestamps)
- Save content to flat-file (`storage/content/posts/YYYY/slug.md`)
- Wrap in database transaction
- Rollback DB if file write fails

### Step 2: Create PostService

**File**: `app/Services/Content/PostService.php`

**Methods**:
- createPost(array $data, string $content): Post
- updatePost(Post $post, array $data, ?string $content): Post
- deletePost(Post $post): bool
- duplicatePost(Post $post): Post
- publishPost(Post $post): bool
- unpublishPost(Post $post): bool
- schedulePost(Post $post, Carbon $publishAt, ?Carbon $unpublishAt): bool

**Business Logic**:
- Auto-generate slug from title (Str::slug)
- Validate slug uniqueness (append -2, -3 if duplicate)
- Calculate reading time (word count / 200)
- Generate excerpt if not provided (first 160 chars)
- Increment lock_version on update
- Dispatch events

### Step 3: Create Flat-File Service

**File**: `app/Services/Content/FlatFileService.php`

**Methods**:
- write(string $path, string $content, array $frontMatter): bool
- read(string $path): array (returns ['content', 'frontMatter'])
- delete(string $path): bool
- exists(string $path): bool
- generatePath(Post $post): string

**Format** (YAML front matter + Markdown):
```markdown
---
id: 123
title: My Post
slug: my-post
author_id: 1
status: published
published_at: '2025-11-09 10:00:00'
---

# My Post Content

This is the **post body** in Markdown.
```

### Step 4: Implement Event Dispatching

**Files**:
- `app/Events/PostCreated.php`
- `app/Events/PostUpdated.php`
- `app/Events/PostDeleted.php`

**Event Properties**:
- public Post $post
- public User $user (who performed action)
- public array $changes (for Updated event)

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Services/PostServiceTest.php`
  - Test createPost with slug generation
  - Test updatePost with lock_version increment
  - Test slug uniqueness handling
  - Test reading time calculation
  - Test excerpt generation

- `tests/Unit/Repositories/PostRepositoryTest.php`
  - Test hybrid storage save
  - Test transaction rollback on file failure
  - Test findBySlug
  - Test pagination with filters

**Feature Tests**:
- `tests/Feature/Content/PostCrudTest.php`
  - Test complete create post flow
  - Test update post flow
  - Test delete post (soft delete)
  - Test duplicate post
  - Test publish/unpublish

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.2.2 (Posts CRUD)
- **Timeline**: Week 4 (v1.0.0)

**Architecture:**
- **Pattern**: Repository Pattern (`docs/prd/04-ARCHITECTURE.md` Section 9)
- **Storage**: Hybrid (SQLite + Flat-File)
- **Events**: PostCreated, PostUpdated, PostDeleted

**Quality Requirements:**
- **Security**: Input sanitization, authorization checks
- **Performance**: Save operation < 200ms
- **Testing**: > 80% coverage

**Related Tasks:**
- **Next**: 016-page-crud-service
- **Blocks**: 025-post-controllers
- **Depends On**: 010-content-schema, 014-factories-seeders

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing
- [ ] Unit tests passing (15+ test cases)
- [ ] Feature tests passing (5+ scenarios)
- [ ] Edge cases covered (duplicate slugs, file write failures)
- [ ] Transaction rollback tested

### Security
- [ ] Input sanitization (strip tags from title)
- [ ] Slug sanitization (no special chars)
- [ ] File path validation (no directory traversal)

### Documentation
- [ ] Service methods documented
- [ ] Hybrid storage flow explained
- [ ] Event payloads documented

## ✅ Verification Steps

```bash
# Test post creation
php artisan tinker
>>> $post = app(PostService::class)->createPost([
...   'title' => 'Test Post',
...   'author_id' => 1,
...   'status' => 'draft'
... ], '# Test Content');
>>> $post->id; // Should return post ID
>>> File::exists(storage_path("content/posts/2025/test-post.md")); // Should be true

# Verify event dispatched
>>> Event::fake();
>>> app(PostService::class)->createPost([...], '...');
>>> Event::assertDispatched(PostCreated::class);

# Quality check
composer quality
```
