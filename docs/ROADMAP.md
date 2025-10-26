# 🗺️ PineCMS Development Roadmap

> **Project:** PineCMS - Security & Privacy-First Flat-File Hybrid CMS
> **Target:** Shared Hosting Deployment (No Git Required)
> **License:** MIT - 100% Open Source
> **Architecture:** Core CMS + Official Plugins

---

## 📅 **Timeline Overview**

**Core Development:** 13-17 Weeks (3-4 Months)
**Plugin Development:** Parallel to Core (weeks 10-17)
**Target Release:** Q2 2025 (v1.0.0), Q3 2025 (v1.2.0)

### Feature Counts

| Version | Core Features | Official Plugins | Total |
|---------|--------------|------------------|-------|
| **v1.0.0 (MVP)** | ~80 | - | 80 |
| **v1.1.0 (Enhanced)** | ~128 | 5 Plugins (~50 features) | 178 |
| **v1.2.0 (Professional)** | ~143 | 8 Plugins (~75 features) | 218 |

---

## 🎯 **Version 1.0.0 - Core CMS (MVP)**

**Duration:** 8-10 Weeks
**Goal:** Schlankes, schnelles Blogging-CMS für Shared Hosting
**Features:** ~80

### Week 1: Installer & Foundation

#### Deliverables

- ✅ Web-based Installer UI
- ✅ System Requirements Check (PHP 8.3+, Extensions)
- ✅ Automatic .env File Generation
- ✅ SQLite Database Creation
- ✅ First Admin User Setup Wizard
- ✅ Apache .htaccess Configuration
- ✅ nginx.conf.example

#### Success Criteria

- [ ] User can upload ZIP to shared hosting
- [ ] User can complete installation via browser
- [ ] Admin can login to dashboard
- [ ] No Git required on server

---

### Week 2-5: Content Management

#### Week 2-3: Core Content

- [ ] Database Schema (Pages, Posts, Tags, Categories)
- [ ] Flat-File Storage Structure (Markdown)
- [ ] Revisions System (Flat-File based with Auto-Save)
- [ ] Basic CRUD for Pages
- [ ] Basic CRUD for Posts
- [ ] Basic Custom Fields (Text, Number, Date, Boolean)

#### Week 3-4: TipTap Editor (Basic)

- [ ] Basic Formatting (Bold, Italic, Strike, Underline)
- [ ] Headings (H1-H6), Lists (Bullet, Numbered, Task)
- [ ] Code Blocks (Syntax Highlighting)
- [ ] Links with Preview
- [ ] Images (Upload, URL, Drag & Drop)
- [ ] WYSIWYG ↔ Markdown Toggle
- [ ] Live Preview (Side-by-Side)
- [ ] Auto-Save (30 seconds)

#### Week 4-5: Media Library (Basic)

- [ ] Media Library UI
- [ ] Drag & Drop Upload
- [ ] Supported Types (Images: JPEG, PNG, GIF, WebP; Documents: PDF)
- [ ] Image Processing:
  - Auto-Resize
  - Thumbnail Generation
  - WebP Conversion
  - Compression
- [ ] File Organization (Folders)
- [ ] Media Search & Filters

---

### Week 6: User Management

- [ ] User CRUD
- [ ] User Profiles (Avatar, Bio, Social Links)
- [ ] 2 Roles (v1.0):
  - **Administrator** (Full system access)
  - **Author** (Create/edit own posts, upload media)
- [ ] Authentication:
  - Login/Logout
  - Password Reset
  - Remember Me
  - Invite-Only Registration (default)
- [ ] Activity Log (Admin Actions)
- [ ] Authentication Log (Login History)

---

### Week 7: Theme System

#### Frontend (Public Site)

- [ ] Blade Templates + Alpine.js + Tailwind CSS 4
- [ ] 1 Default Theme (included)
- [ ] Theme Switcher UI (Admin Panel)
- [ ] Dark/Light Mode Toggle (User Preference)
- [ ] Lucide Icons

#### Admin Panel

- [ ] PrimeVue + Inertia.js + Vue 3.5
- [ ] Dark/Light Mode Toggle
- [ ] PrimeIcons
- [ ] Responsive Design (Mobile-Friendly)

---

### Week 8: Admin Panel & Settings

#### Dashboard

