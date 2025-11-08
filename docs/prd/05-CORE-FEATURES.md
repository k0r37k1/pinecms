# 05 - Core Features (v1.0-1.2)

**Version:** 1.0.0
**Last Updated:** 2025-11-07
**Status:** 📋 Planned
**Owner:** PineCMS Team

---

## 1. Executive Summary

This document specifies all **159 core features** across PineCMS versions 1.0.0 (MVP), 1.1.0, and 1.2.0.

**Feature Breakdown:**

- **v1.0.0 MVP:** ~95 features (10-11 weeks) - Essential blogging CMS
- **v1.1.0:** +54 features (Total: ~149, +3-4 weeks) - Plugin System + Workflow
- **v1.2.0:** +10 features (Total: ~159, +2-3 weeks) - Advanced CMS features

**Core Principles:**

- ⚡ Performance First (< 1 second page load)
- 🔒 Privacy & Security by Default (NO default analytics/tracking)
- 🚀 Shared Hosting Compatible (PHP 8.3+, SQLite)
- 🎯 Essential Features Only (KISS/DRY/YAGNI)
- 🔌 Plugin-API for Extensions (v1.1.0+)

---

## 2. Version 1.0.0 - MVP Features (~95 Features)

**Timeline:** 10-11 Weeks
**Goal:** Lean, fast blogging CMS with migration path
**Priority:** 🔴 Critical (Blocker for v1.0.0 release)

---

### 2.1 Phase 0: Installer & Setup (Week 1-2)

**Web-Based Installer:**

- System Requirements Check (PHP 8.3+, extensions, permissions, disk space)
- Cron-Job Detection (optional, setup instructions if unavailable)
- Environment Setup (.env Generator with secure APP_KEY)
- SQLite Database Creation (auto-create database/database.sqlite)
- Admin User Setup (email, password, nickname)
- Post-Install Cleanup (remove /install/ directory)
- Apache .htaccess Generator (auto-generate for pretty URLs)
- nginx.conf.example (provide manual nginx setup guide)

**Acceptance Criteria:**

- Installation completion rate > 95%
- Time to install < 10 minutes
- Clear error messages for missing requirements

---

### 2.2 Phase 1: Content Management (Weeks 3-5)

#### Pages (CRUD)

- Create/Edit/Delete pages (static content: About, Contact, Privacy)
- Status: Published / Draft
- Auto-Save (30/60/custom seconds)
- Slug Management (auto-generate, custom, unique validation)
- SEO Meta (title, description, Open Graph, Twitter Cards)
- Flat-File Storage (Markdown + YAML front matter)
- Revisions System (auto-create on save, max 10 revisions, restore capability)

#### Posts (CRUD)

- Create/Edit/Delete posts (blog content)
- Status: Draft / Published
- Auto-Save (30 seconds default)
- Pin/Unpin Posts (sticky posts feature)
- Featured Posts (mark as featured, display on homepage)
- Template Selection (dropdown from theme.json, fallback to "default")
- Excerpt (manual or auto-generated from first 160 chars)
- Reading Time Calculation (auto-calculate ~200 words/min)
- Duplicate Post (copy with "(Copy)" suffix, status always "Draft")
- Content Scheduler:
    - Publish Date/Time (future scheduling, UTC storage)
    - Unpublish Date/Time (auto-offline after date)
    - Timezone Support (admin preference, display conversion)
    - Scheduler Modes: Visit-Triggered (default) or Traditional Cron (optional)
    - Manual Overrides: "Publish Now", "Unpublish Now", "Cancel Schedule"
- Tags & Categories (hierarchical categories, flat tags)
- Flat-File Storage (Markdown + YAML front matter)

#### TipTap Editor (Basic Features)

- Basic Formatting (Bold, Italic, Strike, Underline)
- Headings (H1-H6)
- Lists (Bullet, Numbered, Task Lists)
- Blockquote
- Code Block (Syntax Highlighting via Prism.js or Shiki)
- Inline Code
- Links (with preview)
- Images (Upload, URL, Drag & Drop)
- WYSIWYG ↔ Markdown Toggle
- Live Preview:
    - Split View (Side-by-Side)
    - Device Switcher (Desktop, Tablet, Mobile)
    - Full Preview Mode (new tab)

#### Custom Fields (Basic Types)

