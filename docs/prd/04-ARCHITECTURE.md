# 04 - Technical Architecture

**Version:** 1.0.0
**Last Updated:** 2025-11-07
**Status:** 📋 Planned
**Owner:** PineCMS Team

---

## 1. Executive Summary

This document defines the **technical architecture** for PineCMS, a Laravel 12-based, privacy-first, flat-file hybrid CMS. The architecture prioritizes sustainability, performance, security, and maintainability for a 5-10 year lifespan.

**Core Architectural Principles:**

- **Hybrid Storage:** SQLite database + flat-file Markdown (best of both worlds)
- **Event-Driven:** Laravel Events (NOT WordPress-style hooks)
- **Layered Architecture:** Controllers → Services → Repositories → Models
- **SOLID Principles:** Single Responsibility, Open/Closed, Liskov, Interface Segregation, Dependency Inversion
- **Shared Hosting Compatible:** No Node.js server, no Redis required, < 512MB RAM
- **Performance First:** < 1 second page load, < 100ms database queries
- **Security by Design:** Field-level encryption, EXIF stripping, OWASP Top 10 compliance

---

## 2. System Architecture Overview

### 2.1 High-Level Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                         PUBLIC FRONTEND                         │
│  (Blade Templates + Alpine.js 3 + TailwindCSS 4)               │
└─────────────────────────────────────────────────────────────────┘
                              ▲
                              │ HTTP/HTTPS
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      ADMIN PANEL (SPA)                          │
│  (Vue 3.5 + Inertia.js 2.x + PrimeVue + Pinia + TailwindCSS)  │
└─────────────────────────────────────────────────────────────────┘
                              ▲
                              │ HTTP/HTTPS + API
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      APPLICATION LAYER                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │ Controllers  │──│   Services   │──│ Repositories │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
│         │                 │                  │                  │
│         ▼                 ▼                  ▼                  │
│  ┌────────────────────────────────────────────────┐            │
│  │            EVENT SYSTEM (Laravel)              │            │
│  │  PostPublished, PostUpdated, MediaUploaded    │            │
│  └────────────────────────────────────────────────┘            │
└─────────────────────────────────────────────────────────────────┘
                              ▲
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     STORAGE LAYER                               │
│  ┌─────────────────┐              ┌─────────────────┐          │
│  │ SQLite Database │              │   Flat-Files    │          │
│  │  (Relational)   │              │   (Markdown)    │          │
│  │ • users         │              │ • Posts (.md)   │          │
│  │ • categories    │              │ • Pages (.md)   │          │
│  │ • tags          │              │ YAML front      │          │
│  │ • media         │              │ matter          │          │
│  │ • settings      │              │                 │          │
│  └─────────────────┘              └─────────────────┘          │
└─────────────────────────────────────────────────────────────────┘
                              ▲
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                   INFRASTRUCTURE LAYER                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │ File System  │  │ Queue System │  │ Cache (file) │         │
│  │ (Local/FTP)  │  │  (Database)  │  │              │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
└─────────────────────────────────────────────────────────────────┘
```

---

### 2.2 Component Interaction Flow

**Example: User Creates Blog Post**

```
1. Admin Panel (Vue/Inertia)
   ↓ POST /admin/posts

2. PostController@store
   ↓ Validates request (FormRequest)
   ↓ Calls PostService

3. PostService@createPost()
   ↓ Business logic (slug generation, sanitization)
   ↓ Calls PostRepository

4. PostRepository@create()
   ↓ Saves relational data to SQLite (metadata)
   ↓ Saves content to flat-file (Markdown + YAML)

5. Event Dispatched: PostCreated
   ↓ Listeners execute (e.g., ClearCacheListener, NotifyAdminListener)

6. Response (Inertia.js)
   ↓ Redirect to /admin/posts with success message
