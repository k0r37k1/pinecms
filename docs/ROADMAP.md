# 🗺️ PineCMS Development Roadmap

> **Project:** PineCMS - Security & Privacy-First Flat-File Hybrid CMS
> **Target:** Shared Hosting Deployment (No Git Required)
> **License:** MIT - Open Source

---

## 📅 **Timeline Overview**

**Total Duration:** 14-19 Weeks (3.5 - 4.5 Months)
**MVP Feature Count:** ~243 Features (Phase 0-3)
**Total Feature Count:** ~288 Features (including Post-MVP)
**Target Release:** Q2 2025 (MVP v1.0.0)

---

## 🎯 **Phase 0: Installer & Foundation**

**Duration:** 1 Week
**Goal:** Make PineCMS installable on shared hosting via web installer

### Deliverables

- ✅ Web-based installer UI
- ✅ System requirements check (PHP 8.3+, Extensions)
- ✅ Automatic .env file generation
- ✅ SQLite database creation
- ✅ First admin user setup wizard
- ✅ Apache .htaccess configuration
- ✅ nginx.conf.example

### Success Criteria

- [ ] User can upload ZIP to shared hosting
- [ ] User can complete installation via browser
- [ ] Admin can login to dashboard
- [ ] No Git required on server

---

## 🚀 **Phase 1: MVP - Core CMS**

**Duration:** 6-8 Weeks
**Goal:** Functional blogging platform with admin panel

### Week 1-2: Content Foundation

- [ ] Database schema (Pages, Posts, Tags, Categories)
- [ ] Flat-file storage structure
- [ ] Revisions system (Flat-file based with Manual Checkpoints & Cleanup Policy)
- [ ] Basic CRUD for Pages
- [ ] Basic CRUD for Posts
- [ ] Custom Field Types System (12 built-in types):
  - [ ] Text (Short, Long, Rich)
  - [ ] Number, Boolean, Date/Time
  - [ ] Select, Relationship, Media
  - [ ] URL, Email, Color Picker, JSON, Repeater

### Week 3-4: Editor & Media

- [ ] TipTap Markdown Editor (13 Features):
  - [ ] Basic Formatting (Bold, Italic, Strike, Underline)
  - [ ] Headings (H1-H6), Lists (Bullet, Numbered, Task)
  - [ ] Code Blocks with Syntax Highlighting
  - [ ] Tables, Links with Preview, Images
  - [ ] Embeds (YouTube, Vimeo, Twitter)
  - [ ] Markdown Shortcuts, Slash Commands (/)
- [ ] WYSIWYG ↔ Markdown toggle
- [ ] Live Preview (Side-by-Side)
- [ ] Auto-Save (30 seconds)
- [ ] Media Library (Advanced Features):
  - [ ] Drag & Drop Upload
  - [ ] Supported Types (Images, Videos, Audio, Documents, Archives)
  - [ ] Image Processing (Crop, Filters, Responsive Images, Retina/HiDPI)
  - [ ] EXIF Data Stripping (Privacy)
  - [ ] Thumbnails, WebP Conversion
  - [ ] Image CDN Support (optional)

### Week 5-6: User Management

- [ ] User CRUD
- [ ] Role-Based Access Control (RBAC)
- [ ] 4 Default Roles with Permissions Matrix:
  - [ ] Administrator (Full system access)
  - [ ] Author (Create/edit own posts, upload media)
  - [ ] User (View content, comment, manage profile)
  - [ ] Guest (Read-only published content, configurable comments)
- [ ] Custom Permissions System
- [ ] User Profiles (Avatar, Bio)
- [ ] Registration Mode (Invite-Only by default, switchable to Public)
- [ ] Activity Log (User Actions Tracking)
- [ ] Authentication Log (Login History)

### Week 7-8: Admin Panel & Settings

- [ ] Dashboard (Stats, Quick Actions)
- [ ] Bulk Actions (7 Features):
  - [ ] Bulk Select, Publish/Unpublish, Delete
  - [ ] Bulk Move, Assign Author, Export
  - [ ] Inline Editing
- [ ] Site Settings (Tabbed UI):
  - [ ] General, SEO, Social, Privacy
  - [ ] Email, Security, Backup
  - [ ] Advanced (Asset Pipeline, Environment Config)
- [ ] Theme Switcher UI
- [ ] Dark/Light Mode Toggle
- [ ] Health Check Dashboard

### Week 9: Polish & Testing

- [ ] Custom Error Pages (404, 403, 500)
- [ ] Toast Notifications
- [ ] Loading States
- [ ] Responsive Design (Mobile, Tablet)
- [ ] SEO (Slugs, Meta, Sitemap, RSS)
- [ ] Backup System (One-Click)

### Success Criteria

