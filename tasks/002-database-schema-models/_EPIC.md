---
epic_id: 002
title: Database Schema & Models
status: pending
priority: critical
estimated_duration: 1 week
actual_duration: null
week: Week 3
version: v1.0.0
tasks_total: 6
tasks_completed: 0
---

# Epic 002: Database Schema & Models

## 🎯 Epic Goals

- Design and implement complete SQLite database schema for hybrid storage
- Create all Eloquent models with relationships and scopes
- Establish data integrity with foreign keys and constraints
- Build factory and seeder infrastructure for testing and development
- Implement field-level encryption for sensitive data (CipherSweet)

## 📦 Features Delivered

- SQLite database schema (users, posts, pages, categories, tags, media, settings)
- Eloquent models with relationships (belongsTo, hasMany, belongsToMany)
- Database migrations with proper indexing
- Model factories for testing
- Database seeders for development data
- Field-level encryption for sensitive fields
- Soft deletes for content safety
- UUID support for portable identifiers

## 📋 Tasks (6 total)

- [ ] 009 - Users & Roles Schema (6h)
- [ ] 010 - Content Schema (Posts, Pages, Revisions) (8h)
- [ ] 011 - Taxonomy Schema (Categories, Tags) (5h)
- [ ] 012 - Media Library Schema (6h)
- [ ] 013 - Settings & System Schema (4h)
- [ ] 014 - Model Factories & Seeders (6h)

## 🔗 Dependencies

**Blocks:**
- Epic 003 (Content Management Backend)
- Epic 005 (Media Library System)
- Epic 006 (User Management & Auth)

**Requires:**
- Epic 001 (Installer & Foundation) - Database must be initialized

## ✅ Epic Success Criteria

- [ ] All database migrations run successfully on SQLite 3.x
- [ ] Foreign key constraints enforced properly
- [ ] All models have comprehensive relationships defined
- [ ] Soft deletes implemented for users, posts, pages, media
- [ ] UUIDs generated for posts, pages, media (portability)
- [ ] Indexes created on frequently queried columns (slug, status, created_at)
- [ ] Field-level encryption working for sensitive data
- [ ] Factories generate realistic fake data for all models
- [ ] Seeders create complete test dataset (users, posts, categories, tags, media)
- [ ] All 6 tasks completed with tests passing
- [ ] Quality gates passing (PHPStan Level 8, 80% coverage)

## 📚 References

**PRD Specifications:**
- Feature: `docs/prd/05-CORE-FEATURES.md` Section 2 (Content Management)
- Architecture: `docs/prd/04-ARCHITECTURE.md` Section 3 (Hybrid Storage Model)
- Timeline: Week 3 (v1.0.0)

**Architecture:**
- Pattern: Repository Pattern (data access layer)
- Storage: Hybrid (SQLite relational + flat-file content)
- Events: Model events (creating, created, updating, updated, deleting, deleted)

**Quality Requirements:**
- Security: Encrypted sensitive fields, foreign key constraints (`docs/prd/09-QUALITY-REQUIREMENTS.md`)
- Performance: Indexed columns, query optimization (< 100ms)
- Testing: Model unit tests, relationship tests, factory tests
- Data Integrity: Cascading deletes, referential integrity

**Acceptance Criteria:**
- Database migrations reversible (rollback supported)
- All relationships bidirectional and tested
- Factories generate valid data consistently
- Seeders run without errors and create realistic dataset