```

---

## 3. Hybrid Storage Model

### 3.1 Why Hybrid Storage?

**Problem:** Traditional CMS architectures have trade-offs:

- **Pure Database (WordPress, Drupal):** Fast queries, but content locked in database (not portable, hard to version control)
- **Pure Flat-File (Grav, Kirby):** Portable content, but slow queries for relational data (categories, tags, users)

**PineCMS Solution: Hybrid Approach**

- **SQLite Database:** Relational data (users, categories, tags, media metadata, relationships)
- **Flat-Files (Markdown):** Content (post body, page content, YAML front matter)

**Benefits:**

- ✅ Fast relational queries (< 100ms) via SQLite indexes
- ✅ Portable content (Markdown files, Git-friendly for developers)
- ✅ Backup simplicity (FTP download entire directory)
- ✅ Version control friendly (Git tracks content changes)
- ✅ No vendor lock-in (Markdown universally supported)

---

### 3.2 Storage Breakdown

| Data Type              | Storage                                   | Why                                          |
| ---------------------- | ----------------------------------------- | -------------------------------------------- |
| **Post/Page Content**  | Flat-File (`.md`)                         | Portable, version control, Markdown standard |
| **Post/Page Metadata** | SQLite (`posts` table)                    | Fast queries, indexing, relationships        |
| **Categories/Tags**    | SQLite (`categories`, `tags` tables)      | Relational queries (JOIN), filters           |
| **Users**              | SQLite (`users` table)                    | Authentication, RBAC, sensitive data         |
| **Media Files**        | File System (`/storage/media/`)           | Binary files (images, PDFs)                  |
| **Media Metadata**     | SQLite (`media` table)                    | Fast search, filtering, alt text             |
| **Settings**           | SQLite (`settings` table)                 | Key-value store, fast lookups                |
| **Sessions**           | SQLite (`sessions` table)                 | Shared hosting compatible (no Redis)         |
| **Cache**              | File System (`/storage/framework/cache/`) | No Redis required                            |
| **Queue Jobs**         | SQLite (`jobs` table)                     | Background processing (emails, exports)      |

---

### 3.3 Flat-File Structure

**Directory Structure:**

```
storage/
├── content/
│   ├── posts/
│   │   ├── 2025/
│   │   │   ├── 01-my-first-post.md
│   │   │   ├── 02-privacy-matters.md
│   │   │   └── 03-minimalist-blogging.md
│   │   └── 2024/
│   │       └── 12-year-in-review.md
│   └── pages/
│       ├── about.md
│       ├── contact.md
│       └── privacy-policy.md
├── media/
│   ├── originals/       # Original uploaded images
│   ├── thumbnails/      # Auto-generated thumbnails
│   └── optimized/       # WebP optimized versions
└── backups/             # Optional backup exports
```

**Markdown File Format (YAML Front Matter):**

````markdown
---
id: 123
title: 'My First Post'
slug: 'my-first-post'
author_id: 1
category_id: 5
tags: [privacy, blogging]
status: published
published_at: '2025-11-07 14:30:00'
seo_title: 'My First Post - Privacy-First Blogging'
seo_description: 'Learn about privacy-first blogging with PineCMS'
featured_image: '/media/2025/01/header.jpg'
excerpt: 'This is a short excerpt of the post...'
---

# My First Post

This is the **content** of the post written in Markdown.

- Bullet points
- **Bold text**
- _Italic text_

![Alt text](/media/2025/01/image.jpg)

## Code Example

```php
echo "Hello, PineCMS!";
```
````

````

---

### 3.4 Synchronization Strategy

**How Hybrid Storage Stays in Sync:**

**Write Operations (Create/Update Post):**
1. Controller receives request → validates
2. Service performs business logic
3. Repository executes:
   - **SQLite Write:** Insert/update `posts` table (metadata, relationships)
   - **Flat-File Write:** Save Markdown file (`storage/content/posts/YYYY/slug.md`)
4. Both writes wrapped in **database transaction** (rollback if file write fails)
5. Event dispatched: `PostCreated` or `PostUpdated`

**Read Operations (Display Post):**
1. **Fast Listing:** Query SQLite only (titles, dates, authors) → No file reads
2. **Full Post Display:**
   - Query SQLite (metadata, relationships)
   - Read flat-file (content body)
   - Merge data → Return to view

**Caching Strategy:**
- SQLite queries cached (file-based cache, 60 minutes)
- Rendered Markdown cached (avoid re-parsing)
- Cache invalidation on `PostUpdated` event

**Backup/Restore:**
- **Full Backup:** FTP download entire PineCMS directory (SQLite + flat-files)
- **Restore:** Upload backup, overwrite directory
- **Integrity Check:** Artisan command `php artisan pinecms:sync` (re-sync SQLite ↔ Flat-Files)

---

## 4. Technology Stack

### 4.1 Backend Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **PHP** | 8.3+ | Core programming language |
| **Laravel** | 12.x | Web application framework |
| **SQLite** | 3.x | Primary database (relational data) |
| **MySQL** | 8.0+ (optional) | Alternative database (larger sites) |
| **Laravel Fortify** | Latest | Authentication (login, registration, password reset) |
| **Laravel Sanctum** | 4.x | API token authentication (future API) |
| **Laravel Gates & Policies** | Built-in | RBAC (role-based access control) |
| **CipherSweet** | Latest | Field-level encryption (sensitive data) |
| **Intervention Image** | Latest | Image manipulation (resize, optimize, EXIF strip) |
| **League CommonMark** | Latest | Markdown parsing (flat-file content) |
| **Spatie Laravel Permissions** | Latest | Advanced RBAC (roles, permissions) |
| **Spatie Laravel Backup** | Latest (v1.2.0) | Automated backups |
| **Spatie Laravel Media Library** | Latest (v1.1.0) | Media management, conversions |

---

### 4.2 Frontend Stack

#### Public Frontend

| Technology | Version | Purpose |
|------------|---------|---------|
| **Blade Templates** | Laravel 12 | Server-rendered views |
| **Alpine.js** | 3.x | Lightweight interactivity (dropdowns, modals) |
| **TailwindCSS** | 4.x | Utility-first CSS framework |
| **Vite** | Latest | Build tool (CSS/JS bundling) |

**Why Blade + Alpine for Public?**
- ✅ Fast server-rendered HTML (SEO-friendly)
- ✅ Minimal JavaScript footprint (< 50KB)
- ✅ Progressive enhancement (works without JS)
- ✅ Shared hosting compatible (no Node.js server)

---

#### Admin Panel (SPA)

| Technology | Version | Purpose |
|------------|---------|---------|
| **Vue.js** | 3.5+ | Progressive JavaScript framework |
| **Inertia.js** | 2.x | SPA without API (Laravel ↔ Vue bridge) |
| **PrimeVue** | Latest | Vue UI component library (DataTable, Calendar, etc.) |
| **Pinia** | Latest | State management (Vuex replacement) |
| **TailwindCSS** | 4.x | Utility-first CSS |
| **TipTap** | Latest | WYSIWYG editor (headless, extensible) |
| **Vite** | Latest | Build tool (HMR, fast builds) |

**Why Vue + Inertia for Admin?**
- ✅ Modern SPA experience (fast, reactive UI)
- ✅ No separate API required (Inertia bridges Laravel ↔ Vue)
- ✅ Server-rendered first load (SEO for admin if needed)
- ✅ Laravel session authentication (no JWT complexity)
- ✅ PrimeVue provides rich components (DataTable, Calendar, Dropdown)

---

### 4.3 Testing & Quality Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **PHPUnit** | 12.x | Unit & feature testing (backend) |
| **Vitest** | Latest | Unit testing (frontend Vue components) |
| **Playwright** | Latest | End-to-end testing (browser automation) |
| **PHPStan** | Latest (Level 8) | Static analysis (type safety) |
| **Laravel Pint** | Latest | Code formatter (PSR-12) |
| **ESLint** | 9.x | JavaScript linter |
| **Prettier** | 3.x | JavaScript formatter |

**Quality Gates:**
- ✅ PHPUnit: > 80% code coverage
- ✅ PHPStan: Level 8 (strictest type checking)
- ✅ Playwright: E2E tests for critical user flows
- ✅ Vitest: Component tests for Vue admin

---

## 5. Architectural Decision Records (ADRs)

### ADR-001: Hybrid Storage (SQLite + Flat-Files)

**Status:** ✅ Accepted

**Context:**
- Pure database CMSs (WordPress) lock content in database (not portable)
- Pure flat-file CMSs (Grav, Kirby) have slow relational queries

**Decision:**
- Use **SQLite** for relational data (users, categories, tags, media metadata)
- Use **Flat-Files (Markdown + YAML)** for content (post/page body)

**Consequences:**
- ✅ Fast relational queries (< 100ms) via SQLite indexes
- ✅ Portable content (Markdown files, backup via FTP)
- ✅ Version control friendly (Git tracks content changes)
- ⚠️ Complexity: Sync required between SQLite ↔ Flat-Files
- ⚠️ Transaction handling: Database transaction + file write atomicity

**Alternatives Considered:**
- Pure MySQL: Rejected (content not portable, vendor lock-in)
- Pure Flat-File: Rejected (slow relational queries, no indexing)

---

### ADR-002: Event-Driven Architecture (Laravel Events)

**Status:** ✅ Accepted

**Context:**
- WordPress uses "hooks" (actions/filters) → spaghetti code, hard to debug
- Need extensibility for plugins without core modifications

**Decision:**
- Use **Laravel Event System** (Event::dispatch(), Event::listen())
- Define core events: `PostCreated`, `PostUpdated`, `PostDeleted`, `MediaUploaded`, etc.
- Plugins register event listeners in `EventServiceProvider`

**Consequences:**
- ✅ Clean separation of concerns (events vs. listeners)
- ✅ Easy debugging (event listeners visible in `EventServiceProvider`)
- ✅ Testable (mock events, assert dispatched)
- ✅ No global state pollution (unlike WordPress hooks)
- ⚠️ Requires Laravel knowledge (not familiar to WordPress developers)

**Alternatives Considered:**
- WordPress-style hooks: Rejected (global state, spaghetti code)
- Observer pattern: Considered (but Laravel Events are built-in observers)

---

### ADR-003: Web Installer Only (No Composer Requirement)

**Status:** ✅ Accepted

**Context:**
- Non-technical users (Alex, Maria personas) need easy installation
- Shared hosting users may not have SSH/Composer access
- Web installers proven (WordPress, October CMS, Joomla)

**Decision:**
- Provide **web-based installer** (like WordPress)
- Upload ZIP via FTP → Extract → Visit `/install` → Complete form
- NO Composer installation requirement for end-users

**Consequences:**
- ✅ Accessible to non-technical users (95%+ installation success rate)
- ✅ Familiar workflow (WordPress-like experience)
- ✅ Shared hosting compatible (no CLI required)
- ⚠️ Developers (David persona) may prefer Composer (trade-off accepted)
- ⚠️ Manual dependency bundling (vendor folder included in ZIP)

**Alternatives Considered:**
- Composer-only: Rejected (non-technical users can't install)
- Dual deployment: Rejected (complexity, maintenance burden)

---

### ADR-004: SQLite as Primary Database (MySQL Optional)

**Status:** ✅ Accepted

**Context:**
- Shared hosting often limits MySQL databases (1-5 databases)
- SQLite requires zero configuration (file-based)
- Performance sufficient for < 10,000 posts

**Decision:**
- **SQLite** as primary/default database
- **MySQL** supported as optional (larger sites, multi-server)

**Consequences:**
- ✅ Zero MySQL configuration (file-based database)
- ✅ Fast queries (< 100ms) for < 10,000 posts
- ✅ FTP-friendly backups (download entire directory)
- ✅ No separate database server required
- ⚠️ SQLite limitations: No concurrent writes (acceptable for small sites)
- ⚠️ Database size limit: < 50MB for 1,000 posts (monitored)

**Alternatives Considered:**
- MySQL-only: Rejected (configuration complexity, hosting limits)
- PostgreSQL: Rejected (less common on shared hosting)

---

### ADR-005: No Auto-Update System (v1.0-1.2)

**Status:** ✅ Accepted

**Context:**
- Auto-update systems complex (file permissions, rollback, security)
- Non-technical users (Maria) fear updates breaking sites
- Manual updates via FTP well-understood workflow

**Decision:**
- **Manual updates only** (v1.0.0 - v1.2.0)
- Download new version → Upload via FTP → Visit `/update`
- Update wizard runs migrations (if needed)
- (Future: One-click updates in v2.0+)

**Consequences:**
- ✅ Simple implementation (no complex update system)
- ✅ User control (updates on their schedule)
- ✅ Rollback easy (restore FTP backup)
- ⚠️ Manual process (takes 5-10 minutes)
- ⚠️ Some users may delay updates (security risk)

**Alternatives Considered:**
- WordPress-style auto-update: Rejected (complexity, file permission issues)
- GitHub API updates: Rejected (requires GitHub integration)

---

### ADR-006: TipTap WYSIWYG Editor (Not Gutenberg)

**Status:** ✅ Accepted

**Context:**
- WordPress Gutenberg complex, slow, block-based (not universally loved)
- Minimalist users (Alex) want distraction-free writing
- Need Markdown support for developer-friendly workflow

**Decision:**
- Use **TipTap** (headless, extensible WYSIWYG editor)
- Support Markdown shortcuts (`**bold**`, `# heading`)
- Fullscreen mode for distraction-free writing