- Text (Short Text, Long Text)
- Number (Integer, Decimal)
- Boolean (Toggle, Checkbox)
- Date/Time (Date, Time, DateTime)

**Advanced field types in v1.1.0:** Select, URL, Email, Rich Text

#### Revisions System (Flat-File)

- Auto-Create Revision on Save (every 30 seconds)
- Revisions List (Date, User, Preview)
- Compare Revisions:
    - **v1.0.0:** Changes List (text-based summary)
    - **v1.1.0:** Side-by-side Diff-View (visual comparison)
- Restore to Any Revision
- Revision Author & Timestamp
- Revision Limits (max 10 per post, configurable: 5/10/20/50)
- Auto-Cleanup (delete old revisions beyond limit)
- Revision Storage: Separate .md files ({slug}.{timestamp}.md)

#### Concurrent Editing Protection (Optimistic Locking)

- lock_version INTEGER column (detect concurrent edits)
- Conflict Resolution UI (Keep My Version, Use Their Version, View Diff, Merge Manually)
- Auto-Save Integration (pause if lock_version changed)
- Real-Time Notification ("{User} is editing this post" badge, 30s polling)

---

### 2.3 Media Library (Weeks 4-5)

#### Media Library (Basic)

- Media Library UI (Grid/List view, pagination)
- Drag & Drop Upload
- Multi-File Upload (max 50MB batch)
- Upload Directory Structure (YYYY/MM)
- File Organization (Folders)
- Media Search & Filters (by filename, file type, upload date)
- Image Preview (Modal with metadata)
- Direct Link Copy (copy URL to clipboard)
- Storage Quota Display (total used / total available)

#### Upload Security (Multi-Layer Validation)

- **Layer 1:** File Extension Whitelist (.jpg, .jpeg, .png, .gif, .webp, .pdf)
- **Layer 2:** MIME Type Validation (finfo_file() check)
- **Layer 3:** File Content Validation
    - Images: Re-encode via Imagick (priority) or GD (fallback) to strip malware/metadata
    - PDFs: Validate via smalot/pdfparser (check JS, launch actions, v1.4-1.7 only)
- **Layer 4:** File Size Limits (10MB per file, 50MB batch)
- **Layer 5:** Filename Sanitization ({uuid}\_{timestamp}.{extension})

#### Upload Settings (Admin Configurable)

- Max File Size (default: 10MB, max: PHP upload_max_filesize)
- Allowed File Types (whitelist, admin-managed)
- Image Max Dimensions (default: 2560x2560px)
- Auto-optimization on upload (toggle, quality: Low/Medium/High)

#### Image Processing

- Auto-Resize (respect max dimensions, maintain aspect ratio)
- Thumbnail Generation (150px, 300px, 768px)
- EXIF Data Stripping (remove GPS, camera data - **PRIVACY FEATURE**)
- WebP Conversion (optional, with JPEG/PNG fallback)
- Image Compression (configurable quality: 70%/85%/95%)

#### Image Metadata

- Alt Text (required for accessibility/SEO, max 125 chars)
- Title & Caption (optional, max 100/500 chars)
- Focal Point Selection (for auto-crop, visual editor)
- Storage Quota Display (per-file size, total storage)

---

### 2.4 User Management (Week 5)

#### User System

- User CRUD (Create, Read, Update, Delete)
- User Profiles (Avatar, Bio, Social Links)
- Role-Based Access Control (RBAC):
    - **Capability-Based Permissions** (granular, Laravel Gates/Policies)
    - **Default Roles (v1.0):**
        - **Administrator** - All capabilities
        - **Author** - Create/edit own posts, upload media
        - **User** - Comment on posts, view published content
        - **Guest** - View published content only (unauthenticated)

**Permission Matrix (v1.0):**

| Capability       | Administrator | Author | User | Guest |
| ---------------- | ------------- | ------ | ---- | ----- |
| create-post      | ✓             | ✓      | ✗    | ✗     |
| edit-own-post    | ✓             | ✓      | ✗    | ✗     |
| edit-all-posts   | ✓             | ✗      | ✗    | ✗     |
| delete-own-post  | ✓             | ✓      | ✗    | ✗     |
| delete-all-posts | ✓             | ✗      | ✗    | ✗     |
| publish-post     | ✓             | ✓      | ✗    | ✗     |
| manage-users     | ✓             | ✗      | ✗    | ✗     |
| manage-settings  | ✓             | ✗      | ✗    | ✗     |
| view-published   | ✓             | ✓      | ✓    | ✓     |