- [ ] Quick Stats (Posts, Pages, Users)
- [ ] Recent Activity Feed
- [ ] Quick Actions (New Post, New Page)
- [ ] System Health (Storage, Cache)

#### Site Settings (Tabbed UI)

- [ ] **General Tab:** Site Name, Logo, Favicon, Timezone
- [ ] **SEO Tab:** Meta Templates, Sitemap, Robots.txt
- [ ] **Privacy Tab:** Cookie Consent, Field-Level Encryption
- [ ] **Email Tab:** SMTP Config, Test Email
- [ ] **Security Tab:** Rate Limiting, CSP, Secure Headers
- [ ] **Backup Tab:** Auto-Backup Schedule, One-Click Backup/Restore

---

### Week 9: SEO, Privacy & Polish

#### SEO Features

- [ ] Auto-Slug Generation
- [ ] Breadcrumbs
- [ ] XML Sitemap (Auto-Updated)
- [ ] RSS Feed (Posts, Categories, Tags)
- [ ] Meta Tags (OpenGraph, Twitter Cards)
- [ ] Canonical URLs

#### Privacy & Security

- [ ] Cookie Consent Banner
- [ ] Field-Level Encryption (CipherSweet)
- [ ] CSRF Protection
- [ ] XSS Protection
- [ ] Rate Limiting (Forms, Login)

#### Backup System

- [ ] Automated Backups (Database, Flat-Files, Media)
- [ ] Backup Scheduler (Daily/Weekly/Monthly)
- [ ] One-Click Backup
- [ ] One-Click Restore

#### UI/UX Polish

- [ ] Custom Error Pages (404, 403, 500, 503)
- [ ] Loading States (Skeleton Loaders)
- [ ] Toast Notifications
- [ ] Modal System
- [ ] Form Validation (Real-Time)
- [ ] Responsive Design (Mobile, Tablet, Desktop)

---

### v1.0.0 Success Criteria

- [ ] User can create/edit/delete Posts & Pages
- [ ] TipTap Editor works with basic features
- [ ] Media Library functional with basic image processing
- [ ] 2-role system works (Administrator, Author)
- [ ] Theme system works (1 default theme included)
- [ ] One-Click Backup/Restore works
- [ ] Installation completes in < 5 minutes on shared hosting
- [ ] Page load < 1 second on shared hosting
- [ ] ~80 Core Features Implemented

---

## 🚀 **Version 1.1.0 - Enhanced Core + Plugin System**

**Duration:** +3-4 Weeks
**Goal:** Professionelle Features + Plugin-System + Analytics
**Core Features:** +48 (Total: ~128)
**Plugins:** 5 Official Plugins (~50 features)

### Week 10-11: Comments & Search

#### Comments System

- [ ] Comment CRUD
- [ ] Moderation Queue (Approve/Reject/Spam)
- [ ] Guest Comments Toggle (disabled by default)
- [ ] Upvote/Downvote Comments
- [ ] Nested Comments (Max Depth: 3)
- [ ] Comment Sorting
- [ ] Email Notifications
- [ ] Gravatar Support
- [ ] Markdown Support in Comments

#### Search System

- [ ] Full-Text Search (TNTSearch default)
- [ ] Search Filters (by type, date, author, tags/categories)
- [ ] Search Highlighting
- [ ] Search Suggestions

#### Command Palette (CMD+K)

- [ ] Quick Search (Posts, Pages, Tags, Categories)
- [ ] Quick Actions (New Post, New Page, Settings, Clear Cache)
- [ ] Keyboard Navigation
- [ ] Recent Searches

---

### Week 11: Advanced User Management

#### RBAC Enhanced (4 Roles)

- [ ] **Administrator** - Full system access
- [ ] **Author** - Create/edit own posts, upload media
- [ ] **User** - View content, comment, manage profile (NEW)
- [ ] **Guest** - Read-only published content (NEW)

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

- [ ] Public Registration Toggle (Settings → Security Tab)
- [ ] Switch from Invite-Only to Public
- [ ] Email Verification (optional)

---

### Week 12: Advanced Media & Editor

#### Advanced Media Library

- [ ] Additional File Types:
  - Videos (MP4, WebM, OGG)
  - Audio (MP3, WAV, OGG)
  - Documents (DOC, DOCX, XLS, XLSX)
  - Archives (ZIP, TAR, GZ)