**Consequences:**
- ✅ Modern, fast, extensible editor (Vue 3 compatible)
- ✅ Markdown shortcuts work seamlessly
- ✅ Fullscreen mode (distraction-free)
- ✅ Lightweight (< 100KB minified)
- ⚠️ Not block-based (page builders require plugin)

**Alternatives Considered:**
- Gutenberg: Rejected (complex, slow, React-based)
- CKEditor: Rejected (jQuery dependency, legacy)
- Quill: Considered (but TipTap more extensible)

---

### ADR-007: Inertia.js for Admin Panel (No Separate API)

**Status:** ✅ Accepted

**Context:**
- Traditional SPA requires separate REST/GraphQL API (doubled development effort)
- API versioning, authentication complexity (JWT, OAuth2)
- PineCMS is monolithic, not microservices

**Decision:**
- Use **Inertia.js** (SPA without API)
- Laravel returns Inertia responses (JSON + page component)
- Vue renders admin panel (reactive, SPA-like UX)

**Consequences:**
- ✅ No separate API required (faster development)
- ✅ Laravel session authentication (no JWT complexity)
- ✅ Server-rendered first load (fast initial load)
- ✅ SPA-like experience (reactive, no page reloads)
- ⚠️ Headless CMS mode requires separate API (v2.0+ if needed)