**Notes:**

- "Own" = User created the resource (created_by column)
- "All" = Can modify any resource regardless of creator
- Guest comments configurable in Settings

#### Authentication

- Login / Logout
- Password Reset (email link, 60 min expiration)
- Remember Me (30-day session if checked)
- Invite-Only Registration (default, public disabled)
- Invite Token Generation & Email (unique, single-use, 7-day expiration)
- First Setup Wizard (Admin Creation on first install)
- Session Management (database driver, 2-hour lifetime)

#### Activity Tracking

- Activity Log (Admin Actions: create, update, delete)
- Authentication Log (Login History: IP, User Agent, success/fail)
- Failed Login Attempts (IP tracking, auto-delete after 7 days)

---

### 2.5 Theme System (Week 6)

#### Frontend (Public Site)

- Blade Templates + Alpine.js + Tailwind CSS 4
- 1 Default Theme (included: themes/default/)
- Theme Installation via SFTP (no ZIP upload in v1.0)
- Theme Switcher (Admin Panel, scans themes/ directory)
- Dark/Light Mode Toggle (User Preference, Cookie-Saved)
- Lucide Icons (Frontend)

#### Theme Structure

```
themes/
├── default/                    ← Included theme
│   ├── theme.json              ← Metadata (name, version, author, templates)
│   ├── layouts/
│   │   └── default.blade.php   ← Master layout
│   ├── templates/              ← Post/Page templates
│   │   ├── default.blade.php
│   │   ├── full-width.blade.php
│   │   └── sidebar.blade.php
│   ├── partials/
│   │   ├── header.blade.php
│   │   ├── footer.blade.php
│   │   └── sidebar.blade.php
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
```

#### theme.json Format

```json
{
    "name": "Default Theme",
    "slug": "default",
    "version": "1.0.0",
    "author": "PineCMS",
    "description": "Clean minimal theme",
    "templates": {
        "default": "Default (Featured Image + Content)",
        "full-width": "Full Width (No Sidebar)",
        "sidebar": "With Sidebar"
    }
}
```

#### Admin Panel

- PrimeVue + Inertia.js + Vue 3.5
- Dark/Light Mode Toggle (User Preference)
- PrimeIcons (Admin Only)
- Responsive Design (Mobile-Friendly)

---

### 2.6 Frontend Event System (Week 6)

**Architecture Decision:** Laravel Events (unified for Backend + Frontend)

#### Blade @event() Directive

- Custom Blade directive `@event('event-name')` for theme integration
- Triggers Laravel Events from Blade templates
- Output sanitization (XSS prevention via Blade escaping)
- Event existence check (silent failure if no listeners)

#### Pre-defined Event Points

- `theme.head.before` - Before closing `</head>` tag
- `theme.head.after` - After opening `<body>` tag
- `theme.body.before` - Before main content area
- `theme.body.after` - After main content area
- `theme.footer.before` - Before footer section
- `theme.footer.after` - Before closing `</body>` tag
- `theme.content.before` - Before post/page content
- `theme.content.after` - After post/page content
- `theme.sidebar.before` - Before sidebar widgets
- `theme.sidebar.after` - After sidebar widgets

#### Event Registration (Laravel Native)

Plugins register Event Listeners in `PluginServiceProvider`:

```php
protected $listen = [
    'theme.head.before' => [
        \MyPlugin\Listeners\InjectAnalytics::class,
    ],
];
```

**Note:** Users can integrate their own analytics (Matomo, Plausible, Fathom, etc.) via plugins or theme event listeners.

---

### 2.7 Categories & Tags System (Week 7)

#### Categories (Hierarchical)

- Hierarchical Structure (Parent → Child, max 2 levels)
- Category CRUD (Create, Read, Update, Delete)
- Category Slug (SEO-friendly URLs, auto-generated)
- Category Description (Plain Text, max 200 characters)
- Category Color/Badge (optional visual identifier)
- Category Post Count (display number of posts)
- Default Category (Auto-assign "Uncategorized" if none selected)
- Drag & Drop Reordering

#### Tags (Flat)

- Tag CRUD (Create, Read, Update, Delete)
- Tag Autocomplete (suggestions while typing)
- Tag Usage Count (how often used?)
- Popular Tags Widget
- Tag Merging (combine 2 tags into 1)
- Tag Cloud (Frontend Widget)

