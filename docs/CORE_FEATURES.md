# 📋 PineCMS - Core Features

> **Last Updated:** 2025-01-26
> **Architecture:** Core CMS + Official Plugins
> **License:** MIT - 100% Open Source

---

## 🎯 Core Philosophy

**PineCMS Core** ist schlank, schnell und für Shared Hosting optimiert. Alle Features die 90%+ der Nutzer brauchen sind im Core enthalten. Spezialisierte Features werden als **Official Plugins** bereitgestellt.

**Core Prinzipien:**

- ⚡ Performance First (< 1 Sekunde Ladezeit)
- 🔒 Privacy & Security by Default
- 🚀 Shared Hosting Compatible (PHP 8.3+, SQLite)
- 🎯 Essential Features Only
- 🔌 Plugin-API für Erweiterungen

---

## 📦 Version 1.0.0 - Core CMS (MVP)

**Timeline:** 8-10 Wochen
**Goal:** Schlankes, schnelles Blogging-CMS
**Features:** ~80

### Phase 0: Installer & Setup (Week 1)

#### Web-Installer

- [ ] System Requirements Check (PHP 8.3+, Extensions, Permissions)
- [ ] Environment Setup (.env Generator)
- [ ] SQLite Database Creation
- [ ] Admin User Setup (Email, Password, Nickname)
- [ ] Post-Install Cleanup (Remove /install/ directory)
- [ ] Apache .htaccess Generator
- [ ] nginx.conf.example

---

### Phase 1: Content Management (Weeks 2-5)

#### Pages

- [ ] CRUD (Create, Read, Update, Delete)
- [ ] Status: Published / Unpublished
- [ ] Auto-Save (30 seconds)
- [ ] Slug Management (Auto-Generate, Custom)
- [ ] SEO Meta (Title, Description, OpenGraph, Twitter Cards)
- [ ] Flat-File Storage (Markdown)
- [ ] Flat-File Revisions System

#### Posts

- [ ] CRUD (Create, Read, Update, Delete)
- [ ] Status: Draft / Published
- [ ] Auto-Save (30 seconds)
- [ ] Pin/Unpin Posts
- [ ] Featured Posts (Randomized Pool, Cached)
- [ ] Content Scheduler (Publish Date/Time)
- [ ] Tags & Categories
- [ ] Upvote/Downvote System
- [ ] Social Sharing Buttons
- [ ] Flat-File Storage (Markdown)

#### TipTap Editor (Basic Features)

- [ ] Basic Formatting (Bold, Italic, Strike, Underline)
- [ ] Headings (H1-H6)
- [ ] Lists (Bullet, Numbered, Task Lists)
- [ ] Blockquote
- [ ] Code Block (Syntax Highlighting)
- [ ] Inline Code
- [ ] Links (with preview)
- [ ] Images (Upload, URL, Drag & Drop)
- [ ] WYSIWYG ↔ Markdown Toggle
- [ ] Live Preview (Side-by-Side)

#### Custom Fields (Basic Types)

- [ ] Text (Short Text, Long Text)
- [ ] Number (Integer, Decimal)
- [ ] Boolean (Toggle, Checkbox)
- [ ] Date/Time (Date, Time, DateTime)

#### Revisions System (Flat-File)

- [ ] Auto-Create Revision on Save (Every 30 seconds)
- [ ] Revisions List (Date, User, Preview)
- [ ] Compare Revisions (Side-by-side Diff-View)
- [ ] Restore to Any Revision
- [ ] Revision Author & Timestamp

#### Media Library (Basic)

- [ ] Media Library UI
- [ ] Drag & Drop Upload
- [ ] Multi-File Upload
- [ ] Supported File Types:
  - Images (JPEG, PNG, GIF, WebP)
  - Documents (PDF)
- [ ] Image Processing:
  - Auto-Resize (Max width/height)
  - Thumbnail Generation
  - WebP Conversion (with fallback)
  - Image Compression