**Alternatives Considered:**
- REST API + Vue SPA: Rejected (doubled development, auth complexity)
- Livewire: Rejected (too much magic, prefer Vue for admin)

---

### ADR-008: No Default Analytics Tracking

**Status:** ✅ Accepted

**Context:**
- Privacy-conscious users (Sarah persona) distrust tracking by default
- GDPR requires explicit consent for cookies/tracking
- 77% of users distrust social media platforms with data

**Decision:**
- **No default analytics** (no Google Analytics, no tracking scripts)
- Users can integrate their own analytics via community plugins or theme event listeners
- Zero cookies without user consent

**Consequences:**
- ✅ Privacy-first by design (competitive advantage)
- ✅ GDPR compliant by default (no cookie banners needed)
- ✅ Trust from privacy-conscious users (key persona)
- ⚠️ No out-of-the-box analytics (users integrate their preferred solution)

**Alternatives Considered:**
- Google Analytics by default: Rejected (privacy violation)
- Bundled Matomo in core: Rejected (feature bloat)
- Official Matomo plugin: Rejected (analytics best handled by community)

---

### ADR-009: Field-Level Encryption (CipherSweet)

**Status:** ✅ Accepted

**Context:**
- Privacy-conscious users (Sarah) need encrypted sensitive data (source names, notes)
- Database backups expose plaintext content
- WordPress has no built-in field encryption