---

### 2.8 Admin Panel & Settings (Week 8)

#### Dashboard

- Quick Stats (Posts, Pages, Users)
- Recent Activity Feed
- Quick Actions (New Post, New Page)
- System Health (Storage, Cache Status)

#### Site Settings (Tabbed UI)

**General Tab:**

- Site Name, Tagline, Description
- Logo Upload
- Favicon Upload
- Timezone
- Date/Time Format

**SEO Tab:**

- Default Meta Title Template
- Default Meta Description
- OpenGraph Defaults
- Twitter Card Defaults
- Sitemap Settings
- Robots.txt Editor

**Privacy Tab:**

- Cookie Consent Banner (Enable/Disable, Customizable)
- GDPR Compliance Settings
- Field-Level Encryption (Enable/Disable via CipherSweet)
- **Analytics Integration:** None by default (users integrate via plugins/themes)

**Email Tab:**

- SMTP Configuration
- Email Templates (Plain text, editable in Settings):
    - Welcome Email (on user creation)
    - Invite Email (invitation token)
    - Password Reset Email
- Test Email Function

**Note:** Rich HTML Email Templates available in v1.1.0 or via Plugin

**Security Tab:**

- Registration Mode (Invite-Only default)
- Rate Limiting (Enable/Disable, Limits)
- Content Security Policy (CSP)
- Secure Headers (HSTS, X-Frame-Options)

**Backup Tab:**

- Auto-Backup Schedule (Daily/Weekly/Monthly)
- Scheduler Mode (Visit-Triggered or Traditional Cron)
- Backup Storage Location (Local only in v1.0)
- One-Click Backup
- Backup History & Restore
- Scheduler Status (last run, next run)

**Note:** S3/SFTP Backup available via "Backup Pro" Plugin (v1.2.0+)

**Backup Implementation:**

- Uses spatie/laravel-backup package
- Components: SQLite Database, Flat-Files (Markdown), Media Files
- Configuration Files (.env excluded for security)
- Automated scheduling (Visit-Triggered or Traditional Cron)

---

### 2.9 Import/Export System (Week 9)

#### Import (Critical for Adoption)

- Import from WordPress (XML/WXR format)
- Import from Ghost (JSON export)
- Import from Markdown Files (Bulk upload)
- Content Mapping UI (map fields during import)
- Import Preview (review before final import)
- Import Progress Indicator

#### Export

- Export to JSON (full site export)
- Export to Markdown (ZIP archive)
- Export Media Files (included in ZIP)
- Selective Export (choose posts/pages/categories)

---

### 2.10 SEO & Privacy (Week 10)

#### SEO Features

- Auto-Slug Generation (Eloquent Sluggable)
- Custom Slugs (Editable)
- XML Sitemap (Auto-Updated)
- RSS Feed (Posts, Categories, Tags)
- Meta Tags Management (OpenGraph, Twitter Cards)
- Canonical URLs

**Note:** Breadcrumbs are theme-specific (themes generate their own)

#### Privacy & Security

- **NO Default Analytics/Tracking** (privacy-first by design)
- Cookie Consent Banner (optional, disabled by default)
- Field-Level Encryption (Email, PII via CipherSweet)
- Content Security Policy (CSP Headers)
- Secure Headers (XSS Protection, HSTS)
- IP Anonymization (optional, for logs)
- CSRF Protection (Laravel Default)
- XSS Protection (Blade auto-escaping)
- SQL Injection Protection (Eloquent ORM)
- Rate Limiting (Forms, Login)

#### UI/UX Polish

- Custom Error Pages (404, 403, 500, 503)
- Loading States (Skeleton Loaders)
- Toast Notifications (Success, Error, Info, Warning)
- Modal System (Confirm Dialogs)
- Form Validation (Real-Time)
- Responsive Design (Mobile, Tablet, Desktop)

**Note:** Lightbox is theme-specific (not in core)

---

## 3. Version 1.1.0 (~54 Features)

**Timeline:** +3-4 Weeks
**Goal:** **Plugin System** + Workflow + Advanced Features
**Total Features:** ~149 (95 + 54)
**Priority:** 🟠 High

---

### 3.1 Comments System (Week 10-11)

