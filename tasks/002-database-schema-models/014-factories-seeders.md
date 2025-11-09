---
task_id: 014
epic: 002-database-schema-models
title: Model Factories & Seeders
status: pending
priority: high
estimated_effort: 6 hours
actual_effort: null
assignee: backend-architect
dependencies: [009, 010, 011, 012, 013]
tags: [database, factories, seeders, testing, week-3]
---

# Task 014: Model Factories & Seeders

## 📋 Overview

Create comprehensive Eloquent model factories and database seeders for testing, development, and demo environments. Must generate realistic fake data with proper relationships.

## 🎯 Acceptance Criteria

- [ ] Factories for all models (User, Post, Page, Category, Tag, Media, Setting)
- [ ] Factory states for common scenarios (published post, draft, admin user)
- [ ] Relationship factories (post with author, category, tags)
- [ ] Realistic fake data (titles, content, emails)
- [ ] Database seeder with complete demo site
- [ ] Configurable seeder (small/medium/large dataset)
- [ ] Seeder runs without errors
- [ ] Factory tests verify data validity

## 🏗️ Implementation Steps

### Step 1: Create User Factory

**File**: `database/factories/UserFactory.php`

**Features**:
- Random names, emails, avatars
- States: admin, author, user
- Verified and unverified states
- Realistic bio and social links

### Step 2: Create Content Factories

**Files**:
- `database/factories/PostFactory.php`
- `database/factories/PageFactory.php`

**Features**:
- Realistic titles (Faker sentence)
- Auto-generated slugs
- Lorem ipsum content (500-2000 words)
- States: published, draft, scheduled
- Random categories and tags (relationships)
- Featured images (random media)
- SEO meta fields

### Step 3: Create Taxonomy Factories

**Files**:
- `database/factories/CategoryFactory.php`
- `database/factories/TagFactory.php`

**Features**:
- Realistic category names (Technology, Travel, Food)
- Hierarchical categories (parent-child)
- Tag names from common topics
- Color codes for badges

### Step 4: Create Media Factory

**File**: `database/factories/MediaFactory.php`

**Features**:
- Fake image URLs (via placeholder service)
- Realistic filenames and MIME types
- File sizes, dimensions
- Alt text, captions
- Thumbnail variants

### Step 5: Create Database Seeder

**File**: `database/seeders/DatabaseSeeder.php`

**Seeding Order** (respects foreign keys):
1. Settings (site name, tagline, etc.)
2. Roles and Permissions
3. Users (1 admin, 3 authors, 5 users)
4. Categories (5 root, 10 children)
5. Tags (20 tags)
6. Media (50 images)
7. Posts (100 posts with relationships)
8. Pages (5 pages: About, Contact, Privacy, Terms, 404)

**Configurable via env**: `SEEDER_SIZE=small|medium|large`

### Step 6: Create Specific Seeders

**Files**:
- `database/seeders/RolesAndPermissionsSeeder.php`
- `database/seeders/SettingsSeeder.php`
- `database/seeders/CategorySeeder.php`

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Factories/UserFactoryTest.php`
  - Test user creation
  - Test admin state
  - Test email uniqueness

- `tests/Unit/Factories/PostFactoryTest.php`
  - Test post creation with relationships
  - Test published state
  - Test slug generation

**Feature Tests**:
- `tests/Feature/Database/SeedersTest.php`
  - Test DatabaseSeeder runs successfully
  - Test correct number of records created
  - Test relationships properly seeded
  - Test no foreign key violations

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: Testing infrastructure for all features
- **Timeline**: Week 3 (v1.0.0)

**Architecture:**
- **Pattern**: Factory Pattern (test data generation)
- **Purpose**: Testing, development, demo sites

**Quality Requirements:**
- **Testing**: All factories must produce valid models
- **Performance**: Seeding 100 posts < 10 seconds
- **Data Quality**: Realistic, not Lorem ipsum only

**Related Tasks:**
- **Next**: 015-post-crud (Epic 003)
- **Blocks**: All testing tasks
- **Depends On**: 009-013 (all schema tasks)

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files

### Testing
- [ ] Factory tests passing (15+ test cases)
- [ ] Seeder runs without errors
- [ ] No foreign key violations
- [ ] Generated data is valid

### Data Quality
- [ ] Realistic names, titles, content
- [ ] Proper relationships established
- [ ] Unique constraints respected
- [ ] Soft deletes not triggered

### Documentation
- [ ] Seeder usage documented
- [ ] Factory states explained
- [ ] Env configuration noted

## ✅ Verification Steps

```bash
# Run seeders
php artisan migrate:fresh --seed

# Verify counts
php artisan tinker
>>> User::count(); // Should be 9
>>> Post::count(); // Should be 100
>>> Category::count(); // Should be 15

# Test specific factory
>>> Post::factory()->published()->create();

# Quality check
composer quality
```