**Decision:**
- Use **CipherSweet** (searchable field-level encryption)
- Encrypt custom fields marked "sensitive"
- Database stores ciphertext (not plaintext)

**Consequences:**
- ✅ Sensitive data encrypted at rest (database backups safe)
- ✅ Searchable encryption (unlike full-disk encryption)
- ✅ Transparent to users (encrypt/decrypt automatic)
- ⚠️ Performance overhead (10-20ms per encrypted field)
- ⚠️ Key management critical (stored in `.env`, must be backed up)

**Alternatives Considered:**
- No encryption: Rejected (privacy persona critical need)
- Full-disk encryption: Rejected (not searchable, shared hosting lacks support)

---

### ADR-010: Manual Queue Processing (Shared Hosting)

**Status:** ✅ Accepted

**Context:**
- Shared hosting lacks daemon support (no `queue:work` process)
- Background jobs needed (email sending, backups, exports)
- Cron jobs widely supported on shared hosting

**Decision:**
- Use **database queue driver** (SQLite `jobs` table)
- Process queue via **cron job** (`php artisan queue:work --once`)
- Run every 5 minutes via shared hosting cron panel

**Consequences:**
- ✅ Shared hosting compatible (no daemon required)
- ✅ Simple setup (one cron job)
- ✅ Reliable processing (Laravel's queue system)
- ⚠️ 5-minute delay for background jobs (acceptable trade-off)
- ⚠️ No real-time processing (use sync driver for immediate tasks)

**Alternatives Considered:**
- Redis queue: Rejected (shared hosting lacks Redis)
- Supervisor daemon: Rejected (shared hosting lacks daemon support)

---

## 6. Infrastructure Requirements

### 6.1 Minimum Server Requirements

**For Shared Hosting (Target Environment):**

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| **PHP Version** | 8.3 | 8.4+ |
| **Memory Limit** | 256 MB | 512 MB |
| **Max Execution Time** | 60s | 120s |
| **Max Upload Size** | 10 MB | 50 MB |
| **Disk Space** | 500 MB | 2 GB |
| **SQLite** | Enabled | Enabled |
| **MySQL** | Optional | 8.0+ |
| **Web Server** | Apache 2.4+ / Nginx 1.18+ | Latest |
| **HTTPS** | Required (Let's Encrypt) | Required |

**Required PHP Extensions:**
- `php-pdo` (SQLite/MySQL)
- `php-sqlite3`
- `php-mbstring`
- `php-xml`
- `php-curl`
- `php-zip`
- `php-gd` or `php-imagick` (image manipulation)
- `php-intl` (internationalization)
- `php-fileinfo` (MIME detection)

**Optional PHP Extensions:**
- `php-mysql` (if using MySQL instead of SQLite)
- `php-opcache` (performance boost)
- `php-redis` (if Redis available for caching)

---

### 6.2 Development Environment

**Local Development (Laravel Valet / Herd / Sail):**

```bash
# Requirements
PHP 8.3+
Composer 2.x
Node.js 20+ (for Vite)
npm 10+

# Setup
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev

# Testing
composer quality  # PHPStan + Pint + PHPUnit
npm run quality   # ESLint + Prettier + Vitest
````

---

### 6.3 Production Deployment

**Shared Hosting Deployment:**

1. **Upload via FTP:**
    - Download PineCMS ZIP (includes `vendor/` folder)
    - Extract to web root or subdirectory

2. **Web Installer:**
    - Navigate to `yoursite.com/install`
    - Complete installation form
    - Installer creates `.env`, runs migrations

3. **Cron Job Setup:**
    - Add cron job: `*/5 * * * * cd /path/to/pinecms && php artisan schedule:run >> /dev/null 2>&1`
    - Runs scheduled tasks (backups, queue processing)

4. **Permissions:**
    - `storage/` writable (755 or 775)
    - `bootstrap/cache/` writable (755 or 775)

---

## 7. Security Architecture

### 7.1 OWASP Top 10 Compliance

| Vulnerability                        | Mitigation Strategy                                                              |
| ------------------------------------ | -------------------------------------------------------------------------------- |
| **A01: Broken Access Control**       | Laravel Gates & Policies (RBAC), middleware authorization                        |
| **A02: Cryptographic Failures**      | CipherSweet field encryption, HTTPS only, strong password hashing (bcrypt)       |
| **A03: Injection**                   | Eloquent ORM (parameterized queries), input validation, output escaping          |
| **A04: Insecure Design**             | Security by design (threat modeling, ADRs)                                       |
| **A05: Security Misconfiguration**   | `.env` secrets, debug mode off in production, security headers                   |
| **A06: Vulnerable Components**       | Composer audit (`composer audit`), automated dependency updates                  |
| **A07: Authentication Failures**     | Laravel Fortify (rate limiting, 2FA in v2.0+), strong password policy            |
| **A08: Software/Data Integrity**     | Signed releases, file integrity checks during updates                            |
| **A09: Logging Failures**            | Laravel logging (failed logins, permission denials), Sentry integration (v1.2.0) |
| **A10: Server-Side Request Forgery** | Whitelist external URLs, no user-controlled URLs in `file_get_contents()`        |

---

### 7.2 Security Layers

**Layer 1: Network Security**

- ✅ HTTPS only (redirect HTTP → HTTPS)
- ✅ Security headers (CSP, X-Frame-Options, X-Content-Type-Options)
- ✅ Rate limiting (Laravel throttle middleware)

**Layer 2: Application Security**

- ✅ CSRF protection (Laravel built-in)
- ✅ XSS prevention (Blade `{{ }}` auto-escaping)
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ File upload validation (MIME type, size, extension whitelist)
- ✅ Input validation (FormRequest validation)

**Layer 3: Data Security**

- ✅ Field-level encryption (CipherSweet for sensitive fields)
- ✅ Password hashing (bcrypt, Laravel Fortify)
- ✅ EXIF stripping (remove GPS, camera metadata from images)
- ✅ Secure session handling (httponly, secure, samesite cookies)

**Layer 4: Access Control**

- ✅ Role-Based Access Control (RBAC via Gates & Policies)
- ✅ Authentication (Laravel Fortify)
- ✅ Authorization (middleware, policy checks)
- ✅ Audit logging (failed login attempts, permission denials)

---

### 7.3 Privacy-First Features

**Built-In Privacy Tools:**

- ✅ **No Default Tracking:** Zero analytics scripts by default
- ✅ **EXIF Stripping:** Automatic removal of GPS, camera metadata from uploaded images
- ✅ **Field-Level Encryption:** CipherSweet for sensitive custom fields (e.g., source notes)
- ✅ **Cookieless by Default:** Session cookies only (no tracking cookies)
- ✅ **Optional Analytics:** Matomo plugin with privacy mode (anonymized IPs, no cookies)
- ✅ **GDPR Compliance:** Data export, right to deletion (v1.1.0)

---

## 8. Event-Driven Architecture

### 8.1 Core Events Catalog (v1.0.0)

**Post Events:**

- `PostCreating` - Before post created (cancellable)
- `PostCreated` - After post created
- `PostUpdating` - Before post updated (cancellable)
- `PostUpdated` - After post updated
- `PostDeleting` - Before post deleted (cancellable)
- `PostDeleted` - After post deleted
- `PostPublished` - Post status changed to "published"
- `PostUnpublished` - Post status changed to "draft"

**Media Events:**

- `MediaUploading` - Before media uploaded (cancellable)
- `MediaUploaded` - After media uploaded
- `MediaDeleting` - Before media deleted (cancellable)
- `MediaDeleted` - After media deleted

**User Events:**

- `UserRegistered` - After user registration
- `UserLoggedIn` - After successful login
- `UserLoggedOut` - After logout
- `UserUpdated` - After user profile updated

**Category/Tag Events:**

- `CategoryCreated` - After category created
- `TagCreated` - After tag created

---

### 8.2 Event Listener Examples

**Example 1: Clear Cache After Post Updated**

```php
// app/Listeners/ClearPostCacheListener.php

namespace App\Listeners;

use App\Events\PostUpdated;
use Illuminate\Support\Facades\Cache;

class ClearPostCacheListener
{
    public function handle(PostUpdated $event): void
    {
        Cache::forget("post.{$event->post->id}");
        Cache::forget("posts.listing");
    }
}
```

**Example 2: Strip EXIF After Media Uploaded**

```php
// app/Listeners/StripExifListener.php

namespace App\Listeners;

use App\Events\MediaUploaded;
use Intervention\Image\Facades\Image;

class StripExifListener
{
    public function handle(MediaUploaded $event): void
    {
        $path = $event->media->path;

        $image = Image::make($path);
        $image->save($path, 90, true); // Save with quality 90, strip EXIF
    }
}
```

**Example 3: Send Slack Notification (Custom Plugin)**

```php
// plugins/slack-notifications/src/Listeners/NotifySlackListener.php

namespace SlackNotifications\Listeners;

use App\Events\PostPublished;
use Illuminate\Support\Facades\Http;

class NotifySlackListener
{
    public function handle(PostPublished $event): void
    {
        $webhookUrl = config('slack.webhook_url');

        Http::post($webhookUrl, [
            'text' => "New post published: {$event->post->title}",
        ]);
    }
}
```

---

### 8.3 Plugin Event Registration

**Plugin EventServiceProvider:**

```php
// plugins/slack-notifications/src/Providers/EventServiceProvider.php

namespace SlackNotifications\Providers;

use App\Events\PostPublished;
use SlackNotifications\Listeners\NotifySlackListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PostPublished::class => [
            NotifySlackListener::class,
        ],
    ];
}
```

**Benefits:**

- ✅ Clean separation (event vs. listener)
- ✅ Easy debugging (all listeners visible in `EventServiceProvider`)
- ✅ Testable (mock events, assert dispatched)
- ✅ No core modification (plugins register own listeners)

---

## 9. Layered Architecture

### 9.1 Layer Responsibilities

```
┌─────────────────────────────────────────────────────────────┐
│                   PRESENTATION LAYER                        │
│  Controllers (HTTP), Inertia Responses, Blade Views        │
│  Responsibilities: HTTP handling, validation, response      │
└─────────────────────────────────────────────────────────────┘
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    BUSINESS LOGIC LAYER                     │
│  Services (PostService, MediaService, UserService)         │
│  Responsibilities: Business rules, transactions, events    │
└─────────────────────────────────────────────────────────────┘
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   DATA ACCESS LAYER                         │
│  Repositories (PostRepository, MediaRepository)            │
│  Responsibilities: Database queries, flat-file I/O         │
└─────────────────────────────────────────────────────────────┘
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      STORAGE LAYER                          │
│  Eloquent Models, SQLite Database, Flat-Files              │
│  Responsibilities: Data persistence                         │
└─────────────────────────────────────────────────────────────┘
```

---

### 9.2 Example: Create Post Flow

**Step 1: Controller (Presentation Layer)**

```php
// app/Http/Controllers/Admin/PostController.php

