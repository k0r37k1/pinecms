# 🗺️ PineCMS Development Roadmap

> **Project:** PineCMS - Security & Privacy-First Flat-File Hybrid CMS
> **Target:** Shared Hosting Deployment (No Git Required)
> **License:** MIT - 100% Open Source
> **Architecture:** Core CMS + Official Plugins

---

## 📅 **Timeline Overview**

**Core Development:** 15-18 Weeks (3.5-4.5 Months)
**Plugin Development:** Parallel to Core (weeks 11-18)
**Target Release:** Q2 2025 (v1.0.0), Q3 2025 (v1.2.0)

**Key Changes:**

- ✅ Extended v1.0.0 to 10-11 weeks (was 8-10) - More realistic timeline
- ✅ Import/Export moved from v1.2.0 → v1.0.0 (Critical for adoption)
- ✅ Enhanced Core Features:
    - Hierarchical Categories (Parent → Child, Default Category)
    - Enhanced Media Library (Upload Security 5-Layer, Alt Text, Focal Point, EXIF stripping)
    - Advanced Live Preview (Device switcher with breakpoints, Full preview mode)
    - Enhanced Scheduler (UTC Storage, Unpublish date, Timezone support, DST-safe)
    - Revision Management (Limits, Auto-cleanup, Changes List)
    - Post Essentials (Template Selection, Excerpt, Reading Time, Duplicate)
- ✅ Removed YAGNI Features: Upvote/Downvote, Social Sharing, Breadcrumbs, Lightbox
- ✅ +15 Core Features in v1.0.0 (95 instead of 80), -4 YAGNI = Net +11

### Feature Counts

| Version                   | Core Features | Official Plugins         | Total |
| ------------------------- | ------------- | ------------------------ | ----- |
| **v1.0.0 (MVP)**          | ~95           | -                        | 95    |
| **v1.1.0 (Enhanced)**     | ~149          | 3 Plugins (~21 features) | 170   |
| **v1.2.0 (Professional)** | ~159          | 4 Plugins (~24 features) | 183   |

---

## 🎯 **Version 1.0.0 - Core CMS (MVP)**

**Duration:** 10-11 Weeks
**Goal:** Schlankes, schnelles Blogging-CMS mit Migration-Path
**Features:** ~90

### Week 1-2: Installer & Foundation

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### Deliverables

- ✅ Web-based Installer UI
- ✅ System Requirements Check (PHP 8.3+, Extensions)
- ✅ Automatic .env File Generation
- ✅ SQLite Database Creation
- ✅ First Admin User Setup Wizard
- ✅ Apache .htaccess Configuration
- ✅ nginx.conf.example

#### Success Criteria

- User can upload ZIP to shared hosting
- User can complete installation via browser
- Admin can login to dashboard
- No Git required on server

---

### Week 3-5: Content Management

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### Week 3-4: Core Content

- Database Schema (Pages, Posts, Tags, Categories)
- Flat-File Storage Structure (Markdown)
- Revisions System (Flat-File with Auto-Save, Limits, Auto-Cleanup, Changes List)
- Basic CRUD for Pages
- Basic CRUD for Posts with:
    - Template Selection (Default, Full Width, Sidebar)
    - Excerpt (Manual or Auto-generated from first 160 chars)
    - Reading Time Calculation (~200 words/min)
    - Duplicate Post feature
- Basic Custom Fields (Text, Number, Date, Boolean)
- Enhanced Content Scheduler (Publish/Unpublish Date/Time, UTC Storage, Timezone Support, DST-safe)

**Note:** Side-by-side Revision Diff moved to v1.1.0

#### Week 4: TipTap Editor (Basic)

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical

- Basic Formatting (Bold, Italic, Strike, Underline)
- Headings (H1-H6), Lists (Bullet, Numbered, Task)
- Code Blocks (Syntax Highlighting)
- Links with Preview
- Images (Upload, URL, Drag & Drop)
- WYSIWYG ↔ Markdown Toggle
- Live Preview:
    - Split View (Side-by-Side)
    - Device Switcher (Desktop / Tablet / Mobile)
    - Full Preview Mode