- [ ] File Organization (Folders)
- [ ] Media Search & Filters
- [ ] Image Preview & Lightbox
- [ ] Direct Link Copy
- [ ] Storage Quota Display

---

### Phase 1: User Management (Week 6)

#### User System

- [ ] User CRUD (Create, Read, Update, Delete)
- [ ] User Profiles (Avatar, Bio, Social Links)
- [ ] Role-Based Access Control (RBAC)
- [ ] 2 Default Roles (v1.0):
  - **Administrator** - Full system access
  - **Author** - Create/edit own posts, upload media

#### Authentication

- [ ] Login / Logout
- [ ] Password Reset
- [ ] Remember Me
- [ ] Invite-Only Registration (default)
- [ ] Invite Token Generation & Email
- [ ] First Setup Wizard (Admin Creation)
- [ ] Session Management

#### Activity Tracking

- [ ] Activity Log (Admin Actions)
- [ ] Authentication Log (Login History, IP, User Agent)
- [ ] Failed Login Attempts

---

### Phase 1: Theme System (Week 7)

#### Frontend (Public Site)

- [ ] Blade Templates + Alpine.js + Tailwind CSS 4
- [ ] 1 Default Theme (included)
- [ ] Standalone Themes (ZIP Upload)
- [ ] Theme Switcher (Admin Panel)
- [ ] Dark/Light Mode Toggle (User Preference, Cookie-Saved)
- [ ] Lucide Icons (Frontend)

#### Admin Panel

- [ ] PrimeVue + Inertia.js + Vue 3.5
- [ ] Dark/Light Mode Toggle (User Preference)
- [ ] PrimeIcons (Admin Only)
- [ ] Responsive Design (Mobile-Friendly)

---

### Phase 1: Admin Panel & Settings (Week 8)

#### Dashboard

- [ ] Quick Stats (Posts, Pages, Users)
- [ ] Recent Activity Feed
- [ ] Quick Actions (New Post, New Page)
- [ ] System Health (Storage, Cache Status)

#### Site Settings (Tabbed UI)

**General Tab:**

- [ ] Site Name, Tagline, Description
- [ ] Logo Upload
- [ ] Favicon Upload
- [ ] Timezone
- [ ] Date/Time Format

**SEO Tab:**

- [ ] Default Meta Title Template
- [ ] Default Meta Description
- [ ] OpenGraph Defaults
- [ ] Twitter Card Defaults
- [ ] Sitemap Settings
- [ ] Robots.txt Editor

**Privacy Tab:**

- [ ] Cookie Consent Banner (Enable/Disable, Customizable)
- [ ] GDPR Compliance Settings
- [ ] Field-Level Encryption (Enable/Disable via CipherSweet)

**Email Tab:**

- [ ] SMTP Configuration
- [ ] Email Templates (Welcome, Invite, Notification)
- [ ] Test Email Function

**Security Tab:**

- [ ] Registration Mode (Invite-Only default)
- [ ] Rate Limiting (Enable/Disable, Limits)
- [ ] Content Security Policy (CSP)
- [ ] Secure Headers (HSTS, X-Frame-Options)

**Backup Tab:**

- [ ] Auto-Backup Schedule (Daily/Weekly/Monthly)
- [ ] Backup Storage Location (Local/S3/SFTP)
- [ ] One-Click Backup
- [ ] Backup History & Restore

---

### Phase 1: SEO & Privacy (Week 9)

#### SEO Features

- [ ] Auto-Slug Generation (Eloquent Sluggable)
- [ ] Custom Slugs (Editable)
- [ ] Breadcrumbs (Auto-Generated)
- [ ] XML Sitemap (Auto-Updated)
- [ ] RSS Feed (Posts, Categories, Tags)
- [ ] Meta Tags Management (OpenGraph, Twitter Cards)
- [ ] Canonical URLs

#### Privacy & Security

- [ ] Cookie Consent Banner
- [ ] Field-Level Encryption (Email, PII via CipherSweet)
- [ ] Content Security Policy (CSP Headers)
- [ ] Secure Headers (XSS Protection, HSTS)
- [ ] IP Anonymization
- [ ] CSRF Protection (Laravel Default)
- [ ] XSS Protection
- [ ] SQL Injection Protection (Eloquent ORM)
- [ ] Rate Limiting (Forms, Login)

