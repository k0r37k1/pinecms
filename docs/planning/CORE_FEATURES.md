# 📋 PineCMS - Core Features

> **Last Updated:** 2025-01-26 **Architecture:** Core CMS + Official Plugins
> **License:** MIT - 100% Open Source

---

## 🎯 Core Philosophy

**PineCMS Core** ist schlank, schnell und für Shared Hosting optimiert. Alle
Features die 90%+ der Nutzer brauchen sind im Core enthalten. Spezialisierte
Features werden als **Official Plugins** bereitgestellt.

**Core Prinzipien:**

- ⚡ Performance First (< 1 Sekunde Ladezeit)
- 🔒 Privacy & Security by Default
- 🚀 Shared Hosting Compatible (PHP 8.3+, SQLite)
- 🎯 Essential Features Only
- 🔌 Plugin-API für Erweiterungen

---

## 📦 Version 1.0.0 - Core CMS (MVP)

**Timeline:** 10-11 Wochen **Goal:** Schlankes, schnelles Blogging-CMS mit
Migration-Path **Features:** ~95

### Phase 0: Installer & Setup (Week 1-2)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### Web-Installer

- System Requirements Check (PHP 8.3+, Extensions, Permissions)
- Cron-Job Detection (optional, shows setup instructions if available)
- Environment Setup (.env Generator)
- SQLite Database Creation
- Admin User Setup (Email, Password, Nickname)
- Post-Install Cleanup (Remove /install/ directory)
- Apache .htaccess Generator
- nginx.conf.example

---

### Phase 1: Content Management (Weeks 3-5)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### Pages

- CRUD (Create, Read, Update, Delete)
- Status: Published / Unpublished
- Auto-Save (30/60/custom seconds)
- Slug Management (Auto-Generate, Custom)
- SEO Meta (Title, Description, OpenGraph, Twitter Cards)
- Flat-File Storage (Markdown)
- Flat-File Revisions System

#### Posts

- CRUD (Create, Read, Update, Delete)
- Status: Draft / Published
- Auto-Save (30 seconds)
- Pin/Unpin Posts
- Featured Posts (Mark as Featured, Display on Homepage)
- Template Selection:
    - Dropdown populated from active theme's `theme.json` → `templates` object (see [Theme Structure](#theme-structure) for config format)
    - Template files located in `themes/{active-theme}/templates/`
    - Template keys (e.g., "default", "full-width") map to Blade files (default.blade.php)
    - Default template fallback: Falls back to "default" if selected template missing
    - Per-post override: Template choice stored in database (posts table, `template` column)
    - Theme switch behavior: Posts keep template key, frontend validates against new theme
- Excerpt (Manual or Auto-generated from first 160 characters)
- Reading Time Calculation (Auto-calculate, ~200 words/min)
- Duplicate Post (Create copy with "(Copy)" suffix, see detailed behavior below)
- Content Scheduler:
    - Publish Date/Time (future scheduling, stored as UTC)
    - Unpublish Date/Time (automatically take offline)
    - Timezone Support (admin timezone preference)
    - Scheduler Modes:
        - Visit-Triggered (default, Laravel Middleware, 60s cache lock)
        - Traditional Cron (optional, `* * * * * php artisan schedule:run`)
    - Unpublish Behavior (status → "Unpublished", URL returns 404)
    - UTC Storage: All timestamps stored as UTC (`Carbon::parse($input, $tz)->utc()`), display converts to user timezone
    - Edge Cases:
        - Publish Date in Past → Immediate publish, no scheduling
        - Unpublish < Publish Date → Validation error, `unpublish_at >= publish_at + 1min`
        - Timezone Change → UTC storage prevents issues, display auto-adjusts
        - DST Changes → UTC prevents duplicate/skipped execution
    - Validation: `publish_at` after_or_equal:now, `unpublish_at` after:publish_at
    - Manual Overrides: "Publish Now", "Unpublish Now", "Cancel Schedule" buttons