- Auto-Save (30 seconds)

#### Week 5: Media Library (Basic)

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical

- Media Library UI
- Upload Security (5-Layer Protection):
    - File Extension Whitelist
    - MIME Type Validation
    - Content Validation (Imagick/GD re-encoding, PDF parsing)
    - File Size Limits (10MB default, configurable)
    - Filename Sanitization (UUID + Timestamp)
- Upload Settings:
    - Max File Size (configurable)
    - Allowed File Types (whitelist)
    - Upload Directory Structure (YYYY/MM)
- Drag & Drop Upload
- Supported Types (Images: JPEG, PNG, GIF, WebP; Documents: PDF)
- Image Processing:
    - Auto-Resize
    - Thumbnail Generation
    - WebP Conversion
    - Compression
    - EXIF Data Stripping (Privacy)
- Image Metadata:
    - Alt Text (Accessibility/SEO)
    - Title & Caption
    - Focal Point Selection
- File Organization (Folders)
- Media Search & Filters
- Image Preview (Modal)

**Note:** Media Usage Tracking moved to v1.1.0 (Week 12)

---

### Week 6: User Management & Theme System

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### User Management

- User CRUD
- User Profiles (Avatar, Bio, Social Links)
- 2 Roles (v1.0):
    - **Administrator** (Full system access)
    - **Author** (Create/edit own posts, upload media)
- Authentication:
    - Login/Logout
    - Password Reset
    - Remember Me
    - Invite-Only Registration (default)
- Activity Log (Admin Actions)
- Authentication Log (Login History)

#### Theme System (Frontend & Admin)

##### Frontend (Public Site)

- Blade Templates + Alpine.js + Tailwind CSS 4
- 1 Default Theme (included)
- Theme Switcher UI (Admin Panel)
- Dark/Light Mode Toggle (User Preference)
- Lucide Icons

##### Admin Panel

- PrimeVue + Inertia.js + Vue 3.5
- Dark/Light Mode Toggle
- PrimeIcons
- Responsive Design (Mobile-Friendly)

---

### Week 7: Categories & Tags System

**Status:** 📋 Geplant
**Priorität:** 🟠 High
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

- Hierarchical Categories (Parent → Child, max 2 levels)
- Category CRUD with Slugs & Descriptions
- Category Color/Badge
- Default Category ("Uncategorized" auto-assign)
- Drag & Drop Reordering
- Tag CRUD with Autocomplete
- Tag Usage Count & Merging
- Popular Tags Widget

---

### Week 8: Admin Panel & Settings

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### Dashboard

- Quick Stats (Posts, Pages, Users)
- Recent Activity Feed
- Quick Actions (New Post, New Page)
- System Health (Storage, Cache)

#### Site Settings (Tabbed UI)

- **General Tab:** Site Name, Logo, Favicon, Timezone, Date/Time Format
- **SEO Tab:** Meta Templates, Sitemap, Robots.txt
- **Privacy Tab:** Cookie Consent, Field-Level Encryption, GDPR Settings
- **Email Tab:** SMTP Config, Plain Text Email Templates, Test Email
- **Security Tab:** Registration Mode, Rate Limiting, CSP, Secure Headers
- **Backup Tab:** Auto-Backup Schedule (Local only), One-Click Backup/Restore

**Note:** Rich HTML Email Templates in v1.1.0, S3/SFTP Backup via Plugin

---

### Week 9: Import/Export System

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical
**Typ:** 🎯 Core Feature
**Version:** v1.0.0
**Note:** Critical for user adoption

- Import from WordPress (XML/WXR format)
- Import from Ghost (JSON export)
- Import from Markdown Files (Bulk)
- Content Mapping UI
- Import Preview & Progress
- Export to JSON & Markdown (ZIP)
- Selective Export

---

### Week 10: SEO, Privacy & Polish

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical
**Typ:** 🎯 Core Feature
**Version:** v1.0.0
**Note:** Essential for production release

#### SEO Features

