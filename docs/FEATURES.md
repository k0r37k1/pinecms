# 📋 PineCMS - Complete Feature List

> **Last Updated:** 2025-01-25
> **Version:** 1.0.0-dev
> **Total Features:** ~163

---

## 🎯 **Phase 0: Installer & Core Setup**

**Timeline:** 1 Week
**Status:** Planned

### Web-Installer
- [ ] System Requirements Check (PHP 8.3+, Extensions, Permissions)
- [ ] Environment Setup (.env Generator)
- [ ] SQLite Database Creation
- [ ] Admin User Setup (Email, Password, Nickname, Role)
- [ ] Post-Install Cleanup (Remove /install/ directory)
- [ ] Apache .htaccess Generator
- [ ] nginx.conf.example

---

## 📝 **Phase 1: Content Management (MVP)**

**Timeline:** 6-8 Weeks
**Status:** Planned

### Pages
- [ ] CRUD (Create, Read, Update, Delete)
- [ ] Status: Published / Unpublished
- [ ] Auto-Save (30 Sekunden)
- [ ] TipTap Markdown Editor (WYSIWYG ↔ Markdown Toggle)
- [ ] Live Preview (Side-by-Side)
- [ ] Slug Management (Auto-Generate, Custom)
- [ ] SEO Meta (Title, Description, OpenGraph, Twitter Cards)
- [ ] Flat-File Storage (Markdown)
- [ ] Flat-File Revisions System

### Posts
- [ ] CRUD (Create, Read, Update, Delete)
- [ ] Status: Draft / Published
- [ ] Auto-Save (30 Sekunden)
- [ ] TipTap Markdown Editor (WYSIWYG ↔ Markdown)
- [ ] Live Preview
- [ ] Pin/Unpin Posts
- [ ] Featured Posts (Randomized Pool, Cached)
- [ ] Content Scheduler (Publish Date/Time)
- [ ] Tags & Categories
- [ ] Upvote/Downvote System
- [ ] Social Sharing Buttons
- [ ] Flat-File Storage (Markdown)
- [ ] Flat-File Revisions System

### Revisions System (Flat-File)
- [ ] Auto-Create Revision on Save
- [ ] Revisions List (Date, User, Preview)
- [ ] Compare Revisions (Diff-View)
- [ ] Restore to Any Revision
- [ ] Revision Cleanup (Keep Last N)

### Media Management
- [ ] Media Library UI
- [ ] Drag & Drop Upload
- [ ] Multi-File Upload
- [ ] Image Optimization (Auto-Resize, Thumbnails, WebP Conversion)
- [ ] File Organization (Folders)
- [ ] Media Search & Filter
- [ ] Media Insertion in TipTap
- [ ] File Size Limits (Configurable)
- [ ] Allowed File Types (Configurable)

---

## 👥 **Phase 1: User Management**

### User CRUD
- [ ] Create, Read, Update, Delete Users
- [ ] User Profiles (Avatar, Bio, Social Links)
- [ ] Role-Based Access Control (RBAC)
- [ ] Default Roles (4 Built-in Roles):
  - **Administrator** - Full system access, user management, settings, plugins
  - **Author** - Create/edit/delete own posts, upload media, manage own comments
  - **User** - View content, comment, manage own profile
  - **Guest** - Read-only access to published content (no login required)
- [ ] Permissions Matrix:
  ```
  | Feature              | Administrator | Author | User | Guest |
  |----------------------|---------------|--------|------|-------|
  | System Settings      | ✅            | ❌     | ❌   | ❌    |
  | User Management      | ✅            | ❌     | ❌   | ❌    |
  | Plugin Management    | ✅            | ❌     | ❌   | ❌    |
  | Create/Edit Posts    | ✅            | ✅*    | ❌   | ❌    |
  | Delete Posts         | ✅            | ✅*    | ❌   | ❌    |
  | Create/Edit Pages    | ✅            | ❌     | ❌   | ❌    |
  | Upload Media         | ✅            | ✅     | ❌   | ❌    |
  | Moderate Comments    | ✅            | ✅*    | ❌   | ❌    |
  | Post Comments        | ✅            | ✅     | ✅   | ❌    |
  | View Published       | ✅            | ✅     | ✅   | ✅    |
  | View Drafts          | ✅            | ✅*    | ❌   | ❌    |

  * Author = Own content only
  ```
