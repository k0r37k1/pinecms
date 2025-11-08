# 10 - Release Roadmap & Timeline

**Version:** 1.0.0
**Last Updated:** 2025-11-08
**Status:** 📋 Planned
**Owner:** PineCMS Team

---

## 1. Overview

This document outlines the development roadmap for PineCMS across three major releases (v1.0.0, v1.1.0, v1.2.0) with detailed timelines, deliverables, and success criteria.

**Project Philosophy:**

- ✅ Sustainable 5-10 year commitment
- ✅ Privacy-first by design
- ✅ Focus on essential blogging features
- ✅ Community-driven development

**Target Deployment:** Shared Hosting (No Git Required)
**Architecture:** Core CMS + Official Plugins
**License:** MIT - 100% Open Source

---

## 2. Timeline Overview

### 2.1 Development Schedule

**Total Development Time:** 15-18 Weeks (3.5-4.5 Months)
**Target Public Release:** Q2 2025 (v1.0.0)

| Version          | Duration    | Core Features | Plugin Features | Total Features | Release Date |
| ---------------- | ----------- | ------------- | --------------- | -------------- | ------------ |
| **v1.0.0 (MVP)** | 10-11 weeks | ~95           | -               | **95**         | Q2 2025      |
| **v1.1.0**       | +3-4 weeks  | ~149          | ~17             | **166**        | Q3 2025      |
| **v1.2.0**       | +2-3 weeks  | ~159          | ~17             | **176**        | Q3 2025      |

**Note:** Plugin development happens in parallel during weeks 11-18.

---

### 2.2 Key Milestones

**v1.0.0 Milestones:**

- Week 2: Web installer functional
- Week 5: Basic content management working
- Week 6: User system & theme architecture complete
- Week 9: Import/Export functional
- Week 11: v1.0.0 Release Candidate

**v1.1.0 Milestones:**

- Week 13: Plugin system functional
- Week 13: All 3 official plugins installable
- Week 14: v1.1.0 Release Candidate

**v1.2.0 Milestones:**

- Week 15: Advanced features complete
- Week 16: v1.2.0 Release Candidate

**Public Release:**

- Week 17-18: Security audit, performance testing, documentation
- Week 18: v1.0.0 Public Release

---

## 3. Version 1.0.0 - Core CMS (MVP)

### 3.1 Overview

**Duration:** 10-11 Weeks
**Goal:** Lean, fast blogging CMS with migration path
**Features:** ~95 Core Features
**Status:** 📋 Planned

**Release Criteria:**

- ✅ Web installer completes in < 5 minutes
- ✅ Page load < 1 second on shared hosting
- ✅ Import from WordPress/Ghost functional
- ✅ Zero critical bugs
- ✅ WCAG 2.1 AA compliant

---

### 3.2 Week 1-2: Installer & Foundation

**Status:** 📋 Planned | **Priority:** 🔴 Critical | **Type:** 🎯 Core Feature

#### Deliverables

**Web-Based Installer:**

- System requirements check (PHP 8.3+, Extensions)
- Automatic `.env` file generation
- SQLite database creation
- First admin user setup wizard
- Apache `.htaccess` configuration
- `nginx.conf.example` template

**Success Criteria:**

- ✅ User can upload ZIP to shared hosting
- ✅ User can complete installation via browser (< 5 minutes)
- ✅ Admin can login to dashboard
- ✅ No Git/Composer required on server

**Technical Requirements:**

- PHP 8.3+ with extensions: PDO_SQLite, GD/Imagick, Fileinfo, Mbstring, OpenSSL
- Web server: Apache 2.4+ or nginx 1.20+
- Writable directories: `storage/`, `bootstrap/cache/`, `database/`

---

### 3.3 Week 3-5: Content Management

**Status:** 📋 Planned | **Priority:** 🔴 Critical | **Type:** 🎯 Core Feature

#### Week 3-4: Core Content

**Database Schema:**

- Users table (with encrypted fields)
- Posts metadata table (SQLite)
- Pages metadata table (SQLite)
- Categories table (hierarchical, max 2 levels)
- Tags table
- Media library table
- Revisions table

**Flat-File Storage:**

- Markdown content files (`content/posts/{uuid}.md`)
- YAML front matter (metadata)
- Revision snapshots (`content/revisions/{uuid}/{timestamp}.md`)

**Post/Page CRUD:**

- Create, edit, delete posts/pages
- Template selection (Default, Full Width, Sidebar)
- Excerpt (manual or auto-generated from first 160 chars)
- Reading time calculation (~200 words/min)
- Duplicate post feature
- Basic custom fields (Text, Number, Date, Boolean)

**Revisions System:**

- Auto-save every 30 seconds
- Revision history (flat-file storage)
- Revision limits (keep last 10 revisions per post)
- Auto-cleanup (delete revisions older than 30 days)
- Changes list (show what changed between revisions)

**Content Scheduler:**