#### Backup System

- [ ] Automated Backups (spatie/laravel-backup)
- [ ] Backup Components:
  - SQLite Database Export
  - Flat-Files (Markdown Content)
  - Media Files
  - Configuration Files (.env excluded)
- [ ] Backup Scheduler (Daily/Weekly/Monthly)
- [ ] One-Click Backup (Admin Panel)
- [ ] One-Click Restore

#### UI/UX Polish

- [ ] Custom Error Pages (404, 403, 500, 503)
- [ ] Loading States (Skeleton Loaders)
- [ ] Toast Notifications (Success, Error, Info, Warning)
- [ ] Modal System (Confirm Dialogs, Lightbox)
- [ ] Form Validation (Real-Time)
- [ ] Responsive Design (Mobile, Tablet, Desktop)

---

## 📦 Version 1.1.0 - Enhanced Core

**Timeline:** +3-4 Wochen
**Goal:** Professionelle Features + Plugin-System + Analytics
**Features:** ~48 zusätzlich (Total: ~128)

### Comments System (Week 10-11)

- [ ] Comment CRUD
- [ ] Moderation Queue (Approve/Reject/Spam)
- [ ] Guest Comments Toggle (Enable/Disable in Settings, disabled by default)
- [ ] Upvote/Downvote Comments
- [ ] Nested Comments (Replies, Max Depth: 3)
- [ ] Comment Sorting (Newest, Oldest, Most Upvoted)
- [ ] Email Notifications (New Comment, Reply)
- [ ] Comment Count per Post
- [ ] Commenter Info (Name, Email, Website - Optional)
- [ ] Gravatar Support
- [ ] Markdown Support in Comments

---

### Search System (Week 10-11)

- [ ] Full-Text Search Engine (TNTSearch default)
- [ ] Search Filters:
  - By Content Type (Posts, Pages)
  - By Date Range
  - By Author
  - By Tags/Categories
- [ ] Search Highlighting (Match preview)
- [ ] Search Suggestions (Did you mean...)

#### Command Palette (CMD+K / CTRL+K)

- [ ] Quick Search (Posts, Pages, Tags, Categories)
- [ ] Quick Actions:
  - Create New Post
  - Create New Page
  - Navigate to Settings
  - Navigate to Profile
  - Clear Cache
- [ ] Keyboard Navigation
- [ ] Recent Searches

---

### Advanced User Management (Week 11)

#### RBAC Enhanced (4 Roles)