- [ ] Custom Roles & Permissions (extend default roles)
- [ ] User Status (Active, Banned, Pending)

### Authentication
- [ ] Login / Logout
- [ ] Password Reset
- [ ] Remember Me
- [ ] Registration Mode Toggle (Invite-Only by default, switchable to Public Registration in Settings)
- [ ] Invite Token Generation & Email (for Invite-Only mode)
- [ ] First Setup Wizard (Admin Creation)
- [ ] Session Management

### Activity Tracking
- [ ] Activity Log (Admin Actions)
- [ ] Authentication Log (Login History, IP, User Agent)
- [ ] Failed Login Attempts

---

## 🎨 **Phase 1: Theme System**

### Frontend (Public Site)
- [ ] Blade Templates + Alpine.js + Tailwind CSS
- [ ] Standalone Themes (ZIP Upload)
- [ ] Theme Switcher (Admin Panel)
- [ ] Dark/Light Mode Toggle (User Preference, Cookie-Saved)
- [ ] Theme Customizer (Colors, Fonts - Phase 2)
- [ ] Lucide Icons (Frontend Only)

### Admin Panel
- [ ] PrimeVue + Inertia.js + Vue 3.5
- [ ] Dark/Light Mode Toggle (User Preference)
- [ ] PrimeIcons (Admin Only)
- [ ] Responsive Design (Mobile-Friendly)

---

## ⚙️ **Phase 1: Admin Panel**

### Dashboard
- [ ] Quick Stats (Posts, Pages, Comments, Users)
- [ ] Recent Activity Feed
- [ ] Quick Actions (New Post, New Page)
- [ ] System Health (Storage, Cache Status)

### Site Settings (Tabbed UI)

#### General Tab
- [ ] Site Name, Tagline, Description
- [ ] Logo Upload
- [ ] Favicon Upload
- [ ] Timezone
- [ ] Date/Time Format

#### SEO Tab
- [ ] Default Meta Title Template
- [ ] Default Meta Description
- [ ] OpenGraph Defaults
- [ ] Twitter Card Defaults
- [ ] Sitemap Settings
- [ ] Robots.txt Editor

#### Social Tab
- [ ] Social Media Links (Twitter, Facebook, LinkedIn, GitHub)
- [ ] Social Sharing Buttons (Enable/Disable per Platform)

#### Privacy Tab
- [ ] Cookie Consent Banner (Enable/Disable, Text Customization)
- [ ] Analytics Opt-Out
- [ ] GDPR Compliance Settings
- [ ] Field-Level Encryption (Enable/Disable)

#### Email Tab
- [ ] SMTP Configuration
- [ ] Email Templates (Welcome, Invite, Notification)
- [ ] Test Email Function

#### Security Tab
- [ ] Registration Mode (Invite-Only by default / Public Registration toggle)
- [ ] Rate Limiting (Enable/Disable, Limits)
- [ ] Content Security Policy (CSP)
- [ ] Secure Headers (HSTS, X-Frame-Options)

#### Backup Tab
- [ ] Auto-Backup Schedule (Daily/Weekly/Monthly)
- [ ] Backup Storage Location (Local/S3/SFTP)
- [ ] One-Click Backup
- [ ] Backup History & Restore

#### Advanced Tab
- [ ] Cache Settings (Enable/Disable, Clear Cache)
- [ ] Maintenance Mode
- [ ] Debug Mode Toggle
- [ ] Custom Code (Head/Footer Injection)

---

## 🔒 **Phase 1: Privacy & Compliance**

### Privacy Features
- [ ] Cookie Consent Banner (Customizable in Settings)
- [ ] Field-Level Encryption (Email, PII via CipherSweet)
- [ ] Privacy-First Analytics (Matomo Self-Hosted - Phase 2)
- [ ] GDPR-Compliant Data Exports
- [ ] Content Security Policy (CSP Headers)
- [ ] Secure Headers (XSS Protection, HSTS, etc.)
- [ ] IP Anonymization

---

## 🔍 **Phase 1: SEO & Navigation**

### SEO Features
- [ ] Auto-Slug Generation (Eloquent Sluggable)
- [ ] Custom Slugs (Editable)
- [ ] Breadcrumbs (Auto-Generated)
- [ ] XML Sitemap (Auto-Updated)
- [ ] RSS Feed (Posts, Categories, Tags)
- [ ] Meta Tags Management (OpenGraph, Twitter Cards)
- [ ] Canonical URLs
- [ ] Structured Data (Schema.org - Phase 2)