- Auto-Slug Generation (Eloquent Sluggable)
- Custom Slugs (Editable)
- XML Sitemap (Auto-Updated)
- RSS Feed (Posts, Categories, Tags)
- Meta Tags (OpenGraph, Twitter Cards)
- Canonical URLs

**Note:** Breadcrumbs are theme-specific (not in core)

#### Privacy & Security

- Cookie Consent Banner
- Field-Level Encryption (CipherSweet)
- CSRF Protection
- XSS Protection
- Rate Limiting (Forms, Login)

#### Backup System

- Automated Backups (Database, Flat-Files, Media)
- Backup Scheduler (Daily/Weekly/Monthly)
- One-Click Backup
- One-Click Restore

#### UI/UX Polish

- Custom Error Pages (404, 403, 500, 503)
- Loading States (Skeleton Loaders)
- Toast Notifications (Success, Error, Info, Warning)
- Modal System (Confirm Dialogs)
- Form Validation (Real-Time)
- Responsive Design (Mobile, Tablet, Desktop)

**Note:** Lightbox is theme-specific (not in core)

---

### v1.0.0 Success Criteria

**Status:** 📋 Geplant
**Version:** v1.0.0

- User can create/edit/delete Posts & Pages
- Posts have Template Selection, Excerpt, Reading Time, Duplicate feature
- TipTap Editor works with enhanced live preview (device switcher: 1280px+/768px-1279px/<768px)
- Media Library functional with Alt Text, Focal Point, EXIF stripping, Upload Settings
- 2-role system works (Administrator, Author)
- Hierarchical Categories with Default Category & Enhanced Tags
- Import from WordPress/Ghost works
- Enhanced Content Scheduler (Publish/Unpublish dates, UTC Storage, Timezone, DST-safe)
- Revision Management with Limits, Auto-Cleanup, Changes List
- Theme system works (1 default theme included)
- One-Click Backup/Restore works (Local storage)
- Installation completes in < 5 minutes on shared hosting
- Page load < 1 second on shared hosting
- **~95 Core Features Implemented** (was 90)

**Feature Count History:**

- **2025-11-06:** 90 → 95 Features (Net: +5) nach SEP-Analyse
    - Added (+15): Hierarchical Categories, Template Selection, Excerpt, Reading Time, Import/Export, Frontend Event System, Concurrent Editing Protection, UTC Storage, Upload Security (5-Layer), Revision Enhancements, Media Settings, Duplicate Post, Email Templates, Bulk Status Change, Media Usage Tracking
    - Removed (-10): Upvote/Downvote (→ Community Plugin), Social Sharing (→ Theme), Breadcrumbs (→ Theme), Lightbox (→ Theme), Advanced Analytics (→ Matomo), Multi-Level Comments (>3 levels), Advanced SEO (→ SEO Pro Plugin), Dashboard Widgets (→ Plugin System)

**Removed from Core (YAGNI):**

- ❌ Upvote/Downvote System (Posts) → "Voting System" Community Plugin
- ❌ Upvote/Downvote Comments (v1.1.0) → "Voting System" Community Plugin
- ❌ Social Sharing Buttons → Theme-specific
- ❌ Breadcrumbs Navigation → Theme-specific
- ❌ Lightbox → Theme Modal only
- ❌ Media Usage Tracking → Moved to v1.1.0 (complexity)

---

## 📊 Status System

Alle Sections verwenden das einheitliche Status-Marker-System:

**Status-Marker:**

- ✅ **Abgeschlossen** - Feature implementiert und getestet
- 🚧 **In Arbeit** - Feature wird aktuell entwickelt
- 📋 **Geplant** - Feature geplant, noch nicht gestartet
- ⏸️ **Pausiert** - Entwicklung temporär pausiert
- ❌ **Gestrichen** - Feature aus Scope entfernt

**Priorität-Marker:**

- 🔴 **Critical** - Blocker für Release (muss fertig sein)
- 🟠 **High** - Wichtig, sollte im Release sein
- 🟡 **Medium** - Wünschenswert, kann verschoben werden
- 🟢 **Low** - Nice-to-have, optional

**Typ-Marker:**

