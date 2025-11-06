# 📋 PineCMS - Feature Overview

> **Last Updated:** 2025-01-26
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

| Version | Core Features | Official Plugins | Total | Timeline |
|---------|--------------|------------------|-------|----------|
| **v1.0.0 (MVP)** | ~95 | - | 95 | 10-11 Wochen |
| **v1.1.0 (Enhanced)** | ~143 | 5 Plugins (~38 features) | 181 | +3-4 Wochen |
| **v1.2.0 (Professional)** | ~153 | 8 Plugins (~60 features) | 213 | +2-3 Wochen |

**Total Timeline:** 15-18 Wochen (3.5-4.5 Monate) für v1.2.0

---

## 📖 Detailed Documentation

### Core Features

➡️ **[CORE_FEATURES.md](./CORE_FEATURES.md)** - Alle Core-Features im Detail

**Version 1.0.0 - MVP (~95 Features):**

- Installer & Setup
- Content Management (Posts, Pages, Tags, Categories, **Template Selection, Excerpt, Reading Time**)
- TipTap Editor (Basic Features)
- Media Library (Basic with **Upload Settings, Max File Size, Allowed File Types**)
- User Management (2 Roles: Administrator, Author)
- Theme System (Blade + Alpine.js + TailwindCSS 4, **Frontend Hooks System**)
- Admin Panel (PrimeVue + Inertia.js + Vue 3.5)
- SEO Basics (Slugs, Meta, Sitemap, RSS)
- Privacy & Security (CipherSweet, CSRF, XSS Protection)
- Backup System (One-Click Backup/Restore)

**Removed from Core (moved to themes/plugins):**

- ❌ Upvote/Downvote System (Posts & Comments) → "Voting System" Community Plugin
- ❌ Social Sharing Buttons → Theme-specific or Community Plugin
- ❌ Breadcrumbs (Auto-Generated) → Theme-specific
- ❌ Image Preview & Lightbox → Theme Modal only

**Version 1.1.0 - Enhanced (+48 Features, Total: ~143):**

- Comments System (Nested, Moderation, Guest Comments, Sorting)
    - Note: Upvote/Downvote → "Voting System" Community Plugin
- Search System (TNTSearch, Command Palette CMD+K)
- RBAC Enhanced (4 Roles: Administrator, Author, User, Guest)
- Advanced Media (Crop, Filters, EXIF Stripping, Retina Support, **Media Usage Tracking**)
- TipTap Editor Advanced (Tables, Embeds, Slash Commands)
- Bulk Actions (Select, Publish, Delete, Move, Export, **Bulk Status Change**)
- **Matomo Analytics (CORE)** - Privacy-First, Self-Hosted
- Custom Fields Advanced (Select, URL, Email, Rich Text)
- **Plugin System** - Event-Driven Architecture
- **Update Manager** - One-Click Updates
- **Revisions:** Side-by-side Diff-View (moved from v1.0.0)
- **Email Templates:** Rich HTML Templates (moved from v1.0.0)

**Version 1.2.0 - Professional (+10 Features, Total: ~153):**

- Import/Export (WordPress, Ghost, Markdown)
- Advanced SEO (Schema.org, Open Graph Enhanced)
- Redirect Management (301/302, Bulk Import, Wildcards)
- Menu Builder (Drag & Drop, Nested Items)
- Widget System (Sidebar, Footer, Drag & Drop)
- Custom Routing (Multi-Language URLs, Hierarchical URLs)

---

### Official Plugins

➡️ **[OFFICIAL_PLUGINS.md](./OFFICIAL_PLUGINS.md)** - 8 Official Plugins im Detail

**Alle Plugins sind 100% Open Source & Kostenlos (MIT License)**

1. **Newsletter Plugin** (~7 Features)
   - Newsletter Editor, Subscriber Management, Campaign Scheduling
   - Template Selection, Privacy-Friendly Tracking, Double Opt-In
   - Segmentation by Tags/Categories

2. **Webhooks Plugin** (~11 Features)
   - Webhook Management UI, 8 Event Types
   - Custom Headers & Payloads, Testing & Logs
   - Retry Logic, Signing, Rate Limiting