### Navigation
- [ ] Go-to-Top Button (Smooth Scroll)
- [ ] Breadcrumbs Component

---

## 💾 **Phase 1: Backup & Security**

### Backup System
- [ ] Automated Backups (spatie/laravel-backup)
- [ ] Backup Components:
  - SQLite Database Export
  - Flat-Files (Markdown Content)
  - Media Files
  - Configuration Files (.env excluded)
- [ ] Backup Scheduler (Daily/Weekly/Monthly)
- [ ] Backup Storage (Local, S3, SFTP)
- [ ] One-Click Backup (Admin Panel)
- [ ] Backup History List
- [ ] One-Click Restore
- [ ] Auto-Backup Before Updates

### Security
- [ ] CSRF Protection (Laravel Default)
- [ ] XSS Protection
- [ ] SQL Injection Protection (Eloquent ORM)
- [ ] Rate Limiting (API, Forms, Login)
- [ ] Failed Login Throttling

---

## 🎨 **Phase 1: UI/UX Features**

### User Interface
- [ ] Custom Error Pages (404, 403, 500, 503)
- [ ] Loading States (Skeleton Loaders)
- [ ] Toast Notifications (Success, Error, Info, Warning)
- [ ] Modal System (Confirm Dialogs, Lightbox)
- [ ] Form Validation (Real-Time)
- [ ] Responsive Design (Mobile, Tablet, Desktop)

---

## 💬 **Phase 2: Comment System**

**Timeline:** 3-4 Weeks
**Status:** Planned

### Comments
- [ ] Comment CRUD
- [ ] Moderation Queue (Approve/Reject/Spam)
- [ ] Upvote/Downvote Comments
- [ ] Nested Comments (Replies, Max Depth: 3)
- [ ] Comment Sorting (Newest, Oldest, Most Upvoted)
- [ ] Spam Detection (Akismet Integration - Optional)
- [ ] Email Notifications (New Comment, Reply)
- [ ] Comment Count per Post
- [ ] Commenter Info (Name, Email, Website - Optional)
- [ ] Gravatar Support
- [ ] Markdown Support in Comments

---

## 🔎 **Phase 2: Search & Navigation**

### Search
- [ ] Full-Text Search (Laravel Scout + TNTSearch)
- [ ] Search Posts by Title, Content, Tags
- [ ] Search Pages by Title, Content
- [ ] Search Users (Admin Only)
- [ ] Search Highlighting

### Command Palette (CMD+K / CTRL+K)
- [ ] Quick Search (Posts, Pages, Tags, Categories)
- [ ] Quick Actions:
  - Create New Post
  - Create New Page
  - Navigate to Settings
  - Navigate to Profile
  - Archive/Export Content
  - Clear Cache
- [ ] Keyboard Navigation
- [ ] Recent Searches

---

## 🌍 **Phase 2: Internationalization (i18n)**

### Multi-Language
- [ ] Language Support (DE/EN, Extensible)
- [ ] Content Translation (spatie/laravel-translatable)
- [ ] Locale Switcher (Frontend + Admin)
- [ ] Translation UI (Admin Panel)
- [ ] Translatable Fields:
  - Post Title, Content
  - Page Title, Content
  - Categories, Tags
  - Site Settings

---

## 📊 **Phase 2: Analytics & Social**

### Analytics
- [ ] Matomo Integration (Self-Hosted)
- [ ] Privacy-First Analytics (No Cookies, IP Anonymization)
- [ ] Dashboard Widget (Pageviews, Visitors, Top Posts)
- [ ] Detailed Reports (Admin Panel)
- [ ] Analytics Opt-Out (User Preference)

### Social Features
- [ ] Social Sharing Buttons (Configurable):
  - Twitter/X
  - Facebook
  - LinkedIn
  - WhatsApp
  - Email
  - Copy Link
- [ ] Open Graph Tags (Auto-Generated)
- [ ] Twitter Card Tags (Auto-Generated)

---

## 🔔 **Phase 2: Notifications**