- [ ] User can create/edit/delete Posts & Pages with Custom Fields
- [ ] TipTap Editor works with all 13 features (Markdown export, embeds, tables)
- [ ] Media Library functional with advanced image processing
- [ ] RBAC system works (4 roles: Administrator, Author, User, Guest)
- [ ] Bulk Actions work for content management
- [ ] Admin Panel fully functional with dark mode
- [ ] Themes switchable
- [ ] One-Click Backup works
- [ ] ~120 Phase 1 Features Implemented

---

## 💬 **Phase 2: Community & Engagement**

**Duration:** 3-4 Weeks
**Goal:** Social features & user engagement

### Week 10-11: Comments & Search

- [ ] Comment System (CRUD, Moderation)
- [ ] Nested Comments (Max Depth: 3)
- [ ] Upvote/Downvote Comments
- [ ] Guest Comments Toggle (disabled by default for privacy)
- [ ] Full-Text Search (10 Features):
  - [ ] Scout + TNTSearch (default)
  - [ ] Search Filters (by type, date, author, tags)
  - [ ] Search Suggestions, Analytics
  - [ ] Command Palette (CMD+K)
  - [ ] Search Highlighting
  - [ ] Alternative Drivers (Algolia, Meilisearch, Elasticsearch)

### Week 12: Analytics & i18n

- [ ] Matomo Integration (Self-Hosted)
- [ ] Privacy-First Analytics (IP Anonymization, Cookie Consent)
- [ ] Multi-Language Support (DE/EN)
- [ ] Content Translation UI
- [ ] Locale Switcher

### Week 13: Newsletter & Notifications

- [ ] Newsletter System (10 Features):
  - [ ] Newsletter Editor (separate from Posts)
  - [ ] Subscriber Management (separate from Users)
  - [ ] Email Campaign Scheduling
  - [ ] Newsletter Templates
  - [ ] Privacy-Friendly Tracking (optional open/click)
  - [ ] Unsubscribe Management, Double Opt-In
  - [ ] Subscriber Segmentation, A/B Testing
  - [ ] Newsletter Archive (public)
- [ ] In-App Notifications (Bell Icon)
- [ ] Email Notifications
- [ ] Notification Types (Comments, Reviews, Mentions, Newsletter)
- [ ] Mark as Read/Unread

### Success Criteria

- [ ] Comment System works with Moderation and Guest Comments toggle
- [ ] Search finds Posts/Pages/Tags with filters and suggestions
- [ ] Newsletter System functional with subscriber management
- [ ] Matomo tracks visits (Privacy-First with IP anonymization)
- [ ] Site available in DE & EN
- [ ] Users receive notifications (in-app & email)
- [ ] ~60 Phase 2 Features Implemented

---

## 🔧 **Phase 3: Advanced Features**

**Duration:** 4-6 Weeks
**Goal:** Pro features & extensibility

### Week 14-15: Content Workflow

- [ ] Workflow System (Draft → Review → Publish)
- [ ] Reviewer Assignment
- [ ] Approval/Rejection with Comments
- [ ] Workflow History
- [ ] Email Notifications (Review Requests)

### Week 16: Update Manager & Webhooks

- [ ] Update Manager:
  - [ ] Check for Updates (GitHub API)
  - [ ] One-Click Update System
  - [ ] Auto-Backup Before Update
  - [ ] Migration Runner
  - [ ] Rollback on Failure
- [ ] Webhook System (11 Features):
  - [ ] Webhook Management UI (Create, Edit, Delete)
  - [ ] 8 Event Types (post.published, user.created, comment.approved, etc.)
  - [ ] Custom Headers & Payloads
  - [ ] Webhook Testing & Logs
  - [ ] Retry Logic, Signing, Rate Limiting

### Week 17-18: Plugin System

- [ ] Event-Driven Architecture
- [ ] Plugin Discovery (Auto-Load)
- [ ] Plugin Activation/Deactivation UI
- [ ] Plugin Settings Pages
- [ ] Plugin Update System

### Week 19: Advanced UI & Routing

- [ ] Menu Builder (Drag & Drop)
- [ ] Redirect Management (301/302)
- [ ] Two-Factor Authentication (2FA)
- [ ] Widget System (Sidebar/Footer)
- [ ] Custom Routing (5 Features):
  - [ ] Multi-Language URLs
  - [ ] Hierarchical URLs
  - [ ] Route Wildcards
  - [ ] Route Conditions

### Success Criteria

- [ ] Content Workflow functional (Draft → Review → Publish)
- [ ] One-Click Update works with auto-backup
- [ ] Webhook System functional with event triggers
- [ ] Plugins can be installed/activated
- [ ] Menu Builder creates navigation
- [ ] Custom Routing works for multi-language URLs
- [ ] 2FA can be enabled
- [ ] ~55 Phase 3 Features Implemented

---

## 📦 **Release Preparation**

**Duration:** Post Phase 3 - 1-2 Weeks