- Comment CRUD
- Moderation Queue (Approve/Reject/Spam)
- Guest Comments Toggle (Enable/Disable in Settings, disabled by default)
- Nested Comments (Replies, Max Depth: 3)
- Comment Sorting (Newest, Oldest)
- Email Notifications (New Comment, Reply)
- Comment Count per Post
- Commenter Info (Name, Email, Website - Optional)
- Gravatar Support
- Markdown Support in Comments

**Removed (YAGNI):**

- ❌ Upvote/Downvote Comments → "Voting System" Community Plugin

---

### 3.2 Search System (Week 10-11)

- Full-Text Search Engine (TNTSearch default)
- Search Filters:
    - By Content Type (Posts, Pages)
    - By Date Range
    - By Author
    - By Tags/Categories
- Search Highlighting (Match preview)
- Search Suggestions (Did you mean...)

#### Command Palette (CMD+K / CTRL+K)

- Quick Search (Posts, Pages, Tags, Categories)
- Quick Actions (Create Post, Create Page, Navigate to Settings, Clear Cache)
- Keyboard Navigation
- Recent Searches

---

### 3.3 Advanced User Management (Week 11)

#### RBAC Enhanced (4 Roles)

- **Administrator** - Full system access
- **Author** - Create/edit own posts, upload media
- **User** - View content, comment, manage profile (NEW)
- **Guest** - Read-only access to published content (NEW)

#### Registration Mode

- Public Registration Toggle (Settings → Security Tab)
- Switch from Invite-Only to Public Registration
- Email Verification (optional for public registration)

---

### 3.4 Advanced Media Library (Week 12)

#### Additional File Types

- Videos (MP4, WebM, OGG)
- Audio (MP3, WAV, OGG)
- Documents (DOC, DOCX, XLS, XLSX)
- Archives (ZIP, TAR, GZ)

#### Advanced Image Processing

- Image Filters (Grayscale, Blur, Brightness)
- Responsive Images (srcset generation)
- Retina/HiDPI Support (2x, 3x)
- Image CDN Support (optional)

#### Media Usage Tracking

- Show where image/file is used (Posts, Pages)
- "Used in X posts" indicator
- Prevent deletion if in use (warning + override option)

#### Bulk Actions

- Bulk Upload (Multiple files at once)
- Bulk Delete
- Bulk Move
- Bulk Download

---

### 3.5 TipTap Editor (Advanced Features) (Week 12)

- Tables (Create, Edit, Delete rows/columns)
- Horizontal Rule
- Embed (YouTube, Vimeo, Twitter, etc.)
- Markdown Shortcuts
- Slash Commands (/ menu)

---

### 3.6 Bulk Actions (Week 12)

- Bulk Select (Checkboxes)
- Bulk Change Status (Draft ↔ Published)
- Bulk Delete
- Bulk Move to Category/Tag
- Bulk Assign Author
- Bulk Export (CSV, JSON)
- Duplicate Post/Page (copy with "(Copy)" suffix)
- Inline Editing (Title, Status, Date)

---

### 3.7 Custom Fields (Advanced Types) (Week 13)

- Select (Dropdown, Multi-select, Radio)
- URL
- Email
- Rich Text (TipTap instance for field)

---

### 3.8 Advanced Settings Tab (Week 13)

**Advanced Tab:**

- Cache Settings (Enable/Disable, Clear Cache)
- Maintenance Mode
- Debug Mode Toggle
- Custom Code (Head/Footer Injection)

---

### 3.9 Workflow System (Week 13) - **CORE FEATURE**

**Content Status Workflow:**

- Draft → Review → Published (3 states)
- Status badge in post list
- Filter by status

**Reviewer Assignment:**

- Assign 1 reviewer per post
- Email notification to reviewer
- Reviewer sees "Pending Review" queue

**Approval/Rejection:**

- Approve Button (auto-publish)
- Reject Button (back to draft with feedback)
- Request Changes (feedback required)

**Workflow History:**

- Status change log (User, Date, Decision)
- Review feedback/comments
- Audit trail

**Email Notifications:**

- Review Request → Reviewer
- Approval → Author
- Rejection → Author

**Why Core (not Plugin):**
Team collaboration is essential for multi-author blogs. Solo bloggers can ignore it.

---

### 3.10 Plugin System (Week 13) - **CRITICAL**

#### Plugin Architecture

