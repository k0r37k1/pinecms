---
epic_id: 001
title: Installer & Foundation
status: pending
priority: critical
estimated_duration: 2 weeks
actual_duration: null
week: Week 1-2
version: v1.0.0
tasks_total: 8
tasks_completed: 0
---

# Epic 001: Installer & Foundation

## 🎯 Epic Goals

- Deliver turnkey web installer for shared hosting users
- Zero Git/Composer requirement on production servers
- Complete installation in under 5 minutes
- Generate production-ready configuration automatically
- Establish foundational architecture for hybrid storage

## 📦 Features Delivered

- System requirements validation (PHP 8.3+, extensions, permissions)
- Web-based installation wizard
- Automatic `.env` generation with secure APP_KEY
- SQLite database initialization
- First admin user creation
- Apache `.htaccess` and nginx configuration templates
- Post-install cleanup and security lockdown
- Cron job detection and setup guidance

## 📋 Tasks (8 total)

- [ ] 001 - System Requirements Check (4h)
- [ ] 002 - Environment File Generator (6h)
- [ ] 003 - Database Setup & Initialization (5h)
- [ ] 004 - Admin User Creation Wizard (4h)
- [ ] 005 - Web Server Configuration Generator (5h)
- [ ] 006 - Post-Install Cleanup & Security (3h)
- [ ] 007 - Cron Job Detection & Instructions (4h)
- [ ] 008 - Installation Wizard UI (8h)

## 🔗 Dependencies

**Blocks:**

- Epic 002 (Database Schema & Models)
- Epic 003 (Content Management Backend)
- All subsequent epics

**Requires:**

- None (Foundation epic)

## ✅ Epic Success Criteria

- [ ] User can upload PineCMS ZIP to shared hosting
- [ ] Installation wizard completes in < 5 minutes
- [ ] System validates all PHP 8.3+ requirements automatically
- [ ] `.env` file generated with secure random APP_KEY
- [ ] SQLite database created at `database/pinecms.sqlite`
- [ ] First admin user can login to dashboard
- [ ] Apache `.htaccess` auto-generated for mod_rewrite
- [ ] nginx.conf.example provided with clear instructions
- [ ] Installer directory auto-removed after completion
- [ ] All 8 tasks completed with tests passing
- [ ] Quality gates passing (PHPStan Level 8, 80% coverage)

## 📚 References

**PRD Specifications:**

- Feature: `docs/prd/05-CORE-FEATURES.md` Section 2.1 (Phase 0: Installer & Setup)
- Timeline: Week 1-2 (v1.0.0)

**Architecture:**

- Pattern: Layered Architecture (Controllers → Services → Repositories)
- Storage: Hybrid (SQLite + Flat-File preparation)
- Events: None (foundation tasks)

**Quality Requirements:**

- Security: File permission validation, secure key generation (`docs/prd/09-QUALITY-REQUIREMENTS.md`)
- Performance: Installation < 5 minutes
- Testing: Feature tests for installation flow, Unit tests for validation logic
- Accessibility: Installation wizard WCAG 2.1 AA compliant

**Acceptance Criteria:**

- Installation completion rate > 95%
- Time to install < 10 minutes
- Clear error messages for missing requirements
- No Git required on server
- No Composer required on server