- [ ] Advanced Image Processing:
  - Crop & Focal Point
  - Filters (Grayscale, Blur, Brightness)
  - Responsive Images (srcset)
  - Retina/HiDPI Support (2x, 3x)
  - EXIF Data Stripping (Privacy)
  - CDN Support (optional)
- [ ] Bulk Actions (Delete, Move, Download)

#### TipTap Editor (Advanced)

- [ ] Tables (Create, Edit rows/columns)
- [ ] Horizontal Rule
- [ ] Embeds (YouTube, Vimeo, Twitter)
- [ ] Markdown Shortcuts
- [ ] Slash Commands (/ menu)

#### Bulk Content Actions

- [ ] Bulk Select (Checkboxes)
- [ ] Bulk Publish/Unpublish
- [ ] Bulk Delete
- [ ] Bulk Move to Category/Tag
- [ ] Bulk Assign Author
- [ ] Bulk Export (CSV, JSON)
- [ ] Inline Editing

#### Custom Fields (Advanced)

- [ ] Select (Dropdown, Multi-select, Radio)
- [ ] URL
- [ ] Email
- [ ] Rich Text (TipTap instance)

---

### Week 12: **Matomo Analytics (CORE)**

#### Integration

- [ ] Matomo PHP SDK Integration
- [ ] Privacy-First Tracking:
  - Cookieless Tracking
  - IP Anonymization (GDPR)
  - DNT (Do Not Track) Support
  - Anonymous Tracking Mode
- [ ] Dashboard Widget:
  - Pageviews (Today, Week, Month)
  - Unique Visitors
  - Top Posts/Pages
  - Referrers
  - Browser/Device Stats
- [ ] Detailed Reports (Admin Panel):
  - Visitor Log
  - Geographic Location (Country-level)
  - Page Performance
- [ ] Privacy Controls (Settings → Privacy Tab):
  - Enable/Disable Analytics
  - Analytics Opt-Out (User Preference)
  - Data Retention Settings (6/12/24 months)
  - Cookie Consent Integration
- [ ] Performance:
  - Async Tracking (No page load impact)
  - Optional: Separate SQLite DB for analytics

---

### Week 13: Plugin System & Update Manager

#### Plugin System (CRITICAL)

- [ ] Event-Driven Architecture (Laravel Events)
- [ ] Plugin Discovery (Auto-Load from `/plugins/`)
- [ ] Plugin Structure:
  - plugin.json (Metadata)
  - PluginServiceProvider.php
  - routes.php, views/, assets/
- [ ] Plugin Activation/Deactivation UI
- [ ] Plugin Settings Pages
- [ ] Plugin Hooks/Events:
  - Content Save (Before/After)
  - User Login (Before/After)
  - Comment Posted (Before/After)
  - Page Render (Before/After)

#### Update Manager

- [ ] Check for Updates (GitHub API)
- [ ] Update Available Notification
- [ ] One-Click Update:
  - Download Update ZIP
  - Auto-Backup Before Update
  - Extract & Run Migrations
  - Clear Cache
  - Restore on Failure
- [ ] Update History Log
- [ ] Rollback to Previous Version

---

### Week 10-13: **Official Plugins Development (Parallel)**

#### Plugin 1: Newsletter Plugin (~10 features)

- [ ] Newsletter Editor
- [ ] Subscriber Management
- [ ] Campaign Scheduling
- [ ] Templates
- [ ] Privacy-Friendly Tracking (optional)
- [ ] Unsubscribe Management
- [ ] Double Opt-In (GDPR)
- [ ] Subscriber Segmentation
- [ ] A/B Testing
- [ ] Newsletter Archive

#### Plugin 2: Webhooks Plugin (~11 features)

- [ ] Webhook Management UI
- [ ] 8 Event Types (post.published, user.created, etc.)
- [ ] Custom Headers & Payloads
- [ ] Testing & Logs
- [ ] Retry Logic
- [ ] Signing
- [ ] Rate Limiting

#### Plugin 3: Custom Fields Pro (~8 features)

- [ ] Relationship Fields
- [ ] Repeater Field
- [ ] Gallery Field
- [ ] JSON Field
- [ ] Color Picker
- [ ] Rich Text Field
- [ ] File Field
- [ ] WYSIWYG Field