- **Event-Driven Architecture (Laravel Events)** - NOT WordPress-style hooks
- Plugin Discovery (Auto-Load from `/plugins/`)
- Plugin Structure:
    ```
    plugins/
      my-plugin/
        plugin.json          # Metadata (name, version, author)
        PluginServiceProvider.php
        routes.php
        views/
        assets/
    ```

#### Plugin Management

- Plugin Activation/Deactivation (Admin Panel)
- Plugin Settings Page (Per Plugin)

#### Plugin Events (Laravel Event System)

- **Event Registration:** Plugin's `PluginServiceProvider` → `$listen` array
- **Priority System:** Listener execution order via array position (first = highest)
- **Event Payloads:** Each event object contains relevant data + mutable properties

**Available Events (v1.1):**

| Event             | Payload                    | When Dispatched               |
| ----------------- | -------------------------- | ----------------------------- |
| `PostSaving`      | `$post` (mutable)          | Before post saved to database |
| `PostSaved`       | `$post` (immutable)        | After post saved to database  |
| `PostDeleting`    | `$post` (mutable)          | Before post deleted           |
| `PostDeleted`     | `$post` (immutable)        | After post deleted            |
| `UserLoggedIn`    | `$user` (immutable)        | After successful login        |
| `UserLoggedOut`   | `$user` (immutable)        | After logout                  |
| `CommentCreating` | `$comment` (mutable)       | Before comment saved          |
| `CommentCreated`  | `$comment` (immutable)     | After comment created         |
| `PageRendering`   | `$page`, `$view` (mutable) | Before Blade render           |
| `PageRendered`    | `$page`, `$html` (mutable) | After Blade render            |

**Event Mutability:**

- Pre-action events (PostSaving, PageRendering): Can modify data before action
- Post-action events (PostSaved, PageRendered): Read-only, for logging/side effects

---

### 3.11 Update Manager (Week 13)

**Note:** Manual updates only in v1.0.0. This feature is v1.1.0.

- Check for Updates (GitHub API)
- Update Available Notification (Admin Dashboard)
- One-Click Update:
    - Download Update ZIP
    - Auto-Backup Before Update
    - Extract Update
    - Run Database Migrations
    - Clear Cache
    - Restore on Failure
- Manual Update Instructions
- Update History Log
- Rollback to Previous Version

---

## 4. Version 1.2.0 (~10 Features)

**Timeline:** +2-3 Weeks
**Goal:** Advanced CMS features
**Total Features:** ~159 (149 + 10)
**Priority:** 🟡 Medium

---

### 4.1 Import/Export Enhancements (Week 14)

**Note:** Basic Import/Export is in v1.0.0 (Week 9)

- Import Error Handling & Retry
- Advanced Content Mapping (custom field mapping)
- Import from Medium (via RSS)
- Import from Substack
- Scheduled Exports (automatic weekly/monthly backups)

---

### 4.2 Advanced SEO (Week 14)

- Schema.org Structured Data (Article, Blog, Organization)
- Open Graph & Twitter Cards (Enhanced)
- XML Sitemap (Advanced with images, priorities)
- Custom Meta Tags per Post/Page

---

### 4.3 Advanced Features (Week 15)

#### Redirect Management

- 301/302 Redirect Management
- Redirect CRUD (Admin Panel)
- Bulk Import (CSV)
- Wildcard Redirects
- Redirect History (Track Usage)

#### Menu Builder

- Drag & Drop Menu Creation
- Menu Locations (Header, Footer, Sidebar)
- Menu Items: Pages, Posts, Categories, Tags, Custom Links
- Nested Items (Unlimited Depth)
- Menu Item Options (CSS Class, Target, Icon)

#### Widget System

- Widget Areas (Sidebar, Footer)
- Default Widgets: Recent Posts, Popular Posts, Categories, Tags, Custom HTML
- Drag & Drop Widget Management

#### Custom Routing

- Multi-Language URL Prefixes (/en/, /de/)
- Hierarchical URLs (/blog/category/subcategory/post)
- Custom Post Type URLs
- Route Wildcards

---

## 5. System Features (All Versions)

### 5.1 Health Check Dashboard (v1.0.0+)

- Storage Usage (Media, Backups, Cache)
- File Permissions Check
- PHP Version & Extensions
- Database Status
- Cache Status
- Queue Status
- System Logs

### 5.2 Performance (v1.0.0+)

- Page Caching (Laravel Cache)
- Query Caching
- Asset Optimization (Vite Build)
- Image Lazy Loading
- CDN Support (Configurable)

