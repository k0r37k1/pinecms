# 📋 PineCMS - Feature Overview

> **Last Updated:** 2025-11-06
> **Architecture:** Core CMS + Official Plugins
> **License:** MIT - 100% Open Source

---

## 🎯 PineCMS Architecture

PineCMS besteht aus einem **schlanken Core** und **Official Plugins** für spezialisierte Features.

**Vorteile dieser Architektur:**

- ⚡ **Performance** - Core lädt in < 1 Sekunde auf Shared Hosting
- 🎯 **Modular** - Installiere nur was du brauchst
- 🔓 **Open Source** - 100% kostenlos (MIT License)
- 🔌 **Erweiterbar** - Community kann eigene Plugins entwickeln
- 🚀 **Shared Hosting** - PHP 8.3+, SQLite, kein Git nötig

---

## 📦 Version Roadmap

| Version          | Core Features | Official Plugins         | Total | Timeline     |
| ---------------- | ------------- | ------------------------ | ----- | ------------ |
| **v1.0.0 (MVP)** | ~95           | -                        | 95    | 10-11 Wochen |
| **v1.1.0**       | ~149          | 3 Plugins (~17 features) | 166   | +3-4 Wochen  |
| **v1.2.0**       | ~159          | 3 Plugins (~17 features) | 176   | +2-3 Wochen  |

**Total Timeline:** 15-18 Wochen (3.5-4.5 Monate) für v1.2.0

---

## 📖 Detailed Documentation

### Core Features

➡️ **[CORE_FEATURES.md](./CORE_FEATURES.md)** - Alle Core-Features im Detail

**Version 1.0.0 - MVP (~95 Features):**

- Installer & Setup
- Content Management (Posts, Pages, Tags, Categories, **Template Selection, Excerpt, Reading Time, Scheduler (UTC Storage), Concurrent Editing Protection**)
- TipTap Editor (Basic Features)
- Media Library (Basic with **Upload Security 5-Layer, Upload Settings, Max File Size, Allowed File Types**)
- User Management (2 Roles: Administrator, Author)
- Theme System (Blade + Alpine.js + TailwindCSS 4, **Frontend Event System**)
- Admin Panel (PrimeVue + Inertia.js + Vue 3.5)
- SEO Basics (Slugs, Meta, Sitemap, RSS)
- Privacy & Security (CipherSweet, CSRF, XSS Protection)
- Backup System (One-Click Backup/Restore)
- **Import/Export (WordPress, Ghost, Markdown)** - Critical for adoption

**Removed from Core (moved to themes/plugins):**

- ❌ Upvote/Downvote System (Posts & Comments) → "Voting System" Community Plugin
- ❌ Social Sharing Buttons → Theme-specific or Community Plugin
- ❌ Breadcrumbs (Auto-Generated) → Theme-specific
- ❌ Image Preview & Lightbox → Theme Modal only

**Version 1.1.0 - Enhanced (+54 Features, Total: ~149):**

- Comments System (Nested, Moderation, Guest Comments, Sorting)
- Search System (TNTSearch, Command Palette CMD+K)
- RBAC Enhanced (4 Roles: Administrator, Author, User, Guest)
- Advanced Media (Crop, Filters, EXIF Stripping, Retina Support, **Media Usage Tracking**)
- TipTap Editor Advanced (Tables, Embeds, Slash Commands)
- Bulk Actions (Select, Publish, Delete, Move, Export, **Bulk Status Change**)
- **No default analytics** - Privacy-First (users integrate their own via community plugins)
- Custom Fields Advanced (Select, URL, Email, Rich Text)
- **Workflow System (CORE)** - Draft → Review → Published, Reviewer Assignment, Email Notifications
- **Plugin System** - Event-Driven Architecture
- **Update Manager** - One-Click Updates
- **Revisions:** Side-by-side Diff-View (moved from v1.0.0)
- **Email Templates:** Rich HTML Templates (moved from v1.0.0)

**Version 1.2.0 - Professional (+10 Features, Total: ~159):**

- Import/Export Enhancements (Medium, Substack, Error Handling, Scheduled Exports)
- Advanced SEO (Schema.org, Open Graph Enhanced)
- Redirect Management (301/302, Bulk Import, Wildcards)
- Menu Builder (Drag & Drop, Nested Items)
- Widget System (Sidebar, Footer, Drag & Drop)
- Custom Routing (Multi-Language URLs, Hierarchical URLs)