#### Plugin 4: Multi-Language Plugin (~10 features)

- [ ] Multi-Language Support (Unlimited)
- [ ] Content Translation UI
- [ ] Translatable Fields
- [ ] Locale Switcher
- [ ] Language-Specific URLs
- [ ] Fallback Language
- [ ] Translation Memory
- [ ] SEO for Multi-Language
- [ ] Auto-Translation API (optional)

#### Plugin 5: Workflow Plugin (~8 features)

- [ ] Content Status (Draft → Review → Published)
- [ ] Reviewer Assignment
- [ ] Approval/Rejection
- [ ] Review Comments
- [ ] Workflow History
- [ ] Email Notifications
- [ ] Workflow Dashboard
- [ ] Custom Workflow States (optional)

---

### v1.1.0 Success Criteria

- [ ] Comment System works with moderation
- [ ] Search finds Posts/Pages/Tags with filters
- [ ] Matomo Analytics tracks visits (Privacy-First)
- [ ] 4-role RBAC system works
- [ ] Bulk Actions functional
- [ ] Plugin System works (5 official plugins installable)
- [ ] Update Manager works (One-Click Update)
- [ ] ~128 Core Features + ~50 Plugin Features = 178 Total

---

## 🔧 **Version 1.2.0 - Professional Features**

**Duration:** +2-3 Weeks
**Goal:** Erweiterte CMS-Features + Additional Plugins
**Core Features:** +15 (Total: ~143)
**Plugins:** +3 Official Plugins (Total: 8 plugins, ~75 features)

### Week 14: Import/Export & Advanced SEO

#### Import/Export System

- [ ] Import from WordPress (XML)
- [ ] Import from Ghost (JSON)
- [ ] Import from Markdown Files (Bulk)
- [ ] Export to JSON
- [ ] Export to Markdown (ZIP)
- [ ] Full Site Backup/Restore
- [ ] Content Mapping UI

#### Advanced SEO

- [ ] Schema.org Structured Data (Article, Blog, Organization)
- [ ] Open Graph & Twitter Cards (Enhanced)
- [ ] XML Sitemap (Advanced with images, priorities)
- [ ] Custom Meta Tags per Post/Page

---

### Week 15: Advanced Features

#### Redirect Management

- [ ] 301/302 Redirect CRUD
- [ ] Bulk Import (CSV)
- [ ] Wildcard Redirects
- [ ] Redirect History

#### Menu Builder

- [ ] Drag & Drop Menu Creation
- [ ] Menu Locations (Header, Footer, Sidebar)
- [ ] Menu Items (Pages, Posts, Categories, Tags, Custom Links)
- [ ] Nested Items (Unlimited Depth)
- [ ] Menu Item Options (CSS Class, Target, Icon)

#### Widget System

- [ ] Widget Areas (Sidebar, Footer)
- [ ] Default Widgets (Recent Posts, Popular Posts, Categories, Tags, Custom HTML)
- [ ] Drag & Drop Widget Management

#### Custom Routing

- [ ] Multi-Language URL Prefixes (/en/, /de/)
- [ ] Hierarchical URLs (/blog/category/subcategory/post)
- [ ] Custom Post Type URLs
- [ ] Route Wildcards

---

### Week 14-15: **Additional Official Plugins**

#### Plugin 6: Two-Factor Auth Plugin (~5 features)

- [ ] 2FA Setup (QR Code, Manual Secret)
- [ ] TOTP Support (Google Authenticator, Authy, etc.)
- [ ] Backup Codes
- [ ] Recovery Options
- [ ] Per-User Enable/Disable

#### Plugin 7: Form Builder Plugin (~15 features)

- [ ] Drag & Drop Form Builder
- [ ] 12 Field Types
- [ ] Validation Rules
- [ ] Submissions Management
- [ ] Email Notifications
- [ ] Export Submissions (CSV, JSON)
- [ ] Anti-Spam (Honeypot, reCAPTCHA, hCaptcha)
- [ ] Conditional Logic (optional)
- [ ] Multi-Step Forms (optional)

#### Plugin 8: SEO Pro Plugin (~8 features)

- [ ] Schema.org Markup (Advanced)
- [ ] Open Graph & Twitter Cards (Enhanced with Preview)
- [ ] AMP Support
- [ ] Advanced Sitemap (Image, Video, News)
- [ ] Redirect Management (Enhanced)
- [ ] Broken Link Checker
- [ ] Canonical URL Management
- [ ] SEO Analysis per Post/Page

