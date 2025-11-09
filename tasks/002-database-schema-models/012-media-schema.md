---
task_id: 012
epic: 002-database-schema-models
title: Media Library Schema
status: pending
priority: high
estimated_effort: 6 hours
actual_effort: null
assignee: database-architect
dependencies: [009]
tags: [database, media, week-3]
---

# Task 012: Media Library Schema

## 📋 Overview

Design media library database schema for tracking uploaded files (images, documents, videos). Includes metadata, focal points, alt text, thumbnails, and usage tracking.

## 🎯 Acceptance Criteria

- [ ] Media table with comprehensive metadata
- [ ] Thumbnail variants tracking
- [ ] Focal point coordinates storage
- [ ] File hash for duplicate detection
- [ ] Usage tracking (attached to posts/pages)
- [ ] Soft deletes enabled
- [ ] UUID for portable identifiers
- [ ] Indexes on file type, uploaded date
- [ ] Factory generates media records

## 🏗️ Implementation Steps

### Step 1: Create Media Migration

**File**: `database/migrations/2025_01_01_000030_create_media_table.php`

**Key Fields**:
- id, uuid (unique, indexed)
- user_id (uploader, foreign key)
- filename (string, original filename)
- filepath (string, storage path)
- file_hash (string, unique, for duplicate detection)
- disk (string, storage disk: local, s3)
- mime_type (string, indexed)
- file_size (bigint, bytes)
- width, height (integer nullable, for images)
- alt_text (string, max 125 chars)
- title (string nullable, max 100 chars)
- caption (text nullable, max 500 chars)
- focal_point_x, focal_point_y (decimal nullable, 0-1 range)
- metadata (json, EXIF data before stripping)
- thumbnail_paths (json, array of generated thumbnail URLs)
- timestamps, soft deletes

**Indexes**: user_id, mime_type, created_at, file_hash

### Step 2: Create Media Model

**File**: `app/Models/Media.php`

**Relationships**:
- uploader: belongsTo(User, 'user_id')
- posts: belongsToMany(Post) via mediables pivot
- pages: belongsToMany(Page) via mediables pivot

**Scopes**:
- images: where mime_type like 'image/%'
- documents: where mime_type in [pdf, doc, docx]
- videos: where mime_type like 'video/%'
- recent: orderBy created_at desc

**Accessors**:
- human_file_size: Convert bytes to KB/MB/GB
- thumbnail_url: Get specific thumbnail size

### Step 3: Create Mediables Polymorphic Pivot

**File**: `database/migrations/2025_01_01_000031_create_mediables_table.php`

**Structure**: media_id, mediable_type, mediable_id (polymorphic)

### Step 4: Implement Media Traits

**File**: `app/Models/Traits/HasMedia.php`

**Methods for Post/Page models**:
- attachMedia(Media $media)
- detachMedia(Media $media)
- syncMedia(array $mediaIds)
- getAllMedia()
- getFeaturedImage()

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Models/MediaTest.php`
  - Test relationships
  - Test scopes (images, documents, videos)
  - Test accessors (human_file_size)
  - Test file hash uniqueness
  - Test focal point validation (0-1 range)

**Feature Tests**:
- `tests/Feature/Database/MediaSchemaTest.php`
  - Test media table structure
  - Test polymorphic relationships
  - Test soft deletes
  - Test unique file_hash constraint

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.3 (Media Library)
- **Timeline**: Week 3-4 (v1.0.0)

**Architecture:**
- **Pattern**: Polymorphic relationships
- **Storage**: File system for binaries, SQLite for metadata
- **Security**: EXIF stripping, file validation

**Quality Requirements:**
- **Security**: File hash validation, MIME type checking
- **Performance**: Indexed queries, lazy loading
- **Testing**: > 80% coverage

**Related Tasks:**
- **Next**: 013-settings-schema
- **Blocks**: 035-media-upload (Epic 005)
- **Depends On**: 009-users-roles-schema

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files

### Testing
- [ ] Unit tests passing (10+ test cases)
- [ ] Polymorphic relationships tested
- [ ] Migration rollback works

### Security
- [ ] File hash uniqueness enforced
- [ ] MIME type validation
- [ ] Focal point range validation

### Documentation
- [ ] Polymorphic usage explained
- [ ] Thumbnail generation strategy noted
- [ ] Storage paths documented

## ✅ Verification Steps

```bash
# Test media creation
php artisan tinker
>>> $media = Media::factory()->create();
>>> $post = Post::first();
>>> $post->attachMedia($media);
>>> $post->getAllMedia();

# Quality check
composer quality
```