3. **Custom Fields Pro Plugin** (~7 Features)
   - Relationship Fields, Repeater, Gallery
   - JSON Field, Color Picker, Rich Text Field (Full/Mini)
   - File Field

4. **Multi-Language Plugin** (~7 Features)
   - Unlimited Languages, Content Translation UI
   - Language-Specific URLs (Prefix/Subdirectory)
   - SEO for Multi-Language (hreflang, Sitemap, RSS)

5. **Workflow Plugin** (~6 Features)
   - Draft → Review → Published Workflow
   - Reviewer Assignment, Approval/Rejection
   - Review Comments, Workflow History, Email Notifications

6. **Two-Factor Auth Plugin** (~5 Features)
   - 2FA Setup (QR Code), TOTP Support
   - Backup Codes, Recovery Options

7. **Form Builder Plugin** (~12 Features)
   - Drag & Drop Form Builder, 12 Field Types
   - Submissions Management, Email Notifications
   - Anti-Spam (Honeypot, reCAPTCHA, hCaptcha)

8. **SEO Pro Plugin** (~5 Features)
   - Schema.org Advanced, Open Graph Enhanced
   - Advanced Sitemap, Broken Link Checker, SEO Analysis

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
- ✅ **Matomo im Core** - Privacy-First Analytics (Ghost braucht externe Services)
- ✅ **Plugin-System** - Ghost hat kein Plugin-System

### vs Statamic

- ✅ **100% Kostenlos** - Statamic kostet $259 pro Site
- ✅ **Einfacher** - Weniger komplex als Statamic
- ✅ **Plugin-Ökosystem** - Community kann eigene Plugins bauen

---

## 📊 Feature Summary

### Core Features (v1.2.0)

- **Content:** Posts, Pages, Tags, Categories, Custom Fields, Revisions
- **Editor:** TipTap WYSIWYG (Markdown, Tables, Embeds, Slash Commands)
- **Media:** Upload, Crop, Filters, Responsive Images, EXIF Stripping
- **Users:** 4 Roles (Administrator, Author, User, Guest), RBAC, Activity Log
- **Comments:** Nested (3 levels), Moderation, Guest Comments (optional)
- **Search:** TNTSearch, Command Palette (CMD+K), Filters
- **Analytics:** Matomo Self-Hosted (Privacy-First, Cookieless)
- **SEO:** Slugs, Meta, OpenGraph, Twitter Cards, Sitemap, RSS, Schema.org
- **Security:** CipherSweet, CSRF, XSS, Rate Limiting, Secure Headers
- **Backup:** One-Click Backup/Restore (SQLite + Flat-Files + Media)
- **Themes:** Blade + Alpine.js + TailwindCSS 4, Theme Switcher
- **Admin:** PrimeVue + Inertia.js + Vue 3.5, Dark Mode
- **Plugins:** Event-Driven Architecture, Plugin Manager UI
- **Updates:** One-Click Update Manager (GitHub API)
- **Import/Export:** WordPress, Ghost, Markdown
- **Menus:** Drag & Drop Menu Builder
- **Widgets:** Drag & Drop Widget System
- **Redirects:** 301/302 Management

**Total Core:** ~153 Features

### Official Plugins

- Newsletter (~7 Features)
- Webhooks (~11 Features)
- Custom Fields Pro (~7 Features)
- Multi-Language (~7 Features)
- Workflow (~6 Features)
- Two-Factor Auth (~5 Features)
- Form Builder (~12 Features)
- SEO Pro (~5 Features)

**Total Plugins:** ~60 Features

**Grand Total:** ~213 Features

**Software Engineering Improvements:**

- Removed 15 YAGNI features (Enterprise/overengineered → v2.0)
- Fixed 1 DRY violation (merged Rich Text + WYSIWYG fields)
- Simplified 3 KISS violations (JSON Editor, Template Builder, Workflow)
- Eliminated deprecated features (AMP Support)

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

**Last Updated:** 2025-01-26
**Maintained By:** PineCMS Team
**License:** MIT - 100% Open Source & Kostenlos