public function store(StorePostRequest $request)
{
    $post = $this->postService->createPost(
        $request->validated()
    );

    return Inertia::location(
        route('admin.posts.edit', $post)
    );
}
```

**Step 2: Service (Business Logic Layer)**

```php
// app/Services/PostService.php

public function createPost(array $data): Post
{
    DB::transaction(function () use ($data) {
        // Generate slug
        $data['slug'] = Str::slug($data['title']);

        // Create post via repository
        $post = $this->postRepository->create($data);

        // Dispatch event
        event(new PostCreated($post));

        return $post;
    });
}
```

**Step 3: Repository (Data Access Layer)**

```php
// app/Repositories/PostRepository.php

public function create(array $data): Post
{
    // Save to SQLite
    $post = Post::create([
        'title' => $data['title'],
        'slug' => $data['slug'],
        'author_id' => $data['author_id'],
        'status' => 'draft',
    ]);

    // Save to flat-file
    $this->saveToFlatFile($post, $data['content']);

    return $post;
}

protected function saveToFlatFile(Post $post, string $content): void
{
    $path = storage_path("content/posts/{$post->created_at->year}/{$post->slug}.md");

    $frontMatter = [
        'id' => $post->id,
        'title' => $post->title,
        'slug' => $post->slug,
        'author_id' => $post->author_id,
        'status' => $post->status,
    ];

    $fileContent = "---\n" . Yaml::dump($frontMatter) . "---\n\n" . $content;

    File::put($path, $fileContent);
}
```

---

### 9.3 Benefits of Layered Architecture

**Single Responsibility Principle (SOLID):**

- ✅ Controllers handle HTTP only
- ✅ Services handle business logic
- ✅ Repositories handle data access
- ✅ Models represent data structure

**Testability:**

- ✅ Unit test services (mock repositories)
- ✅ Feature test controllers (integration tests)
- ✅ Test repositories independently (in-memory SQLite)

**Maintainability:**

- ✅ Easy to locate bugs (clear layer boundaries)
- ✅ Easy to refactor (swap repository implementations)
- ✅ Easy to extend (add new services, repositories)

---

## 10. Performance Architecture

### 10.1 Performance Targets

| Metric                     | Target      | Measurement                         |
| -------------------------- | ----------- | ----------------------------------- |
| **Page Load (P95)**        | < 1 second  | Lighthouse, WebPageTest             |
| **Database Query**         | < 100ms     | Laravel Telescope, Debugbar         |
| **Admin Panel Load**       | < 2 seconds | Lighthouse, Chrome DevTools         |
| **Image Optimization**     | < 500KB     | Intervention Image, WebP conversion |
| **SQLite Size (1K posts)** | < 50MB      | File size monitoring                |
| **Flat-Files (1K posts)**  | < 100MB     | Directory size monitoring           |

---

### 10.2 Caching Strategy

**Cache Layers:**

1. **OPcache (PHP):** Bytecode caching (automatic if enabled)
2. **File Cache (Laravel):** Query results, rendered views
3. **HTTP Cache:** Static assets (CSS, JS, images) via CDN headers
4. **Markdown Cache:** Parsed HTML cached (60 minutes)

**Cache Invalidation:**

- Post updated → Clear post cache (`Cache::forget("post.{$id}")`)
- Category updated → Clear listing cache
- Settings updated → Clear config cache (`php artisan config:clear`)

---

### 10.3 Database Optimization

**SQLite Optimizations:**

- ✅ Indexes on frequently queried columns (`slug`, `status`, `created_at`)
- ✅ Foreign key constraints (referential integrity)
- ✅ VACUUM command (defragment database monthly)
- ✅ WAL mode (Write-Ahead Logging) for concurrent reads

**Query Optimization:**

- ✅ Eager loading (prevent N+1: `Post::with('category', 'author')`)
- ✅ Pagination (never load all posts at once)
- ✅ Query caching (cache listings for 60 minutes)

---

### 10.4 Asset Optimization

**Vite Build Pipeline:**

- ✅ CSS minification (TailwindCSS purge unused classes)
- ✅ JavaScript minification (Terser)
- ✅ Code splitting (dynamic imports for admin panels)
- ✅ Tree shaking (remove unused code)

**Image Optimization:**

- ✅ Auto-resize (max 2000px width)
- ✅ WebP conversion (if browser supports)
- ✅ Lazy loading (defer off-screen images)
- ✅ Responsive images (`srcset` for different screen sizes)

---

## 11. Deployment Architecture

### 11.1 Deployment Diagram (Shared Hosting)

```
┌────────────────────────────────────────────────────────────┐
│                    USER'S BROWSER                          │
└────────────────────────────────────────────────────────────┘
                       ▲ HTTPS
                       ▼