- 🎯 **Core Feature** - Kern-Funktionalität (Core CMS)
- 🔧 **Enhancement** - Verbesserung/Erweiterung
- 🔌 **Plugin** - Plugin-Feature
- 🧪 **Experimental** - Beta/Testing

➡️ Detaillierte Dokumentation: `docs/DOCUMENTATION_STATUS.md`

---

## 🚀 **Version 1.1.0 - Enhanced Core + Plugin System**

**Duration:** +3-4 Weeks
**Goal:** Professionelle Features + Plugin-System + Analytics + Workflow
**Core Features:** +54 (Total: ~149)
**Plugins:** 3 Official Plugins (~21 features)

### Week 10-11: Comments & Search

**Status:** 📋 Geplant
**Priorität:** 🟠 High
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

#### Comments System

- Comment CRUD
- Moderation Queue (Approve/Reject/Spam)
- Guest Comments Toggle (disabled by default)
- Nested Comments (Max Depth: 3)
- Comment Sorting (Newest, Oldest)
- Email Notifications
- Gravatar Support
- Markdown Support in Comments

**Removed (YAGNI):**

- ❌ Upvote/Downvote Comments → "Voting System" Community Plugin

#### Search System

- Full-Text Search (TNTSearch default)
- Search Filters (by type, date, author, tags/categories)
- Search Highlighting
- Search Suggestions

#### Command Palette (CMD+K)

- Quick Search (Posts, Pages, Tags, Categories)
- Quick Actions (New Post, New Page, Settings, Clear Cache)
- Keyboard Navigation
- Recent Searches

---

### Week 11: Advanced User Management

**Status:** 📋 Geplant
**Priorität:** 🟡 Medium
**Typ:** 🔧 Enhancement
**Version:** v1.1.0

#### RBAC Enhanced (4 Roles)

- **Administrator** - Full system access
- **Author** - Create/edit own posts, upload media
- **User** - View content, comment, manage profile (NEW)
- **Guest** - Read-only published content (NEW)

#### Permissions Matrix

```
| Feature              | Administrator | Author | User | Guest   |
|----------------------|---------------|--------|------|---------|
| System Settings      | ✅            | ❌     | ❌   | ❌      |
| User Management      | ✅            | ❌     | ❌   | ❌      |
| Plugin Management    | ✅            | ❌     | ❌   | ❌      |
| Create/Edit Posts    | ✅            | ✅*    | ❌   | ❌      |
| Delete Posts         | ✅            | ✅*    | ❌   | ❌      |
| Create/Edit Pages    | ✅            | ❌     | ❌   | ❌      |
| Upload Media         | ✅            | ✅     | ❌   | ❌      |
| Moderate Comments    | ✅            | ✅*    | ❌   | ❌      |
| Post Comments        | ✅            | ✅     | ✅   | ⚙️ **   |
| View Published       | ✅            | ✅     | ✅   | ✅      |
| View Drafts          | ✅            | ✅*    | ❌   | ❌      |

* Author = Own content only
** Guest comments configurable in Settings
```

#### Registration Mode

- Public Registration Toggle (Settings → Security Tab)
- Switch from Invite-Only to Public
- Email Verification (optional)

---

### Week 12: Advanced Media & Editor

**Status:** 📋 Geplant
**Priorität:** 🟡 Medium
**Typ:** 🔧 Enhancement
**Version:** v1.1.0

#### Advanced Media Library

- Additional File Types:
    - Videos (MP4, WebM, OGG)
    - Audio (MP3, WAV, OGG)
    - Documents (DOC, DOCX, XLS, XLSX)
    - Archives (ZIP, TAR, GZ)
- Advanced Image Processing:
    - Crop & Focal Point
    - Filters (Grayscale, Blur, Brightness)
    - Responsive Images (srcset)
    - Retina/HiDPI Support (2x, 3x)
    - EXIF Data Stripping (Privacy)
    - CDN Support (optional)
- Bulk Actions (Delete, Move, Download)

#### TipTap Editor (Advanced)

- Tables (Create, Edit rows/columns)
- Horizontal Rule
- Embeds (YouTube, Vimeo, Twitter)
- Markdown Shortcuts
- Slash Commands (/ menu)