- Tags & Categories (Hierarchical)
- Flat-File Storage (Markdown)

#### TipTap Editor (Basic Features)

- Basic Formatting (Bold, Italic, Strike, Underline)
- Headings (H1-H6)
- Lists (Bullet, Numbered, Task Lists)
- Blockquote
- Code Block (Syntax Highlighting)
- Inline Code
- Links (with preview)
- Images (Upload, URL, Drag & Drop)
- WYSIWYG ↔ Markdown Toggle
- Live Preview:
    - Split View (Side-by-Side)
    - Device Switcher:
        - Desktop (1280px+)
        - Tablet (768px - 1279px)
        - Mobile (< 768px)
    - Full Preview Mode (without editor)

#### Custom Fields (Basic Types)

- Text (Short Text, Long Text)
- Number (Integer, Decimal)
- Boolean (Toggle, Checkbox)
- Date/Time (Date, Time, DateTime)

#### Revisions System (Flat-File)

- Auto-Create Revision on Save (Every 30 seconds)
- Revisions List (Date, User, Preview)
- Compare Revisions:
    - **v1.0.0:** Changes List (text-based summary)
        - Shows added/removed/modified sections
        - Line-by-line change summary
        - Character count differences
    - **v1.1.0:** Side-by-side Diff-View (visual comparison)
        - Split-screen layout
        - Color-coded additions (green) and deletions (red)
        - Inline change highlighting
- Restore to Any Revision
- Revision Author & Timestamp
- Revision Limits (max 10 per post, configurable: 5/10/20/50)
- Auto-Cleanup (delete old revisions beyond limit)
- Revision Storage:
    - Each revision = separate .md file
    - Naming: `{post-slug}.{timestamp}.md`
    - Metadata stored in YAML front matter

