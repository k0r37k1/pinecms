---
epic_id: 010
title: Import/Export & SEO
status: pending
priority: medium
estimated_duration: 1-2 weeks
actual_duration: null
week: Week 12-13
version: v1.0.0
tasks_total: 11
tasks_completed: 0
---

# Epic 010: Import/Export & SEO

## 🎯 Epic Goals

- Build Markdown import system (migration path)
- Create comprehensive export features (JSON, Markdown ZIP)
- Implement SEO fundamentals (Sitemap, RSS, Meta Tags)
- Add custom error pages

**Focus:** Core CMS functionality, not complex third-party migrations

## 📦 Features Delivered

**Import:**

- Markdown bulk import (ZIP with front matter)
- Content mapping UI
- Import progress tracking

**Export:**

- JSON full site export (backup/migration)
- Markdown ZIP export (portable format)
- Selective export (filter by content type, date)

**SEO:**

- XML sitemap (auto-updated)
- RSS feeds (posts, categories, tags)
- Meta tags management (OpenGraph, Twitter Cards)
- Canonical URLs
- Custom error pages (404, 403, 500, 503)

## 📋 Tasks (11 total)

- [ ] 080 - Markdown Bulk Import (6h)
- [ ] 081 - Content Mapping UI (5h)
- [ ] 082 - Import Progress Indicator (4h)
- [ ] 083 - JSON Site Export (5h)
- [ ] 084 - Markdown ZIP Export (6h)
- [ ] 085 - Selective Export (4h)
- [ ] 086 - XML Sitemap (5h)
- [ ] 087 - RSS Feed (5h)
- [ ] 088 - Meta Tags Management (6h)
- [ ] 089 - Canonical URLs (4h)
- [ ] 090 - Custom Error Pages (5h)

**Note:** WordPress/Ghost import moved to future version (too complex for MVP)

## 🔗 Dependencies

**Requires:**

- Epic 003 (Content Management - posts/pages to export)
- Epic 002 (Database Schema - content structure)

**Blocks:** None (SEO features independent)

## ✅ Epic Success Criteria

- [ ] Markdown import working (ZIP with images)
- [ ] JSON export creates valid backup
- [ ] Markdown ZIP export portable to other systems
- [ ] XML sitemap auto-updates on content changes
- [ ] RSS feed validates against standard
- [ ] Meta tags render correctly (OpenGraph, Twitter)
- [ ] Custom error pages styled consistently
- [ ] All 11 tasks completed
- [ ] Quality gates passing (PHPStan Level 8, 80% coverage)

## 📚 References

**PRD**: `docs/prd/05-CORE-FEATURES.md` Section 2.9 (Import/Export), Section 2.10 (SEO)
**Timeline**: Week 12-13 (v1.0.0)
**Architecture**: Service Layer (ImportService, ExportService, SitemapService)