### 5.3 Scheduler Implementation (v1.0.0)

#### Visit-Triggered (Default)

- Laravel Middleware on public routes
- Cache-based lock (60s TTL)
- Executes on page visits (if cache expired)
- Zero configuration needed

#### Traditional Cron (Optional)

- System cron: `* * * * * php artisan schedule:run`
- 0ms web overhead (runs externally)
- Requires cron-job access
- Setup instructions provided by installer

#### Scheduled Tasks

- Content Publishing/Unpublishing
- Automated Backups
- Cache & Session Cleanup

---

## 6. Deployment Features (v1.0.0)

### 6.1 Shared Hosting Compatibility

- Apache Support (.htaccess)
- nginx Support (nginx.conf.example)
- PHP 8.3+ Required
- SQLite Database (No MySQL Required)
- File-Based Sessions
- File-Based Cache
- No Git Required on Server
- FTP/SFTP Upload Support
- Scheduler: Visit-Triggered (default) or Traditional Cron (optional)

### 6.2 Release Package

- Pre-Compiled Assets (Vite Build)
- Optimized Autoloader
- Cached Config/Routes/Views
- Production-Ready .env.example
- Installation Guide (README.md)
- Changelog (CHANGELOG.md)

---

## 7. Core Feature Summary

| Version          | Features | Timeline    | Total Core Features |
| ---------------- | -------- | ----------- | ------------------- |
| **v1.0.0 (MVP)** | ~95      | 10-11 Weeks | 95                  |
| **v1.1.0**       | +54      | +3-4 Weeks  | 149                 |
| **v1.2.0**       | +10      | +2-3 Weeks  | 159                 |

**Total Development Time:** 15-18 Weeks for v1.2.0

**Key Improvements in v1.0.0:**

- ✅ Import/Export (WordPress, Ghost) - Critical for adoption
- ✅ Hierarchical Categories - Advanced content structure
- ✅ Enhanced Media Library (Alt Text, Focal Point, EXIF stripping)
- ✅ Advanced Live Preview (Device switcher, Full preview mode)
- ✅ Enhanced Content Scheduler (Unpublish date, Timezone support)
- ✅ Revision Management (Limits, Auto-cleanup)
- ✅ **NO Default Analytics/Tracking** - Privacy-first by design

**Key Improvements in v1.1.0:**

- ✅ **Plugin System (Week 13)** - Laravel Events-based architecture
- ✅ Workflow System - Team collaboration (Draft → Review → Published)
- ✅ Comments System - Community engagement
- ✅ Search System - Full-text search with filters
- ✅ Command Palette - Quick navigation (CMD+K / CTRL+K)
- ✅ Update Manager - One-click updates with auto-backup

---

## 8. What's NOT in Core?

The following features are available as **Official Plugins**:

1. **Newsletter Plugin** (~6 Features) - v1.1.0+
2. **Multi-Language Plugin** (~8 Features) - v1.1.0+
3. **SEO+ Plugin** (~3 Features) - v1.1.0+

**Total Official Plugin Features:** ~17 (optimized after KISS/DRY/YAGNI principles)

**Removed Plugins (→ v2.0 or Community):**

- ❌ Webhooks (~11) - Over-engineered, v2.0
- ❌ Two-Factor Auth (~5) - Core security sufficient, v2.0
- ❌ Form Builder (~12) - Too complex, v2.0
- ❌ Workflow (~6) - **Moved to Core v1.1.0** (essential for teams)
- ❌ **Matomo Analytics** - **REMOVED ENTIRELY** (users integrate own analytics via plugins/themes)

**Analytics Integration:**
Users can integrate their preferred analytics via:

- Community Plugins (Matomo, Plausible, Fathom, etc.)
- Theme Event Listeners (`theme.head.before` event)
- Custom Code Injection (Settings → Advanced → Custom Code)

➡️ See `docs/prd/06-PLUGIN-ECOSYSTEM.md` for Official Plugin details

---

## 9. Change History

| Date       | Version | Author       | Changes                                                                      |
| ---------- | ------- | ------------ | ---------------------------------------------------------------------------- |
| 2025-11-07 | 1.0     | PineCMS Team | Initial core features specification (159 features: v1.0-1.2, Matomo removed) |

---

**Last Updated:** 2025-11-07
**Document Owner:** PineCMS Team
**Next Review:** 2025-12-07