**Note:** Basic Import/Export (WordPress, Ghost, Markdown) is in v1.0.0

---

### Official Plugins

➡️ **[OFFICIAL_PLUGINS.md](./OFFICIAL_PLUGINS.md)** - 4 Official Plugins im Detail

**Alle Plugins sind 100% Open Source & Kostenlos (MIT License)**

1. **Newsletter Plugin** (~6 Features)
    - Newsletter Editor (TipTap), Subscriber Management, Sign-up Widget
    - Campaign Scheduling, Email Templates, Privacy-Friendly Tracking
    - Double Opt-In, Segmentation by Tags/Categories

2. **Multi-Language Plugin** (~8 Features)
    - Unlimited Languages, Content Translation UI
    - Language-Specific URLs (Prefix/Subdirectory)
    - RTL Support (Arabic, Hebrew), Auto-Detect Browser Language
    - SEO for Multi-Language (hreflang, Sitemap, RSS)

3. **SEO+ Plugin** (~3 Features)
    - Schema.org (Article, Blog, Organization)
    - Open Graph & Twitter Cards (Enhanced)
    - Advanced Sitemap (Image, Video, News), Broken Link Checker

---

### Development Roadmap

➡️ **[ROADMAP.md](./ROADMAP.md)** - Kompletter Entwicklungsplan

**Wichtige Meilensteine:**

- **Week 1:** Installer & Foundation
- **Week 2-9:** Core CMS (v1.0.0)
- **Week 10-13:** Enhanced Core + Plugin System + 5 Plugins (v1.1.0)
- **Week 14-15:** Professional Features + 3 Additional Plugins (v1.2.0)
- **Week 16-17:** Release Preparation

**Target Release:** Q2 2025 (v1.0.0), Q3 2025 (v1.2.0)

---

### Plugin Development

➡️ **[PLUGIN_DEVELOPMENT.md](./PLUGIN_DEVELOPMENT.md)** - Plugin Entwicklung Guide

**Für Community-Entwickler:**

- Plugin Structure & Architecture
- Event-Driven Development (Laravel Events)
- Admin UI Integration (Inertia.js + Vue)
- Frontend Integration (Blade Templates)
- Database Migrations
- Distribution & Publishing

---

## 🌍 What Makes PineCMS Different?

### vs WordPress

- ✅ **Keine Datenbank-Migration** - SQLite + Flat-Files (Git-freundlich)
- ✅ **Schneller** - < 1 Sekunde Ladezeit (WordPress: 2-5 Sekunden)
- ✅ **Sicherer** - Weniger Angriffsfläche, kein Plugin-Chaos
- ✅ **Moderner Stack** - Laravel 12 + Vue 3.5 + Inertia.js 2.0

### vs Ghost

- ✅ **Shared Hosting** - Kein Node.js/Docker nötig
- ✅ **Günstiger** - Keine Ghost Pro Kosten ($9-199/Monat)
- ✅ **No default analytics** - Privacy-First by design (Ghost has tracking)
- ✅ **Plugin-System** - Ghost hat kein Plugin-System

### vs Statamic

- ✅ **100% Kostenlos** - Statamic kostet $259 pro Site
- ✅ **Einfacher** - Weniger komplex als Statamic
- ✅ **Plugin-Ökosystem** - Community kann eigene Plugins bauen

---

## 📊 Feature Summary

### Core Features (v1.2.0)

- **Content:** Posts, Pages, Tags, Categories, Custom Fields, Revisions, Concurrent Editing Protection (Optimistic Locking), Scheduler (UTC Storage, DST-safe)
- **Editor:** TipTap WYSIWYG (Markdown, Tables, Embeds, Slash Commands)
- **Media:** Upload Security (5-Layer Protection: Extension Whitelist, MIME Validation, Imagick/GD Re-encoding, Size Limits, Filename Sanitization), Crop, Filters, Responsive Images, EXIF Stripping
- **Users:** 4 Roles (Administrator, Author, User, Guest), RBAC, Activity Log
- **Comments:** Nested (3 levels), Moderation, Guest Comments (optional)
- **Search:** TNTSearch, Command Palette (CMD+K), Filters
- **Analytics:** No default tracking (users integrate their own via community plugins)
- **SEO:** Slugs, Meta, OpenGraph, Twitter Cards, Sitemap, RSS, Schema.org
- **Security:** CipherSweet, CSRF, XSS, Rate Limiting, Secure Headers
- **Backup:** One-Click Backup/Restore (SQLite + Flat-Files + Media)
- **Themes:** Blade + Alpine.js + TailwindCSS 4, Theme Switcher, Frontend Event System (Laravel Events)
- **Admin:** PrimeVue + Inertia.js + Vue 3.5, Dark Mode
- **Plugins:** Event-Driven Architecture, Plugin Manager UI
- **Updates:** One-Click Update Manager (GitHub API)
- **Import/Export:** WordPress, Ghost, Markdown (v1.0.0) + Enhancements (Medium, Substack - v1.2.0)
- **Menus:** Drag & Drop Menu Builder
- **Widgets:** Drag & Drop Widget System
- **Redirects:** 301/302 Management