#### Bulk Content Actions

- Bulk Select (Checkboxes)
- Bulk Publish/Unpublish
- Bulk Delete
- Bulk Move to Category/Tag
- Bulk Assign Author
- Bulk Export (CSV, JSON)
- Inline Editing

#### Custom Fields (Advanced)

- Select (Dropdown, Multi-select, Radio)
- URL
- Email
- Rich Text (TipTap instance)

---

### Week 12: **Matomo Analytics (CORE)**

**Status:** 📋 Geplant
**Priorität:** 🟠 High
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

#### Integration

- Matomo PHP SDK Integration
- Privacy-First Tracking:
    - Cookieless Tracking
    - IP Anonymization (GDPR)
    - DNT (Do Not Track) Support
    - Anonymous Tracking Mode
- Dashboard Widget:
    - Pageviews (Today, Week, Month)
    - Unique Visitors
    - Top Posts/Pages
    - Referrers
    - Browser/Device Stats
- Detailed Reports (Admin Panel):
    - Visitor Log
    - Geographic Location (Country-level)
    - Page Performance
- Privacy Controls (Settings → Privacy Tab):
    - Enable/Disable Analytics
    - Analytics Opt-Out (User Preference)
    - Data Retention Settings (6/12/24 months)
    - Cookie Consent Integration
- Performance:
    - Async Tracking (No page load impact)
    - Optional: Separate SQLite DB for analytics

---

### Week 13: Plugin System & Update Manager

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

#### Plugin System (CRITICAL)

- Event-Driven Architecture (Laravel Events)
- Plugin Discovery (Auto-Load from `/plugins/`)
- Plugin Structure:
    - plugin.json (Metadata)
    - PluginServiceProvider.php
    - routes.php, views/, assets/
- Plugin Activation/Deactivation UI
- Plugin Settings Pages
- Plugin Events (Laravel Events):
    - Content Save (Before/After)
    - User Login (Before/After)
    - Comment Posted (Before/After)
    - Page Render (Before/After)

#### Update Manager

- Check for Updates (GitHub API)
- Update Available Notification
- One-Click Update:
    - Download Update ZIP
    - Auto-Backup Before Update
    - Extract & Run Migrations
    - Clear Cache
    - Restore on Failure
- Update History Log
- Rollback to Previous Version

---

### Week 13: **Workflow System (CORE)**

**Status:** 📋 Geplant
**Priorität:** 🟠 High
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

- Content Status Workflow (Draft → Review → Published)
- Reviewer Assignment (1 reviewer per post)
- Approval/Rejection with Feedback
- Workflow History (Audit Trail)
- Email Notifications (Review Request, Approval, Rejection)

---

### Week 10-13: **Official Plugins Development (Parallel)**

**Status:** 📋 Geplant
**Priorität:** 🟡 Medium
**Typ:** 🔌 Plugin
**Version:** v1.1.0

#### Plugin 1: Newsletter Plugin (~6 features)

- Newsletter Editor (TipTap)
- Subscriber Management (Import/Export, Groups/Tags)
- Subscriber Sign-up Widget
- Campaign Scheduling (Send Now, Schedule)
- Email Templates (3 responsive templates)
- Privacy-Friendly Tracking (optional)
- Double Opt-In (GDPR)
- Subscriber Segmentation (Tags/Categories)

#### Plugin 2: Custom Fields Pro (~7 features)

- Relationship Fields (Posts/Pages/Users, Categories/Tags)
- Repeater Field (max 5 levels)
- Gallery Field
- JSON Field
- Color Picker
- Rich Text Field (TipTap, Full/Mini)
- File Field
- Field Conditional Logic

#### Plugin 3: Multi-Language Plugin (~8 features)

- Multi-Language Support (Unlimited)
- Content Translation UI (Side-by-Side)
- Translatable Fields (Post/Page, Categories/Tags, SEO)
- Locale Switcher (Dropdown, Flags, Auto-Detect)
- Language-Specific URLs (Prefix/Subdirectory)
- Fallback Language
- RTL Support (Arabic, Hebrew)
- SEO for Multi-Language (hreflang, Sitemap, RSS)