### Pre-Release Checklist

- [ ] Security Audit (Code Review)
- [ ] Performance Testing (Shared Hosting)
- [ ] Cross-Browser Testing (Chrome, Firefox, Safari, Edge)
- [ ] Mobile Testing (iOS, Android)
- [ ] Documentation (Installation, User Guide, Developer Docs)
- [ ] Demo Site Setup
- [ ] Release Notes & Changelog
- [ ] MIT License File
- [ ] GitHub Repository Setup
- [ ] Community Guidelines (CONTRIBUTING.md, CODE_OF_CONDUCT.md)

### Release Artifacts

- [ ] pinecms-v1.0.0.zip (Production Build)
- [ ] Installation Guide (README.md)
- [ ] User Documentation (docs.pinecms.org)
- [ ] Developer Documentation (API, Hooks, Plugins)
- [ ] Demo Themes (3 Themes)
- [ ] Sample Content (Blog Posts, Pages)

---

## 🎯 **Post-Release (v1.1+)**

### Version 1.1 (Future - API & Integration)

- [ ] REST API (14 Features):
  - [ ] Authentication (Token-based, OAuth2)
  - [ ] Rate Limiting (per user/token)
  - [ ] API Versioning (v1, v2)
  - [ ] Swagger/OpenAPI Documentation
  - [ ] Endpoints (Posts, Pages, Users, Comments, Media, Tags, Categories)
  - [ ] Webhooks Integration
  - [ ] JSON:API Specification
  - [ ] API Key Management UI
- [ ] Import/Export System (8 Features):
  - [ ] WordPress Importer (posts, pages, media)
  - [ ] Ghost Importer (JSON format)
  - [ ] Markdown Importer (bulk import)
  - [ ] CSV Exporter
  - [ ] JSON Exporter
  - [ ] Backup/Restore (full site)
- [ ] Advanced SEO (4 Features):
  - [ ] Schema.org Markup (Article, Organization, BreadcrumbList)
  - [ ] AMP Support (Accelerated Mobile Pages)
  - [ ] Open Graph & Twitter Cards (enhanced)
  - [ ] XML Sitemap (advanced with images)

### Version 1.2 (Future - GraphQL & Advanced Features)

- [ ] GraphQL API (9 Features):
  - [ ] GraphQL Schema Explorer
  - [ ] Queries (Posts, Pages, Users, Comments)
  - [ ] Mutations (Create, Update, Delete)
  - [ ] Subscriptions (Real-time updates)
  - [ ] Authentication & Authorization
  - [ ] GraphQL Playground UI
  - [ ] Rate Limiting
  - [ ] Caching (Apollo Client compatible)
  - [ ] Schema Stitching
- [ ] Multi-Site Support (Network)
- [ ] E-Commerce Integration (Stripe, PayPal)
- [ ] Advanced Analytics (Heatmaps, A/B Testing)
- [ ] Custom Fields Builder (UI for creating field types)
- [ ] Form Builder (drag & drop)

---

## 📊 **Success Metrics**

### Technical

- [ ] Loads on Shared Hosting (< 5 seconds)
- [ ] SQLite Database (< 50MB for 1000 posts)
- [ ] Flat-Files (< 100MB for 1000 posts)
- [ ] Page Load (< 2 seconds)
- [ ] Lighthouse Score (> 90/100)

### Features

- [ ] ~243 MVP Features Implemented
- [ ] ~288 Total Features (including Post-MVP)
- [ ] 100% Feature Parity with Roadmap
- [ ] Zero Critical Bugs
- [ ] Full Mobile Responsiveness
- [ ] Advanced Features: Custom Fields, Newsletter, Webhooks

### Community

- [ ] GitHub Stars (Target: 100+)
- [ ] Contributors (Target: 5+)
- [ ] Active Installations (Target: 50+)
- [ ] Plugin Ecosystem (Target: 10+ Plugins)

---

## 🤝 **Contributing**

This roadmap is a living document. Community feedback shapes priorities.

**How to Contribute:**

- Star the repo on GitHub
- Report bugs/feature requests
- Submit Pull Requests
- Create plugins/themes
- Write documentation
- Translate to other languages

---

**Last Updated:** 2025-01-26
**Next Review:** End of Phase 1 (Week 9)

**Changes in this version:**

- Expanded Phase 1 from 80 to ~120 features
  (Custom Fields, TipTap details, Bulk Actions, Image Processing)
- Expanded Phase 2 from 40 to ~60 features (Newsletter System, Advanced Search)
- Expanded Phase 3 from 35 to ~55 features (Webhook System, Custom Routing)
- Added detailed Post-MVP roadmap (REST API, GraphQL API, v1.1-1.2)
- Updated all success criteria to reflect new features
- Total MVP features: 163 → 243 (+80 features)
- Total features including Post-MVP: ~288 features