---

### v1.2.0 Success Criteria

- [ ] Import/Export works (WordPress, Ghost, Markdown)
- [ ] Advanced SEO features functional
- [ ] Redirect Management works
- [ ] Menu Builder creates navigation
- [ ] Widget System functional
- [ ] Custom Routing works
- [ ] All 8 Official Plugins installable & functional
- [ ] ~143 Core Features + ~75 Plugin Features = 218 Total

---

## 📦 **Release Preparation**

**Post v1.2.0:** 1-2 Weeks

### Pre-Release Checklist

- [ ] Security Audit (Code Review)
- [ ] Performance Testing (Shared Hosting: < 1s page load)
- [ ] Cross-Browser Testing (Chrome, Firefox, Safari, Edge)
- [ ] Mobile Testing (iOS, Android)
- [ ] Documentation:
  - [ ] Installation Guide
  - [ ] User Guide
  - [ ] Plugin Development Guide
  - [ ] API Documentation
- [ ] Demo Site Setup
- [ ] Release Notes & Changelog
- [ ] MIT License File
- [ ] GitHub Repository Setup
- [ ] Community Guidelines (CONTRIBUTING.md, CODE_OF_CONDUCT.md)

### Release Artifacts

- [ ] pinecms-v1.0.0.zip (Core Only)
- [ ] pinecms-v1.1.0.zip (Core + Plugin System)
- [ ] pinecms-v1.2.0.zip (Core + All Features)
- [ ] Official Plugins (8 separate ZIPs)
- [ ] Installation Guide (README.md)
- [ ] User Documentation (docs.pinecms.org)
- [ ] Plugin Development Docs
- [ ] Demo Themes (3 Themes)
- [ ] Sample Content (Blog Posts, Pages)

---

## 🎯 **Future (v2.0+)**

### Potential Features (Community-Driven)

- [ ] GraphQL API (as Premium Plugin or Core)
- [ ] Multi-Site Support (Network)
- [ ] E-Commerce Plugin (Stripe, PayPal)
- [ ] Advanced Analytics Plugin (Heatmaps, A/B Testing)
- [ ] Page Builder Plugin (Drag & Drop)
- [ ] Membership/Subscription Plugin
- [ ] Forum/Community Plugin
- [ ] Advanced Form Builder (Calculated Fields, Payments)
- [ ] Advanced SEO (Rank Math/Yoast level)
- [ ] Performance Plugin (Advanced Caching, CDN)

---

## 📊 **Success Metrics**

### Technical

- [ ] Loads on Shared Hosting (< 1 second)
- [ ] SQLite Database (< 50MB for 1000 posts)
- [ ] Flat-Files (< 100MB for 1000 posts)
- [ ] Page Load (< 2 seconds)
- [ ] Lighthouse Score (> 90/100)

### Features

- [ ] ~143 Core Features (v1.2.0)
- [ ] ~75 Plugin Features (8 official plugins)
- [ ] ~218 Total Features
- [ ] Zero Critical Bugs
- [ ] Full Mobile Responsiveness
- [ ] Advanced Features: Matomo Analytics, Plugin System, Import/Export

### Community

- [ ] GitHub Stars (Target: 500+ by end of year)
- [ ] Contributors (Target: 10+)
- [ ] Active Installations (Target: 100+)
- [ ] Plugin Ecosystem (Target: 20+ plugins by v2.0)
- [ ] Community Plugins (Target: 10+ by end of year)

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

**Last Updated:** 2025-01-26
**Next Review:** End of v1.0.0 (Week 9)

**Changes in this version:**

- Completely restructured roadmap around **Core + Plugins** architecture
- Split into **3 versions** (v1.0.0, v1.1.0, v1.2.0)
- **Matomo Analytics** moved to Core (v1.1.0)
- **8 Official Plugins** documented (all 100% Open Source & kostenlos)
- **Removed REST API** from roadmap (focus on Core CMS)
- Plugin System as core feature in v1.1.0
- Timeline optimized: 13-17 weeks (faster than previous 14-19 weeks)
- Clear separation: Core (~143 features) + Plugins (~75 features) = ~218 total
