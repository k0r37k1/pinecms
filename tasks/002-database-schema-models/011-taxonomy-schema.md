---
task_id: 011
epic: 002-database-schema-models
title: Taxonomy Schema (Categories, Tags)
status: pending
priority: high
estimated_effort: 5 hours
actual_effort: null
assignee: database-architect
dependencies: [010]
tags: [database, taxonomy, week-3]
---

# Task 011: Taxonomy Schema (Categories, Tags)

## 📋 Overview

Implement hierarchical categories (parent-child, max 2 levels) and flat tag system for content organization. Includes pivot tables, slug uniqueness, and usage counts.

## 🎯 Acceptance Criteria

- [ ] Categories migration with hierarchical support (parent_id)
- [ ] Tags migration with flat structure
- [ ] category_post and post_tag pivot tables
- [ ] Category and Tag models with relationships
- [ ] Slug generation and uniqueness
- [ ] Usage count tracking (cached)
- [ ] Soft deletes enabled
- [ ] Factories generate realistic taxonomy data
- [ ] Seeder creates default categories

## 🏗️ Implementation Steps

### Step 1: Create Categories Migration

**File**: `database/migrations/2025_01_01_000020_create_categories_table.php`

**Key Fields**:
- id
- name (string, 100)
- slug (string, unique, indexed)
- description (text nullable, max 200 chars)
- parent_id (foreign key nullable to categories.id)
- color (string nullable, hex color for badge)
- order (integer, default 0)
- post_count (integer, default 0, cached)
- timestamps, soft deletes

**Indexes**: slug, parent_id, order

**Constraints**: Max depth 2 levels (validation in model)

### Step 2: Create Tags Migration

**File**: `database/migrations/2025_01_01_000021_create_tags_table.php`

**Key Fields**:
- id
- name (string, 50)
- slug (string, unique, indexed)
- post_count (integer, default 0, cached)
- timestamps, soft deletes

**Indexes**: slug, post_count (for popular tags query)

### Step 3: Create Pivot Tables

**Files**:
- `database/migrations/2025_01_01_000022_create_category_post_table.php`
- `database/migrations/2025_01_01_000023_create_post_tag_table.php`

**Pivot Structure**: foreign keys with cascading deletes, timestamps

### Step 4: Create Category and Tag Models

**Files**:
- `app/Models/Category.php`
- `app/Models/Tag.php`

**Category Relationships**:
- parent: belongsTo(Category, 'parent_id')
- children: hasMany(Category, 'parent_id')
- posts: hasMany(Post)

**Tag Relationships**:
- posts: belongsToMany(Post)

**Scopes**:
- Category: root (parent_id null), withChildren
- Tag: popular (post_count > threshold)

### Step 5: Implement Usage Count Tracking

**Service**: `app/Services/Taxonomy/TaxonomyCountService.php`

**Methods**:
- updateCategoryCount(Category $category): void
- updateTagCount(Tag $tag): void
- recalculateAllCounts(): void

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Models/CategoryTest.php`
  - Test parent-child relationship
  - Test max depth validation
  - Test slug generation
  - Test post count calculation

- `tests/Unit/Models/TagTest.php`
  - Test posts relationship
  - Test popular scope
  - Test slug generation

**Feature Tests**:
- `tests/Feature/Database/TaxonomySchemaTest.php`
  - Test categories table structure
  - Test hierarchical queries
  - Test pivot tables
  - Test cascading deletes

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.7 (Categories & Tags)
- **Timeline**: Week 3 (v1.0.0)

**Architecture:**
- **Pattern**: Repository Pattern
- **Storage**: SQLite relational
- **Events**: CategoryCreated, TagCreated

**Quality Requirements:**
- **Security**: Slug sanitization
- **Performance**: Indexed slugs, cached counts
- **Testing**: > 80% coverage

**Related Tasks:**
- **Next**: 012-media-schema
- **Blocks**: 062-category-management (Epic 008)
- **Depends On**: 010-content-schema

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files

### Testing
- [ ] Unit tests passing (10+ test cases)
- [ ] Hierarchical queries tested
- [ ] Migration rollback works

### Security
- [ ] Slug sanitization
- [ ] Max depth validation

### Documentation
- [ ] Hierarchical structure explained
- [ ] Usage count caching documented

## ✅ Verification Steps

```bash
# Test hierarchical categories
php artisan tinker
>>> $parent = Category::factory()->create();
>>> $child = Category::factory()->create(['parent_id' => $parent->id]);
>>> $parent->children; // Should include $child

# Quality check
composer quality
```