- [ ] **Administrator** - Full system access
- [ ] **Author** - Create/edit own posts, upload media
- [ ] **User** - View content, comment, manage profile (NEW)
- [ ] **Guest** - Read-only access to published content (NEW)

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
** Guest comments can be enabled/disabled in Settings
```

#### Registration Mode

- [ ] Public Registration Toggle (Settings → Security Tab)
- [ ] Switch from Invite-Only to Public Registration
- [ ] Email Verification (optional for public registration)

---

### Advanced Media Library (Week 12)

- [ ] Additional File Types:
  - Videos (MP4, WebM, OGG)
  - Audio (MP3, WAV, OGG)
  - Documents (DOC, DOCX, XLS, XLSX)
  - Archives (ZIP, TAR, GZ)

- [ ] Advanced Image Processing:
  - Crop & Focal Point
  - Image Filters (Grayscale, Blur, Brightness)
  - Responsive Images (srcset generation)
  - Retina/HiDPI Support (2x, 3x)
  - EXIF Data Stripping (Privacy)
  - Image CDN Support (optional)

- [ ] Bulk Actions:
  - Bulk Upload (Multiple files at once)
  - Bulk Delete
  - Bulk Move
  - Bulk Download

---

### TipTap Editor (Advanced Features) (Week 12)

- [ ] Tables (Create, Edit, Delete rows/columns)
- [ ] Horizontal Rule
- [ ] Embed (YouTube, Vimeo, Twitter, etc.)
- [ ] Markdown Shortcuts
- [ ] Slash Commands (/ menu)

---

### Bulk Actions (Week 12)

- [ ] Bulk Select (Checkboxes)
- [ ] Bulk Publish/Unpublish
- [ ] Bulk Delete
- [ ] Bulk Move to Category/Tag
- [ ] Bulk Assign Author
- [ ] Bulk Export (CSV, JSON)
- [ ] Inline Editing (Title, Status, Date)

---

### Analytics (Matomo Integration) - **CORE FEATURE** (Week 12)

- [ ] Matomo Integration (Self-Hosted, PHP SDK)
- [ ] Privacy-First Analytics:
  - No Cookies Required (Cookieless Tracking)
  - IP Anonymization (GDPR-compliant)
  - DNT (Do Not Track) Support
  - Anonymous Tracking Mode
- [ ] Dashboard Widget (Admin Panel):
  - Pageviews (Today, Week, Month)
  - Unique Visitors
  - Top Posts/Pages
  - Referrers (Where visitors come from)
  - Browser/Device Stats
- [ ] Detailed Reports (Admin Panel):
  - Visitor Log
  - Geographic Location (Country-level only)
  - Page Performance
- [ ] Privacy Controls (Settings → Privacy Tab):
  - Enable/Disable Analytics
  - Analytics Opt-Out (User Preference)
  - Data Retention Settings (GDPR: 6/12/24 months)
  - Cookie Consent Integration
- [ ] Performance:
  - Async Tracking (No impact on page load)
  - Optional: Separate SQLite DB for analytics

---

### Custom Fields (Advanced Types) (Week 13)

- [ ] Select (Dropdown, Multi-select, Radio)
- [ ] URL
- [ ] Email
- [ ] Rich Text (TipTap instance for field)

---

### Advanced Settings Tab (Week 13)

**Advanced Tab:**

- [ ] Cache Settings (Enable/Disable, Clear Cache)
- [ ] Maintenance Mode
- [ ] Debug Mode Toggle
- [ ] Custom Code (Head/Footer Injection)

---

### Plugin System (Week 13) - **CRITICAL**

#### Plugin Architecture

- [ ] Event-Driven Architecture (Laravel Events)
- [ ] Plugin Discovery (Auto-Load from `/plugins/`)
- [ ] Plugin Structure:

  ```
  plugins/
    my-plugin/
      plugin.json          # Metadata (name, version, author)
      PluginServiceProvider.php
      routes.php
      views/
      assets/
  ```

- [ ] Plugin Activation/Deactivation (Admin Panel)
- [ ] Plugin Settings Page (Per Plugin)
- [ ] Plugin Hooks/Events:
  - Content Save (Before/After)
  - User Login (Before/After)
  - Comment Posted (Before/After)
  - Page Render (Before/After)

---

### Update Manager (Week 13)

- [ ] Check for Updates (GitHub API)
- [ ] Update Available Notification (Admin Dashboard)
- [ ] One-Click Update:
  - Download Update ZIP
  - Auto-Backup Before Update
  - Extract Update
  - Run Database Migrations
  - Clear Cache
  - Restore on Failure
- [ ] Manual Update Instructions
- [ ] Update History Log
- [ ] Rollback to Previous Version

---

## 📦 Version 1.2.0 - Professional Features

**Timeline:** +2-3 Wochen
**Goal:** Erweiterte CMS-Features
**Features:** ~15 zusätzlich (Total: ~143)

### Import/Export System (Week 14)

- [ ] Import from WordPress (XML)
- [ ] Import from Ghost (JSON)
- [ ] Import from Markdown Files (Bulk)
- [ ] Export to JSON
- [ ] Export to Markdown (ZIP)
- [ ] Full Site Backup/Restore
- [ ] Content Mapping UI (field matching)

---

### Advanced SEO (Week 14)

- [ ] Schema.org Structured Data (Article, Blog, Organization)
- [ ] Open Graph & Twitter Cards (Enhanced)
- [ ] XML Sitemap (Advanced with images, priorities)
- [ ] Custom Meta Tags per Post/Page

---

### Advanced Features (Week 15)

#### Redirect Management

- [ ] 301/302 Redirect Management
- [ ] Redirect CRUD (Admin Panel)
- [ ] Bulk Import (CSV)
- [ ] Wildcard Redirects
- [ ] Redirect History (Track Usage)

#### Menu Builder

- [ ] Drag & Drop Menu Creation
- [ ] Menu Locations (Header, Footer, Sidebar)
- [ ] Menu Items:
  - Pages
  - Posts
  - Categories
  - Tags
  - Custom Links
  - Nested Items (Unlimited Depth)
- [ ] Menu Item Options (CSS Class, Target, Icon)

#### Widget System

- [ ] Widget Areas (Sidebar, Footer)
- [ ] Default Widgets:
  - Recent Posts
  - Popular Posts
  - Categories
  - Tags
  - Custom HTML
- [ ] Drag & Drop Widget Management

#### Custom Routing

- [ ] Multi-Language URL Prefixes (/en/, /de/)
- [ ] Hierarchical URLs (/blog/category/subcategory/post)
- [ ] Custom Post Type URLs
- [ ] Route Wildcards

---

## 🎯 System Features (All Versions)

### Health Check Dashboard

- [ ] Storage Usage (Media, Backups, Cache)
- [ ] File Permissions Check
- [ ] PHP Version & Extensions
- [ ] Database Status
- [ ] Cache Status
- [ ] Queue Status
- [ ] System Logs

### Performance

- [ ] Page Caching (Laravel Cache)
- [ ] Query Caching
- [ ] Asset Optimization (Vite Build)
- [ ] Image Lazy Loading
- [ ] CDN Support (Configurable)

---

## 📦 Deployment Features

### Shared Hosting Compatibility

- [ ] Apache Support (.htaccess)
- [ ] nginx Support (nginx.conf.example)
- [ ] PHP 8.3+ Required
- [ ] SQLite Database (No MySQL Required)
- [ ] File-Based Sessions
- [ ] File-Based Cache
- [ ] No Git Required on Server
- [ ] FTP/SFTP Upload Support

### Release Package

- [ ] Pre-Compiled Assets (Vite Build)
- [ ] Optimized Autoloader
- [ ] Cached Config/Routes/Views
- [ ] Production-Ready .env.example
- [ ] Installation Guide (README.md)
- [ ] Changelog (CHANGELOG.md)

---

## 📊 Core Feature Summary

| Version | Features | Timeline | Total Core Features |
|---------|----------|----------|---------------------|
| **v1.0.0 (MVP)** | ~80 | 8-10 Wochen | 80 |
| **v1.1.0 (Enhanced)** | +48 | +3-4 Wochen | 128 |
| **v1.2.0 (Professional)** | +15 | +2-3 Wochen | 143 |

**Total Development Time:** 13-17 Wochen für v1.2.0

---

## 🔌 What's NOT in Core?

Die folgenden Features sind als **Official Plugins** verfügbar:

1. **Newsletter Plugin** - Newsletter-System mit Subscriber Management
2. **Webhooks Plugin** - Webhook-System für Integrationen
3. **Custom Fields Pro Plugin** - Advanced Field Types (Repeater, Gallery, JSON, etc.)
4. **Multi-Language Plugin** - Content Translation UI (erweitert)
5. **Workflow Plugin** - Draft → Review → Publish Workflow
6. **Two-Factor Auth Plugin** - 2FA mit Google Authenticator
7. **Form Builder Plugin** - Drag & Drop Formular-Builder
8. **SEO Pro Plugin** - Advanced SEO (AMP, Broken Link Checker)

➡️ Siehe `docs/OFFICIAL_PLUGINS.md` für Details

---

**Last Updated:** 2025-01-26
**Maintained By:** PineCMS Team
**License:** MIT - 100% Open Source