**Note:** For preventing data loss when multiple users edit simultaneously, see [Concurrent Editing Protection](#concurrent-editing-protection-optimistic-locking) below.

#### Concurrent Editing Protection (Optimistic Locking)

- **Problem:** Data Loss bei gleichzeitigem Bearbeiten
- **Solution:** Laravel Optimistic Locking - `lock_version INTEGER` column, `WHERE lock_version = ?` check, throw `StaleModelException` on conflict
- **Conflict Resolution UI:** 4 options - Keep My Version, Use Their Version, View Diff, Merge Manually
- **Auto-Save Integration:** Check `lock_version` before auto-save, pause + notify if changed
- **Real-time Notification:** "{User} is editing this post" badge (30s updates, WebSocket in v2.0)

#### Duplicate Post/Page

- Duplicate Button in Post List & Edit Screen
- Duplicated Content:
    - ✅ Title (with "(Copy)" suffix appended)
    - ✅ Content (full Markdown content)
    - ✅ Excerpt (if manually set)
    - ✅ Categories (all assigned categories)
    - ✅ Tags (all assigned tags)
    - ✅ Template Selection
    - ✅ Custom Fields (all field values)
    - ❌ Status (always set to "Draft", never "Published")
    - ❌ Slug (auto-generated from new title)
    - ❌ Author (set to current user)
    - ❌ Publish Date (set to current date/time)
    - ❌ Featured Image (not duplicated, security concern)
    - ❌ Revisions (fresh revision history)
- Duplicate Behavior:
    - Redirects to edit screen of new duplicate
    - Success notification: "Post duplicated successfully"
    - Original post remains unchanged

#### Media Library (Basic)

- Media Library UI
- Drag & Drop Upload
- Multi-File Upload
- Upload Directory Structure (YYYY/MM)
- File Organization (Folders)
- Media Search & Filters
- Image Preview (Modal)
- Direct Link Copy
- Storage Quota Display

#### Upload Security (Multi-Layer Validation)

- **Layer 1: File Extension Whitelist**
    - Allowed: .jpg, .jpeg, .png, .gif, .webp, .pdf
    - Rejected: Executable extensions (.php, .exe, .sh, .htaccess, etc.)

- **Layer 2: MIME Type Validation**
    - Check real MIME type via `finfo_file()`
    - Extension must match MIME type (`.jpg` → `image/jpeg`)
    - Prevents disguised executables

- **Layer 3: File Content Validation (Imagick/GD)**
    - **Images:** Re-encode to strip malware & metadata
        - Priority 1: Imagick (`stripImage()` + `setImageCompressionQuality()`) - best quality
        - Fallback: GD (`imagecreatefromjpeg()` + `imagecopy()`) - available on all hosts
        - Reject: Only if both unavailable (installer warning)
    - Quality Settings: Low (70%), Medium (85% default), High (95%)
    - **PDFs:** Validate via `smalot/pdfparser` - check embedded JS/launch actions, PDF v1.4-1.7 only, max 10MB

- **Layer 4: File Size Limits**
    - Max: 10MB default (configurable), batch limit: 50MB total

- **Layer 5: Filename Sanitization**
    - Algorithm: `{uuid}_{timestamp}.{extension}` (original stored in DB)
    - Security Headers: `nosniff`, `Content-Disposition`, `X-Frame-Options: DENY`

#### Upload Settings (Admin Configurable)

- Max File Size (default: 10MB, max: PHP upload_max_filesize)
- Allowed File Types (whitelist managed in Admin panel)
- Image Max Dimensions (default: 2560x2560px)
- Auto-optimization on upload

#### Image Processing

- Auto-Resize (respects max dimensions)
- Thumbnail Generation (multiple sizes: 150px, 300px, 768px)
- EXIF Data Stripping (removes GPS, camera data for privacy)
- WebP Conversion (optional, with JPEG/PNG fallback)
- Image Compression (configurable quality)

#### Image Metadata

- Alt Text (Accessibility/SEO)
- Title & Caption
- Focal Point Selection (for auto-crop)
- Storage Quota Display

**Note:** Media Usage Tracking available in v1.1.0 (Week 12 - Advanced Media)

---

### Phase 1: User Management (Week 5)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### User System

- User CRUD (Create, Read, Update, Delete)
- User Profiles (Avatar, Bio, Social Links)
- Role-Based Access Control (RBAC):
    - **Capability-Based Permissions** (granular, not just roles)
    - **Permission Storage:** `role_capabilities` table (role → capabilities mapping)
    - **Enforcement:** Laravel Gates/Policies (e.g., `Gate::authorize('edit-post', $post)`)
    - **Default Roles (v1.0):**
        - **Administrator** - All capabilities (`*` wildcard)
        - **Author** - Create/edit own posts, upload media
        - **User** - Comment on posts, view published content
        - **Guest** - View published content only (unauthenticated)

    **Permission Matrix (v1.0):**

    | Capability          | Administrator | Author | User | Guest |
    | ------------------- | ------------- | ------ | ---- | ----- |
    | `create-post`       | ✓             | ✓      | ✗    | ✗     |
    | `edit-own-post`     | ✓             | ✓      | ✗    | ✗     |
    | `edit-all-posts`    | ✓             | ✗      | ✗    | ✗     |
    | `delete-own-post`   | ✓             | ✓      | ✗    | ✗     |
    | `delete-all-posts`  | ✓             | ✗      | ✗    | ✗     |
    | `publish-post`      | ✓             | ✓      | ✗    | ✗     |
    | `create-page`       | ✓             | ✗      | ✗    | ✗     |
    | `edit-page`         | ✓             | ✗      | ✗    | ✗     |
    | `delete-page`       | ✓             | ✗      | ✗    | ✗     |
    | `upload-media`      | ✓             | ✓      | ✗    | ✗     |
    | `delete-own-media`  | ✓             | ✓      | ✗    | ✗     |
    | `delete-all-media`  | ✓             | ✗      | ✗    | ✗     |
    | `post-comment`      | ✓             | ✓      | ✓    | ⚙️\*  |
    | `moderate-comments` | ✓             | ✓\*\*  | ✗    | ✗     |
    | `manage-users`      | ✓             | ✗      | ✗    | ✗     |
    | `manage-settings`   | ✓             | ✗      | ✗    | ✗     |
    | `manage-themes`     | ✓             | ✗      | ✗    | ✗     |
    | `manage-plugins`    | ✓             | ✗      | ✗    | ✗     |
    | `view-published`    | ✓             | ✓      | ✓    | ✓     |
    | `view-drafts`       | ✓             | ✓\*\*  | ✗    | ✗     |

    **Notes:**
    - `*` Guest comments configurable (Settings → Comments → Allow Guest Comments)
    - `**` Author = Own content only
    - "Own" = User created the resource (`created_by` column)
    - "All" = Can modify any resource regardless of creator

#### Authentication

- Login / Logout
- Password Reset
- Remember Me
- Invite-Only Registration (default)
- Invite Token Generation & Email
- First Setup Wizard (Admin Creation)
- Session Management

#### Activity Tracking

- Activity Log (Admin Actions)
- Authentication Log (Login History, IP, User Agent)
- Failed Login Attempts

---

### Phase 1: Theme System (Week 6)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### Frontend (Public Site)

- Blade Templates + Alpine.js + Tailwind CSS 4
- 1 Default Theme (included: `themes/default/`)
- Theme Installation via SFTP (no ZIP upload)
- Theme Switcher (Admin Panel, scans `themes/` directory)
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
│   │   ├── default.blade.php   ← Default template
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

#### Theme Discovery

- Scans `themes/` directory for folders with `theme.json`
- Active theme stored in database (`settings` table)
- Fallback to `default` theme if active theme missing
- Template dropdown populated from theme.json templates

#### Admin Panel

- PrimeVue + Inertia.js + Vue 3.5
- Dark/Light Mode Toggle (User Preference)
- PrimeIcons (Admin Only)
- Responsive Design (Mobile-Friendly)

---

### Phase 0: Frontend Event System (Week 6)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

**Architektur-Entscheidung:** Laravel Events (einheitlich für Backend + Frontend)

#### Blade @event() Directive

- Custom Blade directive `@event('event-name')` for theme integration
- Triggers Laravel Events from Blade templates
- Output sanitization (XSS prevention via Blade escaping)
- Event existence check (silent failure if no listeners)
- Debug mode logging (event execution tracking)

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

**Event Listener Example:**

```php
namespace MyPlugin\Listeners;

class InjectAnalytics
{
    public function handle($event): string
    {
        // Return HTML (Blade-escaped by default)
        return view('my-plugin::analytics')->render();
    }
}
```

#### Event Priority System

- Priority: Array position in `$listen` (first = highest priority)
- Multiple listeners: Execute in registration order
- Core events: Registered first (highest priority)
- Plugin events: Registered after core (lower priority)

#### Plugin Integration

- Events register in `PluginServiceProvider::$listen` array
- Laravel auto-discovery of Event Listeners
- Event validation (prevent infinite loops)
- Event caching (performance optimization via Laravel)
- Cross-reference: `docs/PLUGIN_DEVELOPMENT.md` for usage examples

---

### Phase 1: Categories & Tags System (Week 7)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

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

### Phase 1: Admin Panel & Settings (Week 8)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

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

---

### Phase 1: Import/Export System (Week 9)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

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

### Phase 1: SEO & Privacy (Week 10)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### SEO Features

- Auto-Slug Generation (Eloquent Sluggable)
- Custom Slugs (Editable)
- XML Sitemap (Auto-Updated)
- RSS Feed (Posts, Categories, Tags)
- Meta Tags Management (OpenGraph, Twitter Cards)
- Canonical URLs

**Note:** Breadcrumbs are theme-specific (themes generate their own breadcrumbs)

#### Privacy & Security

- Cookie Consent Banner
- Field-Level Encryption (Email, PII via CipherSweet)
- Content Security Policy (CSP Headers)
- Secure Headers (XSS Protection, HSTS)
- IP Anonymization
- CSRF Protection (Laravel Default)
- XSS Protection
- SQL Injection Protection (Eloquent ORM)
- Rate Limiting (Forms, Login)

**Backup Implementation:**

- Uses spatie/laravel-backup package
- Components: SQLite Database, Flat-Files (Markdown), Media Files
- Configuration Files (.env excluded for security)
- Automated scheduling (Visit-Triggered or Traditional Cron)
- One-Click operations via Admin Panel

#### UI/UX Polish

- Custom Error Pages (404, 403, 500, 503)
- Loading States (Skeleton Loaders)
- Toast Notifications (Success, Error, Info, Warning)
- Modal System (Confirm Dialogs)
- Form Validation (Real-Time)
- Responsive Design (Mobile, Tablet, Desktop)

**Note:** Lightbox is theme-specific (not in core)

---

## 📦 Version 1.1.0 - Enhanced Core

**Timeline:** +3-4 Wochen **Goal:** Professionelle Features + Plugin-System + Analytics + Workflow **Features:** ~54 zusätzlich (Total: ~149)

### Comments System (Week 10-11)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

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

- ❌ Upvote/Downvote Comments → "Voting System" Community Plugin (for Posts +
  Comments)

---

### Search System (Week 10-11)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

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
- Quick Actions:
    - Create New Post
    - Create New Page
    - Navigate to Settings
    - Navigate to Profile
    - Clear Cache
- Keyboard Navigation
- Recent Searches

---

### Advanced User Management (Week 11)

**Status:** 📋 Geplant
**Typ:** 🔧 Enhancement
**Version:** v1.1.0

#### RBAC Enhanced (4 Roles)

- **Administrator** - Full system access
- **Author** - Create/edit own posts, upload media
- **User** - View content, comment, manage profile (NEW)
- **Guest** - Read-only access to published content (NEW)

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

- Public Registration Toggle (Settings → Security Tab)
- Switch from Invite-Only to Public Registration
- Email Verification (optional for public registration)

---

### Advanced Media Library (Week 12)

**Status:** 📋 Geplant
**Typ:** 🔧 Enhancement
**Version:** v1.1.0

- Additional File Types:
    - Videos (MP4, WebM, OGG)
    - Audio (MP3, WAV, OGG)
    - Documents (DOC, DOCX, XLS, XLSX)
    - Archives (ZIP, TAR, GZ)

- Advanced Image Processing:
    - Image Filters (Grayscale, Blur, Brightness)
    - Responsive Images (srcset generation)
    - Retina/HiDPI Support (2x, 3x)
    - Image CDN Support (optional)

- Media Usage Tracking:
    - Show where image/file is used (Posts, Pages)
    - "Used in X posts" indicator
    - Prevent deletion if in use (warning + override option)

- Bulk Actions:
    - Bulk Upload (Multiple files at once)
    - Bulk Delete
    - Bulk Move
    - Bulk Download

---

### TipTap Editor (Advanced Features) (Week 12)

**Status:** 📋 Geplant
**Typ:** 🔧 Enhancement
**Version:** v1.1.0

- Tables (Create, Edit, Delete rows/columns)
- Horizontal Rule
- Embed (YouTube, Vimeo, Twitter, etc.)
- Markdown Shortcuts
- Slash Commands (/ menu)

---

### Bulk Actions (Week 12)

**Status:** 📋 Geplant
**Typ:** 🔧 Enhancement
**Version:** v1.1.0

- Bulk Select (Checkboxes)
- Bulk Change Status (Draft ↔ Published)
- Bulk Delete
- Bulk Move to Category/Tag
- Bulk Assign Author
- Bulk Export (CSV, JSON)
- Duplicate Post/Page (see "Duplicate Post/Page" section in Phase 0 for detailed behavior)
- Inline Editing (Title, Status, Date)

---

### Analytics (Matomo Integration) - **CORE FEATURE** (Week 12)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

- Matomo Integration (Self-Hosted, PHP SDK)
- Privacy-First Analytics:
    - No Cookies Required (Cookieless Tracking)
    - IP Anonymization (GDPR-compliant)
    - DNT (Do Not Track) Support
    - Anonymous Tracking Mode
- Dashboard Widget (Admin Panel):
    - Pageviews (Today, Week, Month)
    - Unique Visitors
    - Top Posts/Pages
    - Referrers (Where visitors come from)
    - Browser/Device Stats
- Detailed Reports (Admin Panel):
    - Visitor Log
    - Geographic Location (Country-level only)
    - Page Performance
- Privacy Controls (Settings → Privacy Tab):
    - Enable/Disable Analytics
    - Analytics Opt-Out (User Preference)
    - Data Retention Settings (GDPR: 6/12/24 months)
    - Cookie Consent Integration
- Performance:
    - Async Tracking (No impact on page load)
    - Optional: Separate SQLite DB for analytics

---

### Custom Fields (Advanced Types) (Week 13)

**Status:** 📋 Geplant
**Typ:** 🔧 Enhancement
**Version:** v1.1.0

- Select (Dropdown, Multi-select, Radio)
- URL
- Email
- Rich Text (TipTap instance for field)

---

### Advanced Settings Tab (Week 13)

**Status:** 📋 Geplant
**Typ:** 🔧 Enhancement
**Version:** v1.1.0

**Advanced Tab:**

- Cache Settings (Enable/Disable, Clear Cache)
- Maintenance Mode
- Debug Mode Toggle
- Custom Code (Head/Footer Injection)

---

### Workflow System (Week 13)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

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

### Plugin System (Week 13) - **CRITICAL**

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

#### Plugin Architecture

- Event-Driven Architecture (Laravel Events)
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

- Plugin Activation/Deactivation (Admin Panel)
- Plugin Settings Page (Per Plugin)
- Plugin Hooks/Events:
    - **Laravel Event System** (Events + Listeners, NOT WordPress-style hooks)
    - **Event Registration:** Plugin's `PluginServiceProvider` → `$listen` array
    - **Priority System:** Listener execution order via array position (first = highest priority)
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

### Update Manager (Week 13)

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.1.0

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

## 📦 Version 1.2.0 - Professional Features

**Timeline:** +2-3 Wochen **Goal:** Erweiterte CMS-Features **Features:** ~10
zusätzlich (Total: ~153)

### Import/Export Enhancements (Week 14)

**Status:** 📋 Geplant
**Typ:** 🔧 Enhancement
**Version:** v1.2.0

**Note:** Basic Import/Export is now in v1.0.0 (Week 9)

- Import Error Handling & Retry
- Advanced Content Mapping (custom field mapping)
- Import from Medium (via RSS)
- Import from Substack
- Scheduled Exports (automatic weekly/monthly backups)

---

### Advanced SEO (Week 14)

**Status:** 📋 Geplant
**Typ:** 🔧 Enhancement
**Version:** v1.2.0

- Schema.org Structured Data (Article, Blog, Organization)
- Open Graph & Twitter Cards (Enhanced)
- XML Sitemap (Advanced with images, priorities)
- Custom Meta Tags per Post/Page

---

### Advanced Features (Week 15)

**Status:** 📋 Geplant
**Typ:** 🔧 Enhancement
**Version:** v1.2.0

#### Redirect Management

- 301/302 Redirect Management
- Redirect CRUD (Admin Panel)
- Bulk Import (CSV)
- Wildcard Redirects
- Redirect History (Track Usage)

#### Menu Builder

- Drag & Drop Menu Creation
- Menu Locations (Header, Footer, Sidebar)
- Menu Items:
    - Pages
    - Posts
    - Categories
    - Tags
    - Custom Links
    - Nested Items (Unlimited Depth)
- Menu Item Options (CSS Class, Target, Icon)

#### Widget System

- Widget Areas (Sidebar, Footer)
- Default Widgets:
    - Recent Posts
    - Popular Posts
    - Categories
    - Tags
    - Custom HTML
- Drag & Drop Widget Management

#### Custom Routing

- Multi-Language URL Prefixes (/en/, /de/)
- Hierarchical URLs (/blog/category/subcategory/post)
- Custom Post Type URLs
- Route Wildcards

---

## 🎯 System Features (All Versions)

### Health Check Dashboard

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0+

- Storage Usage (Media, Backups, Cache)
- File Permissions Check
- PHP Version & Extensions
- Database Status
- Cache Status
- Queue Status
- System Logs

### Performance

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0+

- Page Caching (Laravel Cache)
- Query Caching
- Asset Optimization (Vite Build)
- Image Lazy Loading
- CDN Support (Configurable)

---

### Scheduler Implementation

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

#### Visit-Triggered (Default)

- Laravel Middleware on public routes
- Cache-based lock (60s TTL)
- Executes on page visits (if cache expired)
- Zero configuration needed
- Proven technique (WordPress, ProcessWire, Craft CMS)

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

## 📦 Deployment Features

### Shared Hosting Compatibility

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

- Apache Support (.htaccess)
- nginx Support (nginx.conf.example)
- PHP 8.3+ Required
- SQLite Database (No MySQL Required)
- File-Based Sessions
- File-Based Cache
- No Git Required on Server
- FTP/SFTP Upload Support
- Scheduler: Visit-Triggered (default) or Traditional Cron (optional)

### Release Package

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

- Pre-Compiled Assets (Vite Build)
- Optimized Autoloader
- Cached Config/Routes/Views
- Production-Ready .env.example
- Installation Guide (README.md)
- Changelog (CHANGELOG.md)

---

## 📊 Core Feature Summary

| Version                   | Features | Timeline     | Total Core Features |
| ------------------------- | -------- | ------------ | ------------------- |
| **v1.0.0 (MVP)**          | ~95      | 10-11 Wochen | 95                  |
| **v1.1.0 (Enhanced)**     | +54      | +3-4 Wochen  | 149                 |
| **v1.2.0 (Professional)** | +10      | +2-3 Wochen  | 159                 |

**Total Development Time:** 15-18 Wochen für v1.2.0

**Key Improvements in v1.0.0:**

- ✅ Import/Export (WordPress, Ghost) - Critical for adoption
- ✅ Hierarchical Categories - Professional content structure
- ✅ Enhanced Media Library (Alt Text, Focal Point, EXIF stripping)
- ✅ Advanced Live Preview (Device switcher, Full preview mode)
- ✅ Enhanced Content Scheduler (Unpublish date, Timezone support)
- ✅ Revision Management (Limits, Auto-cleanup)

---

## 🔌 What's NOT in Core?

Die folgenden Features sind als **Official Plugins** verfügbar:

1. **Newsletter Plugin** (~7 Features) - Newsletter-System mit Subscriber
   Management
2. **Webhooks Plugin** (~11 Features) - Webhook-System für Integrationen
3. **Custom Fields Pro Plugin** (~7 Features) - Advanced Field Types (Repeater,
   Gallery, JSON, etc.)
4. **Multi-Language Plugin** (~7 Features) - Content Translation UI (erweitert)
5. **Workflow Plugin** (~6 Features) - Draft → Review → Publish Workflow
6. **Two-Factor Auth Plugin** (~5 Features) - 2FA mit Google Authenticator
7. **Form Builder Plugin** (~12 Features) - Drag & Drop Formular-Builder
8. **SEO Pro Plugin** (~5 Features) - Advanced SEO (Schema.org, Broken Link
   Checker)

**Total Plugin Features:** ~60 (optimiert nach KISS/DRY/YAGNI principles)

➡️ Siehe `docs/OFFICIAL_PLUGINS.md` für Details

---

**Last Updated:** 2025-01-26 **Maintained By:** PineCMS Team **License:** MIT -
100% Open Source