---

### v1.1.0 Success Criteria

**Status:** 📋 Geplant
**Version:** v1.1.0

- Comment System works with moderation
- Search finds Posts/Pages/Tags with filters
- Matomo Analytics tracks visits (Privacy-First)
- 4-role RBAC system works
- Bulk Actions functional
- Workflow System functional (Draft → Review → Published)
- Plugin System works (3 official plugins installable)
- Update Manager works (One-Click Update)
- ~149 Core Features + ~21 Plugin Features = 170 Total

---

## 🔧 **Version 1.2.0 - Professional Features**

**Duration:** +2-3 Weeks
**Goal:** Erweiterte CMS-Features + SEO Pro Plugin
**Core Features:** +10 (Total: ~159)
**Plugins:** +1 Official Plugin (Total: 4 plugins, ~24 features)

### Week 14: Import/Export Enhancements & Advanced SEO

**Status:** 📋 Geplant
**Priorität:** 🟡 Medium
**Typ:** 🔧 Enhancement
**Version:** v1.2.0
**Note:** Nice-to-have improvements

#### Import/Export Enhancements

**Note:** Basic Import/Export is in v1.0.0 (Week 9) - This section adds enhancements

- Import Error Handling & Retry
- Advanced Content Mapping (custom field mapping)
- Import from Medium (via RSS)
- Import from Substack
- Scheduled Exports (automatic weekly/monthly backups)

#### Advanced SEO

- Schema.org Structured Data (Article, Blog, Organization)
- Open Graph & Twitter Cards (Enhanced)
- XML Sitemap (Advanced with images, priorities)
- Custom Meta Tags per Post/Page

---

### Week 15: Advanced Features

**Status:** 📋 Geplant
**Priorität:** 🟡 Medium
**Typ:** 🔧 Enhancement
**Version:** v1.2.0
**Note:** Professional CMS improvements

#### Redirect Management

- 301/302 Redirect CRUD
- Bulk Import (CSV)
- Wildcard Redirects
- Redirect History

#### Menu Builder

- Drag & Drop Menu Creation
- Menu Locations (Header, Footer, Sidebar)
- Menu Items (Pages, Posts, Categories, Tags, Custom Links)
- Nested Items (Unlimited Depth)
- Menu Item Options (CSS Class, Target, Icon)

#### Widget System

- Widget Areas (Sidebar, Footer)
- Default Widgets (Recent Posts, Popular Posts, Categories, Tags, Custom HTML)
- Drag & Drop Widget Management

#### Custom Routing

- Multi-Language URL Prefixes (/en/, /de/)
- Hierarchical URLs (/blog/category/subcategory/post)
- Custom Post Type URLs
- Route Wildcards

---

### Week 14-15: **SEO Pro Plugin**

**Status:** 📋 Geplant
**Priorität:** 🟡 Medium
**Typ:** 🔌 Plugin
**Version:** v1.2.0

#### Plugin 4: SEO Pro Plugin (~3 features)

- Schema.org Markup (Article, Blog, Organization)
- Open Graph & Twitter Cards (Enhanced with Preview)
- Advanced Sitemap (Image, Video, News)
- Broken Link Checker (Email Alerts, Broken Links Report)

---

### v1.2.0 Success Criteria

**Status:** 📋 Geplant
**Version:** v1.2.0

- Import/Export works (WordPress, Ghost, Markdown)
- Advanced SEO features functional
- Redirect Management works
- Menu Builder creates navigation
- Widget System functional
- Custom Routing works
- All 4 Official Plugins installable & functional
- ~159 Core Features + ~24 Plugin Features = 183 Total

---

## 📦 **Release Preparation**

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical
**Version:** Post v1.2.0
**Timeline:** 1-2 Weeks

### Pre-Release Checklist

- Security Audit (Code Review)
- Performance Testing (Shared Hosting: < 1s page load)
- Cross-Browser Testing (Chrome, Firefox, Safari, Edge)
- Mobile Testing (iOS, Android)
- Documentation:
    - [ ] Installation Guide
    - [ ] User Guide
    - [ ] Plugin Development Guide
    - [ ] API Documentation
