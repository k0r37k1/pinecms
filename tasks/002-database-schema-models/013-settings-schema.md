---
task_id: 013
epic: 002-database-schema-models
title: Settings & System Schema
status: pending
priority: medium
estimated_effort: 4 hours
actual_effort: null
assignee: database-architect
dependencies: [009]
tags: [database, settings, week-3]
---

# Task 013: Settings & System Schema

## 📋 Overview

Implement flexible key-value settings storage with caching, type casting, and encryption support. Includes sessions, jobs queue, and system audit logs tables.

## 🎯 Acceptance Criteria

- [ ] Settings table with key-value pairs
- [ ] Type casting support (string, int, bool, array, json)
- [ ] Encrypted settings support (CipherSweet)
- [ ] Settings cache for fast lookups
- [ ] Sessions table for database driver
- [ ] Jobs and failed_jobs tables for queue
- [ ] Activity log table for audit trail
- [ ] Setting model with accessor/mutator
- [ ] Factory and seeder for default settings

## 🏗️ Implementation Steps

### Step 1: Create Settings Migration

**File**: `database/migrations/2025_01_01_000040_create_settings_table.php`

**Key Fields**:
- id
- key (string, unique, indexed)
- value (text, encrypted if sensitive)
- type (enum: string, integer, boolean, array, json)
- encrypted (boolean, default false)
- group (string, for UI grouping: general, seo, email, etc.)
- description (text nullable)
- timestamps

**Indexes**: key, group

### Step 2: Create System Tables

**Files**:
- `database/migrations/2025_01_01_000041_create_sessions_table.php`
- `database/migrations/2025_01_01_000042_create_jobs_table.php`
- `database/migrations/2025_01_01_000043_create_failed_jobs_table.php`
- `database/migrations/2025_01_01_000044_create_activity_log_table.php`

**Activity Log Fields**:
- id, log_name, description
- subject_type, subject_id (polymorphic)
- causer_type, causer_id (who did it)
- properties (json)
- created_at

### Step 3: Create Setting Model

**File**: `app/Models/Setting.php`

**Features**:
- Custom accessor: getValue() with type casting
- Custom mutator: setValue() with type conversion
- Encryption support via CipherSweet
- Cache integration (60 min TTL)
- Static helper: Setting::get('key', $default)
- Static helper: Setting::set('key', $value)

### Step 4: Create Settings Service

**File**: `app/Services/Settings/SettingsService.php`

**Methods**:
- get(string $key, mixed $default): mixed
- set(string $key, mixed $value, string $type, bool $encrypted): void
- forget(string $key): void
- all(string $group = null): Collection
- refresh(): void (clear cache)

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Models/SettingTest.php`
  - Test type casting (string to int, bool, array)
  - Test encryption/decryption
  - Test cache behavior

- `tests/Unit/Services/SettingsServiceTest.php`
  - Test get/set methods
  - Test default values
  - Test cache invalidation

**Feature Tests**:
- `tests/Feature/Database/SettingsSchemaTest.php`
  - Test settings table structure
  - Test unique key constraint
  - Test system tables exist

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.8 (Settings)
- **Timeline**: Week 3 (v1.0.0)

**Architecture:**
- **Pattern**: Key-Value Store
- **Storage**: SQLite
- **Caching**: File-based cache (60 min)

**Quality Requirements:**
- **Security**: Encrypted sensitive settings
- **Performance**: Cached lookups < 1ms
- **Testing**: > 80% coverage

**Related Tasks:**
- **Next**: 014-factories-seeders
- **Blocks**: 068-settings-ui (Epic 009)
- **Depends On**: 009-users-roles-schema

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files

### Testing
- [ ] Unit tests passing (8+ test cases)
- [ ] Type casting tested thoroughly
- [ ] Cache behavior verified

### Security
- [ ] Encrypted settings working
- [ ] API keys never logged
- [ ] Sensitive data protected

### Documentation
- [ ] Type casting documented
- [ ] Encryption strategy explained
- [ ] Cache strategy noted

## ✅ Verification Steps

```bash
# Test settings service
php artisan tinker
>>> Setting::set('site_name', 'PineCMS', 'string', false);
>>> Setting::get('site_name');
>>> Setting::set('api_key', 'secret', 'string', true); // Encrypted

# Quality check
composer quality
```