### Notification System
- [ ] In-App Notifications (Bell Icon, Unread Count)
- [ ] Email Notifications (Configurable per User)
- [ ] Notification Types:
  - Comment on Your Post
  - Reply to Your Comment
  - Post Needs Review (Workflow)
  - Mentioned in Comment (@username)
  - New User Registration (Admin Alert)
  - System Alerts (Backup Failed, Update Available)
  - Upvote on Your Post/Comment
- [ ] Mark as Read/Unread
- [ ] Clear All Notifications

---

## 🚀 **Phase 3: Content Workflow**

**Timeline:** 4-6 Weeks
**Status:** Planned

### Workflow System
- [ ] Content Status: Draft → Review → Published
- [ ] Reviewer Assignment
- [ ] Approval/Rejection with Comments
- [ ] Workflow History (Who Reviewed, When, Decision)
- [ ] Email Notifications (Review Request, Approval, Rejection)

---

## 🔄 **Phase 3: Update Manager**

### Update System
- [ ] Check for Updates (GitHub API)
- [ ] Update Available Notification (Admin Dashboard)
- [ ] One-Click Update:
  - Download Update ZIP
  - Backup Current Installation
  - Extract Update
  - Run Database Migrations
  - Clear Cache
  - Restore on Failure
- [ ] Manual Update Instructions
- [ ] Update History Log
- [ ] Rollback to Previous Version

---

## 🔌 **Phase 3: Plugin System**

### Plugin Architecture
- [ ] Event-Driven Architecture (Laravel Events)
- [ ] Plugin Discovery (Auto-Load from `/plugins/`)
- [ ] Plugin Structure:
  ```
  plugins/
    my-plugin/
      plugin.json          # Metadata
      PluginServiceProvider.php
      routes.php
      views/
      assets/
  ```
- [ ] Plugin Activation/Deactivation (Admin Panel)
- [ ] Plugin Settings Page (Per Plugin)
- [ ] Plugin Update System
- [ ] Plugin Hooks:
  - Content Save (Before/After)
  - User Login (Before/After)
  - Comment Posted (Before/After)
  - Page Render (Before/After)

---

## 🔧 **Phase 3: Advanced Features**

### Menu Builder
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

### Redirect Management
- [ ] 301/302 Redirect Management
- [ ] Redirect CRUD (Admin Panel)
- [ ] Bulk Import (CSV)
- [ ] Wildcard Redirects
- [ ] Redirect History (Track Usage)

### Two-Factor Authentication (2FA)
- [ ] 2FA Setup (Google Authenticator, Authy)
- [ ] QR Code Generation
- [ ] Backup Codes
- [ ] 2FA Enforcement (Per Role - e.g., mandatory for Administrator, optional for Author/User)
- [ ] Recovery Options

---

## 🎯 **System Features (All Phases)**

### Health Check Dashboard
- [ ] Storage Usage (Media, Backups, Cache)
- [ ] File Permissions Check
- [ ] PHP Version & Extensions
- [ ] Database Status
- [ ] Cache Status
- [ ] Queue Status
- [ ] Failed Jobs
- [ ] System Logs

### Performance
- [ ] Page Caching (Laravel Cache)
- [ ] Query Caching
- [ ] Asset Optimization (Vite Build)
- [ ] Image Lazy Loading
- [ ] CDN Support (Configurable)

---

## 📦 **Deployment Features**

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

## 📊 **Feature Count Summary**

| Phase | Features | Timeline | Status |
|-------|----------|----------|--------|
| Phase 0 | 8 | 1 Week | Planned |
| Phase 1 | ~80 | 6-8 Weeks | Planned |
| Phase 2 | ~40 | 3-4 Weeks | Planned |
| Phase 3 | ~35 | 4-6 Weeks | Planned |
| **Total** | **~163** | **14-19 Weeks** | - |

---

## 🗺️ **Development Roadmap**

### Milestone 1: Installer (Week 1)
- Complete Phase 0
- System Requirements Check
- Database Setup
- First Admin User

### Milestone 2: Core CMS (Weeks 2-9)
- Content Management (Pages, Posts)
- Media Library
- User Management
- Admin Panel
- Theme System
- Backup System

### Milestone 3: Social Features (Weeks 10-13)
- Comment System
- Search
- Analytics
- i18n
- Notifications

### Milestone 4: Advanced (Weeks 14-19)
- Content Workflow
- Update Manager
- Plugin System
- Menu Builder
- 2FA

---

**Last Updated:** 2025-01-25
**Maintained By:** PineCMS Team
**License:** MIT