- Demo Site Setup
- Release Notes & Changelog
- MIT License File
- GitHub Repository Setup
- Community Guidelines (CONTRIBUTING.md, CODE_OF_CONDUCT.md)

### Release Artifacts

**Status:** 📋 Geplant

- pinecms-v1.0.0.zip (Core Only)
- pinecms-v1.1.0.zip (Core + Plugin System)
- pinecms-v1.2.0.zip (Core + All Features)
- Official Plugins (5 separate ZIPs)
- Installation Guide (README.md)
- User Documentation (docs.pinecms.org)
- Plugin Development Docs
- Demo Themes (3 Themes)
- Sample Content (Blog Posts, Pages)

---

## 🎯 **Future (v2.0+)**

**Status:** 📋 Geplant
**Typ:** 🧪 Experimental
**Version:** v2.0+

### Potential Features (Community-Driven)

- GraphQL API (as Premium Plugin or Core)
- Multi-Site Support (Network)
- E-Commerce Plugin (Stripe, PayPal)
- Advanced Analytics Plugin (Heatmaps, A/B Testing)
- Page Builder Plugin (Drag & Drop)
- Membership/Subscription Plugin
- Forum/Community Plugin
- Advanced Form Builder (Calculated Fields, Payments)
- Advanced SEO (Rank Math/Yoast level)
- Performance Plugin (Advanced Caching, CDN)

---

## 📊 **Success Metrics**

### Technical

- Loads on Shared Hosting (< 1 second)
- SQLite Database (< 50MB for 1000 posts)
- Flat-Files (< 100MB for 1000 posts)
- Page Load (< 2 seconds)
- Lighthouse Score (> 90/100)

### Features

- ~159 Core Features (v1.2.0)
- ~24 Plugin Features (4 official plugins)
- ~183 Total Features
- Zero Critical Bugs
- Full Mobile Responsiveness
- Advanced Features: Matomo Analytics, Plugin System, Import/Export

### Community

- GitHub Stars (Target: 500+ by end of year)
- Contributors (Target: 10+)
- Active Installations (Target: 100+)
- Plugin Ecosystem (Target: 20+ plugins by v2.0)
- Community Plugins (Target: 10+ by end of year)

---

## 🤝 **Contributing**

PineCMS ist 100% Open Source (MIT License). Community-Beiträge sind herzlich willkommen!

**How to Contribute:**

- ⭐ Star the repo on GitHub
- 🐛 Report bugs/feature requests
- 💻 Submit Pull Requests
- 🔌 Create plugins/themes
- 📝 Write documentation
- 🌍 Translate to other languages
- 💬 Help in Discussions/Forum

---

**Last Updated:** 2025-11-06
**Next Review:** End of v1.0.0 (Week 9)

**Changes in this version:**

- Completely restructured roadmap around **Core + Plugins** architecture
- Split into **3 versions** (v1.0.0, v1.1.0, v1.2.0)
- **Matomo Analytics** moved to Core (v1.1.0)
- **Workflow System** moved to Core (v1.1.0) - essential for team collaboration
- **4 Official Plugins** documented (all 100% Open Source & kostenlos)
- **Removed REST API** from roadmap (focus on Core CMS)
- Plugin System as core feature in v1.1.0
- Timeline optimized: 15-18 weeks (realistic estimation with all enhancements)
- Clear separation: Core (~159 features) + Plugins (~24 features) = ~183 total
- Software Engineering Improvements (2025-11-06):
    - Removed 4 complete plugins: Webhooks (~11), Two-Factor Auth (~5), Form Builder (~12) → v2.0
    - Workflow (~6) → Moved to Core v1.1.0 (essential for teams)
    - Plugin Feature Cleanup (YAGNI/KISS): Newsletter, Custom Fields Pro, Multi-Language, SEO Pro
    - Plugin reduction: 8 → 4 plugins, ~60 → ~24 features (60% reduction)
    - Grand Total: ~213 → ~183 features