┌────────────────────────────────────────────────────────────┐
│              CLOUDFLARE / CDN (Optional)                   │
│  - SSL/TLS Termination                                    │
│  - Static Asset Caching                                   │
│  - DDoS Protection                                        │
└────────────────────────────────────────────────────────────┘
                       ▲ HTTPS
                       ▼
┌────────────────────────────────────────────────────────────┐
│                 SHARED HOSTING SERVER                      │
│  ┌──────────────────────────────────────────────────┐     │
│  │ Apache/Nginx                                     │     │
│  │  - Handles HTTP requests                        │     │
│  │  - Serves static files                          │     │
│  │  - Proxies to PHP-FPM                           │     │
│  └──────────────────────────────────────────────────┘     │
│                       ▲                                    │
│                       ▼                                    │
│  ┌──────────────────────────────────────────────────┐     │
│  │ PHP-FPM (PHP 8.3+)                              │     │
│  │  - Executes Laravel application                │     │
│  │  - Handles Inertia.js responses                │     │
│  └──────────────────────────────────────────────────┘     │
│                       ▲                                    │
│                       ▼                                    │
│  ┌──────────────────────────────────────────────────┐     │
│  │ File System                                     │     │
│  │  ├── database/database.sqlite                  │     │
│  │  ├── storage/content/ (Markdown files)         │     │
│  │  ├── storage/media/ (Uploaded images)          │     │
│  │  └── storage/framework/cache/ (File cache)     │     │
│  └──────────────────────────────────────────────────┘     │
│                                                            │
│  ┌──────────────────────────────────────────────────┐     │
│  │ Cron Job (Every 5 minutes)                      │     │
│  │  php artisan schedule:run                       │     │
│  │  - Runs scheduled tasks (backups, queues)      │     │
│  └──────────────────────────────────────────────────┘     │
└────────────────────────────────────────────────────────────┘
```

---

### 11.2 Scaling Strategy

**Phase 1: Single Shared Hosting (v1.0-1.2, < 10,000 posts)**

- SQLite database (< 50MB)
- File-based cache
- Single server
- **Target:** 500-5,000 users

**Phase 2: VPS Migration (v2.0+, 10,000-100,000 posts)**

- MySQL database (separate server)
- Redis cache
- Queue workers (background processing)
- **Target:** 5,000-10,000 users

**Phase 3: Multi-Server (v3.0+, 100,000+ posts)**

- Database replication (read replicas)
- Load balancer (multiple web servers)
- CDN for static assets
- Object storage (S3) for media files

---

## 12. Open Questions

### 12.1 Architecture Questions

- ❓ Should MySQL be prioritized over SQLite for v1.0.0? (Currently SQLite primary)
- ❓ Is field-level encryption performance acceptable for 1,000+ encrypted fields?
- ❓ Should flat-file structure be year-based (`/2025/`) or flat (`/all/`)?
- ❓ Is 5-minute queue processing delay acceptable for email notifications?

### 12.2 Technical Questions

- ❓ Should web installer support subdirectory installation? (Currently root-only)
- ❓ Is manual update process sufficient or should v1.2.0 add one-click updates?
- ❓ Should SQLite use WAL mode by default? (Better concurrency, larger file size)
- ❓ Should media optimization be background (queue) or synchronous (instant)?

---

## 13. Change History

| Date       | Version | Author       | Changes                                                            |
| ---------- | ------- | ------------ | ------------------------------------------------------------------ |
| 2025-11-07 | 1.0     | PineCMS Team | Initial architecture (hybrid storage, event-driven, web-installer) |

---

**Last Updated:** 2025-11-07
**Document Owner:** PineCMS Team
**Next Review:** 2025-12-07