**Total Core:** ~159 Features

### Official Plugins

- Newsletter (~6 Features)
- Multi-Language (~8 Features)
- SEO+ (~3 Features)

**Total Plugins:** ~17 Features

**Grand Total:** ~176 Features (159 Core + 17 Plugins)

**Software Engineering Improvements (2025-11-06):**

- **Removed 4 Complete Plugins:**
    - ❌ Webhooks (~11) → v2.0 (over-engineered)
    - ❌ Two-Factor Auth (~5) → v2.0 (core security sufficient)
    - ❌ Form Builder (~12) → v2.0 (too complex)
    - ❌ Workflow (~6) → **Moved to Core v1.1.0** (essential for teams)
- **Plugin Feature Cleanup (YAGNI/KISS):**
    - Newsletter: Bounce Handling → v2.0, Added Signup Widget
    - Custom Fields Pro: Moved to Community Plugin
    - Multi-Language: Added RTL Support (critical)
    - SEO+: 7 Schema → 3, SEO Analysis → v2.0
- Added 15 critical Core features (Workflow, Frontend Events, Concurrent Editing, UTC Storage, Upload Security 5-Layer, etc.)
- Removed 10 YAGNI features from Core (moved to Community Plugins or Theme-specific)
- Fixed 1 DRY violation (merged Rich Text + WYSIWYG fields)
- Eliminated deprecated features (AMP Support)
- **Result:** 8 Plugins → 3 Plugins (~60 → ~17 features, -72%), Grand Total: ~213 → ~176 features

---

## 🚀 Getting Started

### Installation (v1.0.0+)

1. Download `pinecms-v1.0.0.zip`
2. Upload to Shared Hosting (via FTP/SFTP)
3. Extract ZIP
4. Visit `https://your-domain.com/install`
5. Follow Web Installer (5 minutes)
6. Login to Admin Panel
7. Install Optional Plugins (v1.1.0+)

### System Requirements

- **PHP:** 8.3+
- **Database:** SQLite (no MySQL required)
- **Web Server:** Apache or nginx
- **Extensions:** PDO, OpenSSL, Mbstring, Tokenizer, JSON, Ctype, BCMath
- **Storage:** 100MB minimum

---

## 🤝 Contributing

PineCMS ist 100% Open Source (MIT License). Community-Beiträge sind willkommen!

**Ways to Contribute:**

- ⭐ Star the repo on GitHub
- 🐛 Report bugs/feature requests
- 💻 Submit Pull Requests
- 🔌 Create plugins/themes
- 📝 Write documentation
- 🌍 Translate to other languages
- 💬 Help in Discussions

**Plugin Developers:**

- See [PLUGIN_DEVELOPMENT.md](./PLUGIN_DEVELOPMENT.md)
- Join [Discord](https://discord.gg/pinecms) (coming soon)
- Email: <plugins@pinecms.org>

---

## 📞 Support & Community

- 📖 **Documentation:** [docs.pinecms.org](https://docs.pinecms.org) (coming soon)
- 💬 **Discussions:** [GitHub Discussions](https://github.com/pinecms/pinecms/discussions)
- 🐛 **Issues:** [GitHub Issues](https://github.com/pinecms/pinecms/issues)
- 🔌 **Plugins:** [plugins.pinecms.org](https://plugins.pinecms.org) (coming in v2.0)
- 🎨 **Themes:** [themes.pinecms.org](https://themes.pinecms.org) (coming in v2.0)

---

**Last Updated:** 2025-11-06
**Maintained By:** PineCMS Team
**License:** MIT - 100% Open Source & Kostenlos
