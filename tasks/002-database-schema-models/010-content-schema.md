---
task_id: 010
epic: 002-database-schema-models
title: Content Schema (Posts, Pages, Revisions)
status: pending
priority: critical
estimated_effort: 8 hours
actual_effort: null
assignee: database-architect
dependencies: [009]
tags: [database, content, hybrid-storage, week-3]
---

# Task 010: Content Schema (Posts, Pages, Revisions)

## 📋 Overview

Implement hybrid storage schema for posts and pages (SQLite metadata + flat-file Markdown content). Includes revision tracking, scheduling, and lock versioning for concurrent editing protection.

## 🎯 Acceptance Criteria

- [ ] Posts migration with hybrid storage metadata
- [ ] Pages migration with similar structure
- [ ] Revisions table for version history
- [ ] Post and Page models with flat-file integration
- [ ] UUID generation for portability
- [ ] lock_version column for optimistic locking
- [ ] Scheduled publishing (published_at, unpublished_at)
- [ ] Soft deletes enabled
- [ ] Factories for realistic content generation
- [ ] Content synchronization service (SQLite ↔ Flat-File)

## 🏗️ Implementation Steps

### Step 1: Create Posts Migration

**File**: `database/migrations/2025_01_01_000010_create_posts_table.php`

**Key Fields**:
- id, uuid (unique, indexed)
- title (string, 255)
- slug (string, unique, indexed)
- excerpt (text nullable)
- author_id (foreign key to users)
- category_id (foreign key nullable)
- status (enum: draft, published, scheduled)
- featured (boolean, default false)
- pinned (boolean, default false)
- template (string, default 'default')
- lock_version (integer, default 0)
- published_at (timestamp nullable)
- unpublished_at (timestamp nullable)
- reading_time (integer, calculated)
- seo_title, seo_description (string nullable)
- featured_image (string nullable)
- content_file_path (string) - Points to Markdown file
- timestamps, soft deletes

**Indexes**: slug, status, published_at, author_id

### Step 2: Create Pages Migration

**File**: `database/migrations/2025_01_01_000011_create_pages_table.php`

**Similar to posts**, but without categories, tags, pinned, featured

### Step 3: Create Revisions Migration

**File**: `database/migrations/2025_01_01_000012_create_revisions_table.php`

**Key Fields**:
- id
- revisionable_type (morphs)
- revisionable_id
- user_id (who made the revision)
- revision_number (integer)
- content_snapshot (text) - Full Markdown content
- metadata_snapshot (json) - Title, slug, etc.
- created_at

**Indexes**: revisionable type/id, created_at

### Step 4: Create Post and Page Models

**Files**:
- `app/Models/Post.php`
- `app/Models/Page.php`

**Features**:
- Eloquent model with flat-file trait
- Relationships: belongsTo(User, Category), belongsToMany(Tag), morphMany(Revision)
- Scopes: published, draft, scheduled, pinned, featured
- Accessors: reading_time, excerpt (auto-generated from content)
- Mutators: slug (auto-generate from title)
- Content loading from flat-file

### Step 5: Create Content Synchronization Service

**File**: `app/Services/Content/ContentSyncService.php`

**Methods**:
- saveToFlatFile(Post|Page $model, string $content)
- loadFromFlatFile(Post|Page $model): string
- syncToDatabase(string $filePath): void
- syncToFile(int $modelId): void

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Models/PostTest.php`
  - Test relationships
  - Test scopes (published, draft)
  - Test slug generation
  - Test reading time calculation
  - Test lock version increment

- `tests/Unit/Services/ContentSyncServiceTest.php`
  - Test flat-file write
  - Test flat-file read
  - Test sync integrity

**Feature Tests**:
- `tests/Feature/Database/ContentSchemaTest.php`
  - Test posts/pages tables exist
  - Test foreign keys
  - Test soft deletes
  - Test unique constraints

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.2 (Content Management)
- **Architecture**: `docs/prd/04-ARCHITECTURE.md` Section 3 (Hybrid Storage)

**Architecture:**
- **Pattern**: Hybrid Storage (SQLite + Flat-File)
- **Storage**: `storage/content/posts/YYYY/slug.md`
- **Events**: PostCreated, PostUpdated, PostDeleted

**Quality Requirements:**
- **Security**: Sanitized slugs, SQL injection prevention
- **Performance**: Indexed queries < 100ms
- **Testing**: > 80% coverage

**Related Tasks:**
- **Next**: 011-taxonomy-schema
- **Blocks**: 015-post-crud (Epic 003)
- **Depends On**: 009-users-roles-schema

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing
- [ ] Unit tests passing (12+ test cases)
- [ ] Hybrid storage sync tested
- [ ] Migration rollback works
- [ ] Factories generate valid posts

### Security
- [ ] Slug sanitization
- [ ] SQL injection prevention (Eloquent ORM)
- [ ] Soft deletes working

### Documentation
- [ ] Hybrid storage approach documented
- [ ] Sync service logic explained
- [ ] Model relationships documented

## ✅ Verification Steps

```bash
# Run migrations
php artisan migrate:fresh

# Test hybrid storage
php artisan tinker
>>> $post = Post::factory()->create();
>>> $post->saveToFlatFile("# My content");
>>> $content = $post->loadFromFlatFile();

# Quality check
composer quality
```