- Publish date/time picker
- Unpublish date/time (optional)
- UTC storage (timezone-agnostic)
- Timezone support (convert to user's local time)
- DST-safe scheduling

**Note:** Side-by-side revision diff moved to v1.1.0

---

#### Week 4: TipTap Editor (Basic)

**Status:** 📋 Planned | **Priority:** 🔴 Critical

**Basic Formatting:**

- Bold, Italic, Strike, Underline
- Headings (H1-H6)
- Lists (Bullet, Numbered, Task)
- Code blocks (syntax highlighting via Shiki)
- Links with preview
- Images (upload, URL, drag & drop)

**Editor Features:**

- WYSIWYG ↔ Markdown toggle
- Auto-save (30 seconds, debounced)
- Live preview:
    - Split view (side-by-side)
    - Device switcher (Desktop 1280px+ / Tablet 768px-1279px / Mobile <768px)
    - Full preview mode (full-screen)

**Technical:**

- TipTap Vue 3 integration
- Shiki syntax highlighting (server-side)
- Markdown rendering (CommonMark)
- Image lazy loading

---

#### Week 5: Media Library (Basic)

**Status:** 📋 Planned | **Priority:** 🔴 Critical

**Upload Security (5-Layer Protection):**

1. File extension whitelist (JPEG, PNG, GIF, WebP, PDF)
2. MIME type validation (server-side)
3. Content validation (Imagick/GD re-encoding, PDF parsing)
4. File size limits (10MB default, configurable)
5. Filename sanitization (UUID + timestamp)

**Upload Features:**

- Drag & drop upload
- Multi-file upload (batch)
- Upload settings (max size, allowed types)
- Directory structure (YYYY/MM)
- Progress indicator

**Image Processing:**

- Auto-resize (max 2000px width)
- Thumbnail generation (150px, 300px, 600px)
- WebP conversion (80% quality)
- Compression (optimize file size)
- EXIF data stripping (privacy)

**Image Metadata:**

- Alt text (accessibility/SEO)
- Title & caption
- Focal point selection (drag to position)

**Media Library UI:**

- Grid/list view toggle
- File organization (folders)
- Search & filters (by type, date, size)
- Image preview (modal)
- Copy URL to clipboard

**Supported File Types (v1.0.0):**

- Images: JPEG, PNG, GIF, WebP
- Documents: PDF

**Note:** Advanced file types (video, audio, documents, archives) in v1.1.0

---

### 3.4 Week 6: User Management & Theme System

**Status:** 📋 Planned | **Priority:** 🔴 Critical | **Type:** 🎯 Core Feature

#### User Management

**User CRUD:**

- Create, edit, delete users
- User profiles (avatar, bio, social links)
- Activity log (admin actions)
- Authentication log (login history)

**Roles (v1.0.0 - 2 Roles):**

1. **Administrator** - Full system access
2. **Author** - Create/edit own posts, upload media

**Authentication:**

- Login/logout
- Password reset (token-based, 60min expiry)
- Remember me (30 days)
- Invite-only registration (default mode)
- Session timeout (120 minutes inactivity)
- Account lockout (5 failed attempts)

**Security:**

- Laravel Sanctum (token-based auth)
- CSRF protection
- Rate limiting (5 login attempts per minute)
- Password validation (min 8 chars, mixed case, numbers, symbols)

**Note:** 4-role RBAC (Administrator, Author, User, Guest) in v1.1.0

---

#### Theme System

**Frontend (Public Site):**

- Blade Templates + Alpine.js 3 + TailwindCSS 4
- 1 default theme included (Forest Green palette)
- Theme switcher UI (Admin Panel)
- Dark/light mode toggle (user preference, localStorage)
- Lucide Icons

**Admin Panel:**

- PrimeVue + Inertia.js 2.x + Vue 3.5 Composition API
- Dark/light mode toggle
- PrimeIcons
- Responsive design (mobile-friendly)
- Forest Green color scheme (#2D5F3E primary)

**Theme Structure:**

```
themes/
├── default/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── partials/
│   │   ├── posts/
│   │   │   ├── index.blade.php
│   │   │   └── show.blade.php
│   │   └── pages/
│   │       └── show.blade.php
│   ├── assets/
│   │   ├── css/
│   │   └── js/
│   └── theme.json (metadata)
```

---

### 3.5 Week 7: Categories & Tags System

**Status:** 📋 Planned | **Priority:** 🟠 High | **Type:** 🎯 Core Feature

**Hierarchical Categories:**

- Parent → Child (max 2 levels)
- Category CRUD (create, edit, delete)
- Slugs & descriptions
- Category color/badge
- Default category ("Uncategorized" auto-assign)
- Drag & drop reordering

**Tags System:**

- Tag CRUD with autocomplete
- Tag usage count
- Tag merging (combine duplicate tags)
- Popular tags widget

**Assignment:**

- Assign multiple categories per post
- Assign multiple tags per post
- Bulk category/tag assignment

**URLs:**

- `/blog/category/{slug}`
- `/blog/tag/{slug}`

---

### 3.6 Week 8: Admin Panel & Settings

**Status:** 📋 Planned | **Priority:** 🔴 Critical | **Type:** 🎯 Core Feature

#### Dashboard

**Quick Stats:**

- Total posts (published, drafts)
- Total pages
- Total users
- Storage usage

**Recent Activity Feed:**

- Recent posts (last 5)
- Recent comments (last 5, if enabled)
- Recent users

**Quick Actions:**

- New post button
- New page button
- View site link

**System Health:**

- Storage usage (database size, media size)
- Cache status
- Last backup date

---

#### Site Settings (Tabbed UI)

**General Tab:**

- Site name, tagline
- Logo upload (SVG, PNG)
- Favicon upload (ICO, PNG)
- Timezone (user's local timezone)
- Date/time format (PHP format strings)

**SEO Tab:**

- Meta description template
- Sitemap settings (auto-generate, update frequency)
- Robots.txt editor

**Privacy Tab:**

- Cookie consent banner (enable/disable)
- Field-level encryption settings (enable/disable)
- GDPR compliance tools
- **Analytics:** User-integrated via plugins/themes/custom code (NO default analytics)

**Email Tab:**

- SMTP configuration (host, port, username, password, encryption)
- Plain text email templates
- Test email button (send test email)

**Security Tab:**

- Registration mode (invite-only, public, email verification)
- Rate limiting settings
- CSP (Content Security Policy) configuration
- Secure headers (X-Frame-Options, HSTS)

**Backup Tab:**

- Auto-backup schedule (local storage: daily/weekly/monthly)
- One-click backup (database, flat-files, media)
- One-click restore

**Note:** Rich HTML email templates in v1.1.0, S3/SFTP backup via plugin

---

### 3.7 Week 9: Import/Export System

**Status:** 📋 Planned | **Priority:** 🔴 Critical | **Type:** 🎯 Core Feature

**Why Critical:** Essential for user adoption (migration path from existing platforms)

#### Import Sources

**WordPress Import:**

- XML/WXR format support
- Import posts, pages, comments, categories, tags
- Import media (download from URLs)
- Author mapping

**Ghost Import:**

- JSON export format support
- Import posts, tags, authors
- Import media

**Markdown Import:**

- Bulk import from ZIP archive
- YAML front matter parsing
- Auto-detect metadata (title, date, tags, categories)

**Import UI:**

- Content mapping (map WordPress categories to PineCMS categories)
- Import preview (show what will be imported)
- Progress bar (real-time progress updates)

---

#### Export Formats

**JSON Export:**

- Full database export
- Selective export (posts only, pages only, etc.)

**Markdown Export:**

- ZIP archive with all Markdown files
- YAML front matter included
- Media files included
- Directory structure preserved

**Selective Export:**

- Export by date range
- Export by author
- Export by category/tag

---

### 3.8 Week 10: SEO, Privacy & Polish

**Status:** 📋 Planned | **Priority:** 🔴 Critical | **Type:** 🎯 Core Feature

**Note:** Essential for production release

#### SEO Features

**Slug Generation:**

- Auto-slug generation (Eloquent Sluggable)
- Custom slugs (editable)
- Unique slug validation

**Sitemap & Feeds:**

- XML sitemap (auto-updated on content changes)
- RSS feed (posts, categories, tags)
- Atom feed

**Meta Tags:**

- OpenGraph tags (title, description, image)
- Twitter Cards (summary, large image)
- Canonical URLs (prevent duplicate content)

**Note:** Breadcrumbs are theme-specific (not in core)

---

#### Privacy & Security

**Privacy Features:**

- Cookie consent banner (customizable)
- Field-level encryption (CipherSweet for sensitive fields)
- EXIF data stripping from uploaded images
- **NO default analytics** - Users integrate their own via plugins/themes/custom code

**Security Features:**

- CSRF protection (Laravel default)
- XSS protection (Blade escaping, Vue sanitization)
- Rate limiting (forms, login, API)
- Secure headers (CSP, X-Frame-Options, HSTS)

---

#### Backup System

**Automated Backups:**

- Database backup (SQLite file copy)
- Flat-file backup (Markdown content)
- Media backup (images, PDFs)
- Backup scheduler (daily/weekly/monthly, configurable)

**Backup UI:**

- One-click backup button
- One-click restore (select backup from list)
- Backup history (last 10 backups)
- Download backup ZIP

**Storage:** Local filesystem only (v1.0.0), S3/SFTP via plugin (future)

---

#### UI/UX Polish

**Error Pages:**

- Custom 404 (Page Not Found)
- Custom 403 (Forbidden)
- Custom 500 (Server Error)
- Custom 503 (Maintenance Mode)

**Loading States:**

- Skeleton loaders (post list, media grid)
- Spinner animations
- Progress bars (upload, import, backup)

**Notifications:**

- Toast notifications (success, error, info, warning)
- Dismissable alerts
- Auto-dismiss (3 seconds default)

**Modal System:**

- Confirm dialogs (delete post, delete user)
- Image preview modal
- Custom modals (reusable component)

**Form Validation:**

- Real-time validation (on blur)
- Error messages (field-level)
- Submit button disabled until valid

**Responsive Design:**

- Mobile (< 768px)
- Tablet (768px - 1279px)
- Desktop (1280px+)
- Touch-friendly UI (larger buttons on mobile)

**Note:** Lightbox is theme-specific (not in core)

---

### 3.9 v1.0.0 Success Criteria

**Functional Requirements:**

- ✅ User can create/edit/delete posts & pages
- ✅ Posts have template selection, excerpt, reading time, duplicate feature
- ✅ TipTap editor works with enhanced live preview (device switcher)
- ✅ Media library functional with alt text, focal point, EXIF stripping
- ✅ 2-role system works (Administrator, Author)
- ✅ Hierarchical categories with default category
- ✅ Import from WordPress/Ghost works
- ✅ Enhanced content scheduler (publish/unpublish dates, UTC storage, timezone, DST-safe)
- ✅ Revision management with limits, auto-cleanup, changes list
- ✅ Theme system works (1 default theme included)
- ✅ One-click backup/restore works (local storage)

**Performance Requirements:**

- ✅ Installation completes in < 5 minutes on shared hosting
- ✅ Page load < 1 second on shared hosting (P95)
- ✅ Database queries < 100ms (P95)

**Quality Requirements:**

- ✅ **~95 Core Features Implemented**
- ✅ WCAG 2.1 AA compliant
- ✅ OWASP Top 10 2024 compliant
- ✅ Zero critical bugs
- ✅ Code coverage > 80%
- ✅ PHPStan Level 8 passing

**Documentation:**

- ✅ Installation guide
- ✅ User guide (basic)
- ✅ Admin panel documentation

---

### 3.10 Features Removed from Core (YAGNI)

**Moved to Community Plugins:**

- ❌ Upvote/Downvote System (Posts) → "Voting System" Community Plugin
- ❌ Upvote/Downvote Comments → "Voting System" Community Plugin

**Theme-Specific (Not Core):**

- ❌ Social Sharing Buttons → Theme-specific
- ❌ Breadcrumbs Navigation → Theme-specific
- ❌ Lightbox → Theme modal only

**Deferred to v1.1.0:**

- ❌ Media Usage Tracking → v1.1.0 (complexity)
- ❌ Side-by-side Revision Diff → v1.1.0 (UI complexity)

---

## 4. Version 1.1.0 - Core + Plugin System

### 4.1 Overview

**Duration:** +3-4 Weeks (Weeks 10-13)
**Goal:** Plugin System + Workflow + Advanced Features
**Core Features:** +54 (Total: ~149)
**Plugin Features:** ~17 (3 Official Plugins)
**Total Features:** 166
**Status:** 📋 Planned

**Release Criteria:**

- ✅ Plugin system functional
- ✅ All 3 official plugins installable
- ✅ Comments system works with moderation
- ✅ Search functional
- ✅ 4-role RBAC system works
- ✅ Workflow system functional

---

### 4.2 Week 10-11: Comments & Search

**Status:** 📋 Planned | **Priority:** 🟠 High | **Type:** 🎯 Core Feature

#### Comments System

**Comment CRUD:**

- Create, edit, delete comments
- Moderation queue (approve/reject/spam)
- Guest comments toggle (disabled by default)
- Nested comments (max depth: 3 levels)
- Comment sorting (newest, oldest)

**Comment Features:**

- Email notifications (author, admin)
- Gravatar support
- Markdown support in comments
- Comment threading (reply to comment)

**Moderation:**

- Auto-approve registered users (optional)
- Blacklist words (auto-spam)
- Akismet integration (optional plugin)

**Removed (YAGNI):**

- ❌ Upvote/Downvote Comments → "Voting System" Community Plugin

---

#### Search System

**Full-Text Search:**

- TNTSearch driver (default, no external dependencies)
- Search posts, pages, tags, categories
- Search indexing (auto-update on save)

**Search Filters:**

- Filter by type (posts, pages)
- Filter by date range
- Filter by author
- Filter by tags/categories

**Search UI:**

- Search bar (header)
- Search results page
- Search highlighting (matched terms)
- Search suggestions (autocomplete)

---

#### Command Palette (CMD+K)

**Quick Search:**

- Posts, pages, tags, categories
- Users (admin only)

**Quick Actions:**

- New post
- New page
- Settings
- Clear cache
- View site

**Keyboard Navigation:**

- Arrow keys (navigate results)
- Enter (select result)
- Escape (close palette)

**Recent Searches:**

- Show last 5 searches
- Click to repeat search

---

### 4.3 Week 11: Advanced User Management

**Status:** 📋 Planned | **Priority:** 🟡 Medium | **Type:** 🔧 Enhancement

#### RBAC Enhanced (4 Roles)

**Roles:**

1. **Administrator** - Full system access
2. **Author** - Create/edit own posts, upload media
3. **User** - View content, comment, manage profile (NEW)
4. **Guest** - Read-only published content (NEW)

#### Permissions Matrix

| Feature           | Administrator | Author | User | Guest  |
| ----------------- | ------------- | ------ | ---- | ------ |
| System Settings   | ✅            | ❌     | ❌   | ❌     |
| User Management   | ✅            | ❌     | ❌   | ❌     |
| Plugin Management | ✅            | ❌     | ❌   | ❌     |
| Create/Edit Posts | ✅            | ✅\*   | ❌   | ❌     |
| Delete Posts      | ✅            | ✅\*   | ❌   | ❌     |
| Create/Edit Pages | ✅            | ❌     | ❌   | ❌     |
| Upload Media      | ✅            | ✅     | ❌   | ❌     |
| Moderate Comments | ✅            | ✅\*   | ❌   | ❌     |
| Post Comments     | ✅            | ✅     | ✅   | ⚙️\*\* |
| View Published    | ✅            | ✅     | ✅   | ✅     |
| View Drafts       | ✅            | ✅\*   | ❌   | ❌     |

\*Author = Own content only
\*\*Guest comments configurable in Settings

#### Registration Mode

**Settings → Security Tab:**

- Public registration toggle (enable/disable)
- Switch from invite-only to public
- Email verification (optional)
- Default role for new users (User, Author)

---

### 4.4 Week 12: Advanced Media & Editor

**Status:** 📋 Planned | **Priority:** 🟡 Medium | **Type:** 🔧 Enhancement

#### Advanced Media Library

**Additional File Types:**

- Videos: MP4, WebM, OGG
- Audio: MP3, WAV, OGG
- Documents: DOC, DOCX, XLS, XLSX
- Archives: ZIP, TAR, GZ

**Advanced Image Processing:**

- Crop & focal point
- Filters (grayscale, blur, brightness)
- Responsive images (srcset generation)
- Retina/HiDPI support (2x, 3x)
- CDN support (optional, configurable)

**Bulk Actions:**

- Bulk delete
- Bulk move to folder
- Bulk download (ZIP archive)

---

#### TipTap Editor (Advanced)

**New Features:**

- Tables (create, edit rows/columns)
- Horizontal rule
- Embeds (YouTube, Vimeo, Twitter)
- Markdown shortcuts (e.g., `##` for H2)
- Slash commands (`/` menu for quick formatting)

---

#### Bulk Content Actions

**Post/Page List:**

- Bulk select (checkboxes)
- Bulk publish/unpublish
- Bulk delete (with confirmation)
- Bulk move to category
- Bulk assign tags
- Bulk assign author
- Bulk export (CSV, JSON)
- Inline editing (quick edit title, status)

---

#### Custom Fields (Advanced)

**New Field Types:**

- Select (dropdown, multi-select, radio)
- URL (with validation)
- Email (with validation)
- Rich text (TipTap instance, full or mini)

**Note:** Advanced custom fields (relationship, repeater, gallery, JSON, color picker) moved to Community Plugin (previously "Custom Fields Pro" official plugin)

---

### 4.5 Week 13: Plugin System & Workflow

**Status:** 📋 Planned | **Priority:** 🔴 Critical | **Type:** 🎯 Core Feature

#### Plugin System

**Architecture:**

- Event-driven (Laravel Events, NOT WordPress-style hooks)
- Auto-discovery (`/plugins/` directory)
- PSR-4 autoloading

**Plugin Structure:**

```
plugins/
├── newsletter/
│   ├── plugin.json (metadata)
│   ├── src/
│   │   ├── NewsletterServiceProvider.php
│   │   ├── Models/
│   │   ├── Controllers/
│   │   └── Events/
│   ├── routes.php
│   ├── views/
│   └── assets/
```

**Plugin Metadata (plugin.json):**

```json
{
    "name": "Newsletter",
    "slug": "newsletter",
    "version": "1.0.0",
    "author": "PineCMS Team",
    "requires": "1.1.0",
    "description": "Newsletter subscription and campaign management"
}
```

**Plugin Features:**

- Activation/deactivation UI
- Plugin settings pages
- Plugin events (Laravel Events):
    - `ContentSaving`, `ContentSaved`
    - `UserLoggedIn`, `UserLoggedOut`
    - `CommentPosted`, `CommentModerated`
    - `PageRendering`, `PageRendered`

**Plugin Management UI:**

- Plugin list (installed, available)
- Install/uninstall buttons
- Activate/deactivate toggle
- Plugin settings link

---

#### Workflow System

**Content Status Workflow:**

- Draft → Review → Published
- Reviewer assignment (1 reviewer per post)
- Approval/rejection with feedback
- Workflow history (audit trail)

**Email Notifications:**

- Review request (notify reviewer)
- Approval (notify author)
- Rejection (notify author with feedback)

**Workflow UI:**

- "Submit for Review" button (authors)
- "Approve/Reject" buttons (reviewers)
- Workflow status badge (draft, in review, approved, rejected)
- Workflow history timeline (show all status changes)

---

### 4.6 Week 10-13: Official Plugins (Parallel Development)

**Status:** 📋 Planned | **Priority:** 🟡 Medium | **Type:** 🔌 Plugin

#### Plugin 1: Newsletter Plugin (~6 features)

**Features:**

- Newsletter editor (TipTap)
- Subscriber management (CRUD, import/export CSV)
- Subscriber groups/tags
- Sign-up widget (shortcode, Blade component)
- Campaign scheduling (send now, schedule)
- Email templates (3 responsive templates)
- Privacy-friendly tracking (open rate, click rate - optional)
- Double opt-in (GDPR compliant)
- Subscriber segmentation (tags/categories)

**Newsletter Builder:**

- Drag & drop editor (TipTap-based)
- Pre-built templates (welcome, announcement, newsletter)
- Preview mode (desktop, mobile)
- Test email (send to admin)

---

#### Plugin 2: Multi-Language Plugin (~8 features)

**Features:**

- Multi-language support (unlimited languages)
- Content translation UI (side-by-side editor)
- Translatable fields (post/page, categories/tags, SEO meta)
- Locale switcher (dropdown, flags, auto-detect browser language)
- Language-specific URLs (prefix `/en/`, `/de/`, subdirectory)
- Fallback language (default to English if translation missing)
- RTL support (Arabic, Hebrew)
- SEO for multi-language (hreflang tags, sitemap, RSS per language)

**Translation Workflow:**

- Create post in default language
- Click "Add Translation" button
- Side-by-side editor (original | translation)
- Copy metadata (categories, tags, featured image)

---

#### Plugin 3: SEO+ Plugin (~3 features)

**Features:**

- Schema.org structured data (Article, Blog, Organization, Breadcrumbs)
- Open Graph & Twitter Cards (enhanced with preview)
- Advanced sitemap (image sitemap, video sitemap, news sitemap)
- Fix link UI (edit link inline)

---

### 4.7 v1.1.0 Success Criteria

**Functional Requirements:**

- ✅ Comment system works with moderation queue
- ✅ Search finds posts/pages/tags with filters
- ✅ 4-role RBAC system works (Administrator, Author, User, Guest)
- ✅ Bulk actions functional (select, publish, delete, export)
- ✅ Workflow system functional (draft → review → published)
- ✅ Plugin system works (discover, install, activate/deactivate)
- ✅ All 3 official plugins installable & functional

**Feature Requirements:**

- ✅ **~149 Core Features** (+54 from v1.0.0)
- ✅ **~17 Plugin Features** (3 official plugins)
- ✅ **Total: 170 Features**

**Quality Requirements:**

- ✅ Performance maintained (< 1s page load)
- ✅ WCAG 2.1 AA compliant
- ✅ Zero critical bugs

---

## 5. Version 1.2.0 - Advanced Features

### 5.1 Overview

**Duration:** +2-3 Weeks (Weeks 14-15)
**Goal:** Advanced CMS features for professional users
**Core Features:** +10 (Total: ~159)
**Plugin Features:** ~17 (same as v1.1.0)
**Total Features:** 176
**Status:** 📋 Planned

**Release Criteria:**

- ✅ Import/export enhancements functional
- ✅ Advanced SEO features working
- ✅ Redirect management functional
- ✅ Menu builder creates navigation
- ✅ Widget system functional
- ✅ Custom routing works

---

### 5.2 Week 14: Import/Export Enhancements & Advanced SEO

**Status:** 📋 Planned | **Priority:** 🟡 Medium | **Type:** 🔧 Enhancement

**Note:** Basic Import/Export is in v1.0.0 (Week 9) - This section adds enhancements

#### Import/Export Enhancements

**Import Features:**

- Import error handling & retry (failed imports)
- Advanced content mapping (map custom fields)
- Import from Medium (via RSS)
- Import from Substack (JSON export)
- Scheduled exports (automatic weekly/monthly backups to local storage)

**Export Features:**

- Export templates (save mapping configurations)
- Export preview (before export, show what will be exported)

---

#### Advanced SEO

**Schema.org Structured Data:**

- Article schema (blog posts)
- Blog schema (blog index)
- Organization schema (site-wide)
- Breadcrumb schema (navigation)

**Open Graph & Twitter Cards:**

- Enhanced preview (live preview in admin)
- Custom OG images per post
- Twitter card type selection (summary, large image)

**Advanced Sitemap:**

- Image sitemap (all images in posts)
- Priority settings (per post/page)
- Change frequency settings

**Custom Meta Tags:**

- Custom meta tags per post/page (robots, viewport, etc.)
- Meta tag preview (show how it appears in search results)

---

### 5.3 Week 15: Advanced Features

**Status:** 📋 Planned | **Priority:** 🟡 Medium | **Type:** 🔧 Enhancement

#### Redirect Management

**Redirect CRUD:**

- 301 redirects (permanent)
- 302 redirects (temporary)
- Bulk import (CSV upload)
- Wildcard redirects (`/old-category/*` → `/new-category/*`)
- Redirect history (track all redirects)

**Redirect UI:**

- Source URL (old URL)
- Target URL (new URL)
- Redirect type (301, 302)
- Enable/disable toggle

---

#### Menu Builder

**Menu Features:**

- Drag & drop menu creation
- Menu locations (header, footer, sidebar)
- Menu items:
    - Pages
    - Posts
    - Categories
    - Tags
    - Custom links (external URLs)
- Nested items (unlimited depth)
- Menu item options:
    - CSS class (custom styling)
    - Target (`_blank`, `_self`)
    - Icon (Lucide icon picker)

**Menu Management:**

- Multiple menus (create unlimited menus)
- Assign menu to location (header, footer)
- Menu preview (show how menu appears)

---

#### Widget System

**Widget Areas:**

- Sidebar (left, right)
- Footer (3 columns)
- Custom widget areas (theme-specific)

**Default Widgets:**

- Recent Posts (configurable count)
- Popular Posts (by view count)
- Categories (list, dropdown)
- Tags (cloud, list)
- Custom HTML (for ads, custom content)
- Search (search bar widget)

**Widget Management:**

- Drag & drop widget placement
- Widget settings (per widget)
- Widget preview (show how widget appears)

---

#### Custom Routing

**URL Customization:**

- Multi-language URL prefixes (`/en/`, `/de/`)
- Hierarchical URLs (`/blog/category/subcategory/post`)
- Custom post type URLs (`/portfolio/{slug}`)
- Route wildcards (`/{year}/{month}/{slug}`)

**Custom Routes UI:**

- Route pattern editor
- Route preview (show generated URL)
- Route validation (check for conflicts)

---

### 5.4 v1.2.0 Success Criteria

**Functional Requirements:**

- ✅ Import/export works (WordPress, Ghost, Markdown, Medium, Substack)
- ✅ Advanced SEO features functional (schema.org, enhanced OG/Twitter cards)
- ✅ Redirect management works (301/302, wildcard, bulk import)
- ✅ Menu builder creates navigation (drag & drop, nested items)
- ✅ Widget system functional (sidebar, footer widgets)
- ✅ Custom routing works (multi-language prefixes, hierarchical URLs)

**Feature Requirements:**

- ✅ **~159 Core Features** (+10 from v1.1.0)
- ✅ **~17 Plugin Features** (Newsletter, Multi-Language, SEO+)
- ✅ **Total: 183 Features**

**Quality Requirements:**

- ✅ Performance maintained (< 1s page load)
- ✅ WCAG 2.1 AA compliant
- ✅ Zero critical bugs
- ✅ Code coverage > 80%

---

## 6. Release Preparation

### 6.1 Pre-Release Checklist (Weeks 16-18)

**Duration:** 1-2 Weeks
**Status:** 📋 Planned
**Priority:** 🔴 Critical

#### Security Audit

- [ ] Code review (all critical paths)
- [ ] Penetration testing (OWASP Top 10 compliance)
- [ ] Dependency vulnerability scan (`composer audit`, `npm audit`)
- [ ] Security headers validation
- [ ] CSRF/XSS protection verification

---

#### Performance Testing

**Shared Hosting Test:**

- [ ] Test on 3 shared hosting providers (Hostinger, Bluehost, SiteGround)
- [ ] Page load < 1 second (P95)
- [ ] Database queries < 100ms (P95)
- [ ] Memory usage < 128MB per request

**Load Testing:**

- [ ] 100 concurrent users (Apache Bench)
- [ ] 1000 posts (database performance)
- [ ] 5000 media files (file system performance)

**Lighthouse Scores:**

- [ ] Performance > 90
- [ ] Accessibility > 90
- [ ] Best Practices > 90
- [ ] SEO > 90

---

#### Cross-Browser Testing

**Desktop Browsers:**

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)

**Mobile Browsers:**

- [ ] iOS Safari (latest)
- [ ] Android Chrome (latest)

**Accessibility Testing:**

- [ ] NVDA screen reader (Windows)
- [ ] JAWS screen reader (Windows)
- [ ] VoiceOver (macOS, iOS)
- [ ] Keyboard-only navigation

---

#### Documentation

**User Documentation:**

- [ ] Installation guide (step-by-step)
- [ ] User guide (create post, upload media, settings)
- [ ] Admin panel guide (all features)
- [ ] FAQ (common issues, troubleshooting)

**Developer Documentation:**

- [ ] Plugin development guide (API, events, examples)
- [ ] Theme development guide (Blade templates, Alpine.js, TailwindCSS)
- [ ] API documentation (REST API, if enabled)
- [ ] Contributing guide (CONTRIBUTING.md)

**Legal:**

- [ ] MIT License file (LICENSE)
- [ ] Code of Conduct (CODE_OF_CONDUCT.md)

---

#### Demo & Community

**Demo Site:**

- [ ] Public demo site (demo.pinecms.org)
- [ ] Admin demo access (view-only)
- [ ] Sample content (10 blog posts, 5 pages)

**Community Setup:**

- [ ] GitHub repository public
- [ ] GitHub Discussions enabled
- [ ] Community guidelines (CONTRIBUTING.md)
- [ ] Issue templates (bug report, feature request)

---

### 6.2 Release Artifacts

**Core CMS:**

- `pinecms-v1.0.0.zip` (Core Only, ~95 features)
- `pinecms-v1.1.0.zip` (Core + Plugin System, ~149 features)
- `pinecms-v1.2.0.zip` (Core + All Features, ~159 features)

**Official Plugins:**

- `newsletter-v1.0.0.zip` (~6 features)
- `multi-language-v1.0.0.zip` (~8 features)
- `seo-plus-v1.0.0.zip` (~3 features)

**Documentation:**

- `README.md` (installation guide)
- `docs/` (user guide, developer guide)

**Themes:**

- 1 default theme (included in core)

**Sample Content:**

- `sample-content.zip` (10 blog posts, 5 pages, demo images)

---

## 7. Future (v2.0+)

### 7.1 Potential Features (Community-Driven)

**Status:** 📋 Planned | **Type:** 🧪 Experimental

**Advanced Features:**

- GraphQL API (as premium plugin or core)
- Multi-site support (network)
- E-commerce plugin (Stripe, PayPal integration)
- Advanced analytics plugin (heatmaps, A/B testing)
- Page builder plugin (drag & drop)
- Membership/subscription plugin
- Forum/community plugin
- Advanced form builder (calculated fields, payments)
- Advanced SEO (Rank Math/Yoast level)
- Performance plugin (advanced caching, CDN)

**Note:** v2.0 features depend on community feedback and adoption.

---

## 8. Success Metrics

### 8.1 Technical Metrics

**Performance:**

- ✅ Loads on shared hosting (< 1 second page load)
- ✅ SQLite database (< 50MB for 1000 posts)
- ✅ Flat-files (< 100MB for 1000 posts)
- ✅ Lighthouse score (> 90/100)

**Quality:**

- ✅ Zero critical bugs at release
- ✅ WCAG 2.1 AA compliant
- ✅ OWASP Top 10 2024 compliant
- ✅ Code coverage > 80%
- ✅ PHPStan Level 8 passing

---

### 8.2 Feature Metrics

**v1.0.0:**

- ✅ ~95 Core Features
- ✅ Full mobile responsiveness
- ✅ Web installer functional

**v1.1.0:**

- ✅ ~149 Core Features
- ✅ ~21 Plugin Features (3 official plugins)
- ✅ Total: 170 Features

**v1.2.0:**

- ✅ ~159 Core Features
- ✅ ~17 Plugin Features (Newsletter, Multi-Language, SEO+)
- ✅ Total: 176 Features

---

### 8.3 Community Metrics

**Year 1 Targets (2025):**

- 500-1,000 active installations
- 50+ GitHub stars
- 5+ community contributors
- Active community forum

**Year 2 Targets (2026):**

- 2,000-3,000 active installations
- 10+ community plugins
- Self-sustaining (donations/services)

**Year 5 Targets (2030):**

- 5,000-10,000 loyal users
- 20+ community plugins
- Stable, reliable platform
- Regular monthly releases

---

## 9. Risk Management

### 9.1 Technical Risks

**Risk 1: Shared Hosting Performance**

- **Likelihood:** Medium
- **Impact:** High
- **Mitigation:** Test on 3+ shared hosting providers, optimize SQLite queries, implement caching

**Risk 2: Plugin System Complexity**

- **Likelihood:** Medium
- **Impact:** Medium
- **Mitigation:** Simple event-driven architecture, comprehensive plugin development guide

**Risk 3: Import/Export Data Loss**

- **Likelihood:** Low
- **Impact:** High
- **Mitigation:** Extensive testing, import preview, automatic backups before import

---

### 9.2 Timeline Risks

**Risk 1: Scope Creep**

- **Likelihood:** High
- **Impact:** High
- **Mitigation:** Strict adherence to PRD, YAGNI principle, defer non-essential features to v2.0

**Risk 2: Underestimated Complexity**

- **Likelihood:** Medium
- **Impact:** Medium
- **Mitigation:** 10-11 week buffer for v1.0.0 (was 8-10 weeks), realistic timeline estimates

---

### 9.3 Adoption Risks

**Risk 1: Low Initial Adoption**

- **Likelihood:** Medium
- **Impact:** Medium
- **Mitigation:** Focus on privacy-conscious communities, import/export tools, quality over quantity

**Risk 2: Difficulty Finding Sustainable Funding**

- **Likelihood:** High
- **Impact:** Medium
- **Mitigation:** Volunteer-driven Year 1-2, optional donations, managed hosting service (future)

---

## 10. Change History

| Date       | Version | Author       | Changes                                                                  |
| ---------- | ------- | ------------ | ------------------------------------------------------------------------ |
| 2025-11-08 | 1.0     | PineCMS Team | Initial release roadmap (v1.0.0-v1.2.0, 15-18 weeks, 3 official plugins) |

---

**Last Updated:** 2025-11-08
**Document Owner:** PineCMS Team
**Next Review:** End of v1.0.0 (Week 11)

---

## Appendix A: Feature Count History

**2025-11-06 - SEP Analysis:**

- v1.0.0: 90 → 95 Features (Net: +5)
    - Added (+15): Hierarchical Categories, Template Selection, Excerpt, Reading Time, Import/Export, Frontend Event System, Concurrent Editing Protection, UTC Storage, Upload Security (5-Layer), Revision Enhancements, Media Settings, Duplicate Post, Email Templates, Bulk Status Change
    - Removed (-10): Upvote/Downvote (→ Community Plugin), Social Sharing (→ Theme), Breadcrumbs (→ Theme), Lightbox (→ Theme), Advanced Analytics, Multi-Level Comments (>3 levels), Advanced SEO (→ SEO+ Plugin), Dashboard Widgets (→ Plugin System)

**2025-11-08 - PRD Finalization:**

- Matomo Analytics completely removed (was in v1.1.0 core)
- Custom Fields Pro removed from official plugins (→ Community Plugin)
- SEO+ Plugin in v1.1.0
- **Official Plugins reduced: 4 → 3 plugins**
- **Plugin Features reduced: ~60 → ~17 features (72% reduction)**

---

## Appendix B: Status System

**Status Markers:**

- ✅ **Completed** - Feature implemented and tested
- 🚧 **In Progress** - Feature currently being developed
- 📋 **Planned** - Feature planned, not yet started
- ⏸️ **Paused** - Development temporarily paused
- ❌ **Removed** - Feature removed from scope

**Priority Markers:**

- 🔴 **Critical** - Blocker for release (must be done)
- 🟠 **High** - Important, should be in release
- 🟡 **Medium** - Desirable, can be deferred
- 🟢 **Low** - Nice-to-have, optional

**Type Markers:**

- 🎯 **Core Feature** - Core functionality (Core CMS)
- 🔧 **Enhancement** - Improvement/extension
- 🔌 **Plugin** - Plugin feature
- 🧪 **Experimental** - Beta/testing
