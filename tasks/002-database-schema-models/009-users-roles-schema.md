---
task_id: 009
epic: 002-database-schema-models
title: Users & Roles Schema
status: pending
priority: critical
estimated_effort: 6 hours
actual_effort: null
assignee: database-architect
dependencies: [001]
tags: [database, users, rbac, week-3]
---

# Task 009: Users & Roles Schema

## 📋 Overview

Design and implement complete user authentication and role-based access control (RBAC) database schema. Includes users table, roles, permissions, and capability-based authorization system.

## 🎯 Acceptance Criteria

- [ ] Users migration created with all required fields
- [ ] Roles and permissions tables created
- [ ] Role-permission pivot table established
- [ ] User model with authentication traits
- [ ] Soft deletes enabled for users
- [ ] Field-level encryption for email (CipherSweet)
- [ ] Indexes on email, created_at columns
- [ ] Factory generates realistic user data
- [ ] Comprehensive unit tests for User model

## 🏗️ Implementation Steps

### Step 1: Create Users Migration

**File**: `database/migrations/2025_01_01_000001_create_users_table.php`

**Key Fields**:

- id (bigint primary key)
- uuid (string, unique, indexed)
- name (string)
- email (string, unique, indexed, encrypted)
- email_verified_at (timestamp nullable)
- password (string, hashed)
- remember_token (string nullable)
- avatar (string nullable)
- bio (text nullable)
- role_id (foreign key to roles)
- last_login_at (timestamp nullable)
- timestamps, soft deletes

### Step 2: Create Roles and Permissions Migration

**File**: `database/migrations/2025_01_01_000002_create_roles_and_permissions.php`

**Tables**:

- `roles` (id, name, slug, description)
- `permissions` (id, name, slug, description)
- `permission_role` (role_id, permission_id)

**Default Roles**: Administrator, Author, User, Guest

### Step 3: Create User Model

**File**: `app/Models/User.php`

**Features**:

- Laravel Authenticatable
- Soft deletes
- HasFactory trait
- Relationships: belongsTo(Role), hasMany(Post)
- Encrypted email attribute (CipherSweet)
- Accessors and mutators
- Scopes for filtering

### Step 4: Create Role and Permission Models

**Files**:

- `app/Models/Role.php`
- `app/Models/Permission.php`

**Relationships**:

- Role belongsToMany Permission
- Role hasMany User

## 🧪 Testing Requirements

**Unit Tests**:

- `tests/Unit/Models/UserTest.php`
    - Test user creation
    - Test relationships (role, posts)
    - Test email encryption/decryption
    - Test soft delete behavior
    - Test avatar accessor

**Feature Tests**:

- `tests/Feature/Database/UsersSchemaTest.php`
    - Test users table exists
    - Test foreign key constraints
    - Test unique constraints (email)
    - Test default values

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.4 (User Management)
- **Timeline**: Week 3 (v1.0.0)

**Architecture:**

- **Pattern**: Repository Pattern (`docs/prd/04-ARCHITECTURE.md`)
- **Security**: Field-level encryption for PII
- **Storage**: SQLite primary, MySQL optional

**Quality Requirements:**

- **Security**: Encrypted email, hashed passwords, RBAC
- **Performance**: Indexed email and timestamps
- **Testing**: > 80% model coverage

**Related Tasks:**

- **Next**: 010-content-schema
- **Blocks**: 045-user-crud (Epic 006)
- **Depends On**: 001-system-requirements

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all PHP files
- [ ] PHPDoc blocks with array shapes

### Testing

- [ ] Unit tests passing (8+ test cases)
- [ ] Migration rollback works
- [ ] Factory generates valid users
- [ ] Relationship tests passing

### Security

- [ ] Email field encrypted (CipherSweet)
- [ ] Passwords hashed (bcrypt)
- [ ] Foreign keys enforced
- [ ] Soft deletes working

### Documentation

- [ ] Migration comments added
- [ ] Model relationships documented
- [ ] PHPDoc for encrypted fields

## ✅ Verification Steps

```bash
# Run migrations
php artisan migrate:fresh

# Verify schema
php artisan tinker
>>> \DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='users'");

# Test factory
>>> User::factory()->count(10)->create();

# Quality check
composer quality
```
