# 07 - Technical Specifications

**Version:** 1.0.0
**Last Updated:** 2025-11-07
**Status:** 📋 Planned
**Owner:** PineCMS Team

---

## 1. Overview

This document provides **medium-level technical specifications** for PineCMS, including database schema, flat-file structure, event system, API design, and security architecture.

**Target Audience:** Backend developers, database architects, security engineers

**Related Documents:**

- [04-ARCHITECTURE.md](04-ARCHITECTURE.md) - High-level system architecture
- [05-CORE-FEATURES.md](05-CORE-FEATURES.md) - Functional specifications
- [09-QUALITY-REQUIREMENTS.md](09-QUALITY-REQUIREMENTS.md) - Performance & security standards

---

## 2. Database Schema

### 2.1 Overview

**Database:** SQLite (default), MySQL (optional future support)
**ORM:** Laravel Eloquent 12
**Migrations:** Laravel Migrations (version controlled)
**Conventions:** snake_case for tables/columns, plural table names

### 2.2 Core Tables

#### 2.2.1 Users Table

**Table:** `users`
**Purpose:** User accounts for admin panel

| Column              | Type         | Nullable | Default  | Description                        |
| ------------------- | ------------ | -------- | -------- | ---------------------------------- |
| `id`                | INTEGER      | NO       | AUTO     | Primary key                        |
| `name`              | VARCHAR(255) | NO       | -        | Full name                          |
| `email`             | VARCHAR(255) | NO       | -        | Unique email                       |
| `email_verified_at` | TIMESTAMP    | YES      | NULL     | Email verification timestamp       |
| `password`          | VARCHAR(255) | NO       | -        | Hashed password (bcrypt)           |
| `role`              | ENUM         | NO       | 'editor' | admin, editor, author, contributor |
| `status`            | ENUM         | NO       | 'active' | active, inactive, suspended        |
| `avatar`            | VARCHAR(255) | YES      | NULL     | Avatar filename                    |
| `bio`               | TEXT         | YES      | NULL     | User bio (encrypted if sensitive)  |
| `remember_token`    | VARCHAR(100) | YES      | NULL     | Laravel auth token                 |
| `created_at`        | TIMESTAMP    | NO       | NOW      | Creation timestamp                 |
| `updated_at`        | TIMESTAMP    | NO       | NOW      | Last update timestamp              |

**Indexes:**

- PRIMARY KEY: `id`
- UNIQUE INDEX: `email`
- INDEX: `role`, `status`

**Relationships:**

- `posts` → hasMany(Post)
- `pages` → hasMany(Page)
- `media` → hasMany(Media)
- `comments` → hasMany(Comment) [v1.1.0+]

---

#### 2.2.2 Categories Table

**Table:** `categories`
**Purpose:** Post/page categorization

| Column        | Type         | Nullable | Default | Description                          |
| ------------- | ------------ | -------- | ------- | ------------------------------------ |
| `id`          | INTEGER      | NO       | AUTO    | Primary key                          |
| `name`        | VARCHAR(255) | NO       | -       | Category name                        |
| `slug`        | VARCHAR(255) | NO       | -       | URL-friendly slug (unique)           |
| `description` | TEXT         | YES      | NULL    | Category description                 |
| `parent_id`   | INTEGER      | YES      | NULL    | Nested categories (self-referencing) |
| `order`       | INTEGER      | NO       | 0       | Display order                        |
| `created_at`  | TIMESTAMP    | NO       | NOW     | Creation timestamp                   |
| `updated_at`  | TIMESTAMP    | NO       | NOW     | Last update timestamp                |

**Indexes:**

- PRIMARY KEY: `id`
- UNIQUE INDEX: `slug`
- INDEX: `parent_id`

**Relationships:**

- `posts` → belongsToMany(Post) via `category_post` pivot
- `parent` → belongsTo(Category)
- `children` → hasMany(Category)

---

#### 2.2.3 Tags Table

**Table:** `tags`
**Purpose:** Tagging system for posts/pages

| Column       | Type         | Nullable | Default | Description                |
| ------------ | ------------ | -------- | ------- | -------------------------- |
| `id`         | INTEGER      | NO       | AUTO    | Primary key                |
| `name`       | VARCHAR(255) | NO       | -       | Tag name                   |
| `slug`       | VARCHAR(255) | NO       | -       | URL-friendly slug (unique) |
| `created_at` | TIMESTAMP    | NO       | NOW     | Creation timestamp         |
| `updated_at` | TIMESTAMP    | NO       | NOW     | Last update timestamp      |

**Indexes:**

- PRIMARY KEY: `id`
- UNIQUE INDEX: `slug`

**Relationships:**

- `posts` → belongsToMany(Post) via `post_tag` pivot

---

#### 2.2.4 Media Table

**Table:** `media`
**Purpose:** File storage metadata (images, videos, documents)

| Column        | Type         | Nullable | Default | Description                   |
| ------------- | ------------ | -------- | ------- | ----------------------------- |
| `id`          | INTEGER      | NO       | AUTO    | Primary key                   |
| `user_id`     | INTEGER      | NO       | -       | Uploader (FK: users.id)       |
| `filename`    | VARCHAR(255) | NO       | -       | Original filename             |
| `path`        | VARCHAR(255) | NO       | -       | Storage path (relative)       |
| `mime_type`   | VARCHAR(100) | NO       | -       | File MIME type                |
| `size`        | INTEGER      | NO       | -       | File size (bytes)             |
| `alt_text`    | VARCHAR(255) | YES      | NULL    | Alt text (accessibility)      |
| `title`       | VARCHAR(255) | YES      | NULL    | File title                    |
| `description` | TEXT         | YES      | NULL    | File description              |
| `width`       | INTEGER      | YES      | NULL    | Image width (px)              |
| `height`      | INTEGER      | YES      | NULL    | Image height (px)             |
| `focal_point` | VARCHAR(50)  | YES      | NULL    | Focal point (x,y coordinates) |
| `created_at`  | TIMESTAMP    | NO       | NOW     | Upload timestamp              |
| `updated_at`  | TIMESTAMP    | NO       | NOW     | Last update timestamp         |

**Indexes:**

- PRIMARY KEY: `id`
- INDEX: `user_id`, `mime_type`
- FULLTEXT INDEX: `title`, `description` (for search)

**Relationships:**

- `user` → belongsTo(User)

**Storage:**

- Files stored in: `/storage/media/{year}/{month}/{hash}.{ext}`
- Metadata stored in database

---

#### 2.2.5 Posts Metadata Table

**Table:** `posts`
**Purpose:** Post metadata (actual content in flat-files)

| Column              | Type         | Nullable | Default  | Description                              |
| ------------------- | ------------ | -------- | -------- | ---------------------------------------- |
| `id`                | INTEGER      | NO       | AUTO     | Primary key                              |
| `uuid`              | VARCHAR(36)  | NO       | -        | UUID (unique, for file reference)        |
| `user_id`           | INTEGER      | NO       | -        | Author (FK: users.id)                    |
| `title`             | VARCHAR(255) | NO       | -        | Post title                               |
| `slug`              | VARCHAR(255) | NO       | -        | URL-friendly slug (unique)               |
| `excerpt`           | TEXT         | YES      | NULL     | Short excerpt                            |
| `featured_image_id` | INTEGER      | YES      | NULL     | Featured image (FK: media.id)            |
| `status`            | ENUM         | NO       | 'draft'  | draft, published, scheduled, archived    |
| `visibility`        | ENUM         | NO       | 'public' | public, private, password                |
| `password`          | VARCHAR(255) | YES      | NULL     | Password (hashed if visibility=password) |
| `allow_comments`    | BOOLEAN      | NO       | TRUE     | Comments enabled/disabled                |
| `published_at`      | TIMESTAMP    | YES      | NULL     | Publication timestamp                    |
| `scheduled_at`      | TIMESTAMP    | YES      | NULL     | Scheduled publication time               |
| `file_path`         | VARCHAR(255) | NO       | -        | Markdown file path (relative)            |
| `file_hash`         | VARCHAR(64)  | YES      | NULL     | SHA-256 hash (content integrity)         |
| `created_at`        | TIMESTAMP    | NO       | NOW      | Creation timestamp                       |
| `updated_at`        | TIMESTAMP    | NO       | NOW      | Last update timestamp                    |

**Indexes:**

- PRIMARY KEY: `id`
- UNIQUE INDEX: `uuid`, `slug`
- INDEX: `user_id`, `status`, `published_at`
- FULLTEXT INDEX: `title`, `excerpt` (for search)

**Relationships:**

- `user` → belongsTo(User)
- `categories` → belongsToMany(Category) via `category_post` pivot
- `tags` → belongsToMany(Tag) via `post_tag` pivot
- `featuredImage` → belongsTo(Media)
- `comments` → hasMany(Comment) [v1.1.0+]

**Flat-File Reference:**

- Content stored in: `/storage/content/posts/{year}/{uuid}.md`
- Database stores metadata + file path

---

#### 2.2.6 Pages Metadata Table

**Table:** `pages`
**Purpose:** Page metadata (actual content in flat-files)

| Column              | Type         | Nullable | Default   | Description                              |
| ------------------- | ------------ | -------- | --------- | ---------------------------------------- |
| `id`                | INTEGER      | NO       | AUTO      | Primary key                              |
| `uuid`              | VARCHAR(36)  | NO       | -         | UUID (unique, for file reference)        |
| `user_id`           | INTEGER      | NO       | -         | Author (FK: users.id)                    |
| `title`             | VARCHAR(255) | NO       | -         | Page title                               |
| `slug`              | VARCHAR(255) | NO       | -         | URL-friendly slug (unique)               |
| `parent_id`         | INTEGER      | YES      | NULL      | Nested pages (self-referencing)          |
| `template`          | VARCHAR(100) | YES      | 'default' | Page template name                       |
| `featured_image_id` | INTEGER      | YES      | NULL      | Featured image (FK: media.id)            |
| `status`            | ENUM         | NO       | 'draft'   | draft, published, archived               |
| `visibility`        | ENUM         | NO       | 'public'  | public, private, password                |
| `password`          | VARCHAR(255) | YES      | NULL      | Password (hashed if visibility=password) |
| `allow_comments`    | BOOLEAN      | NO       | FALSE     | Comments enabled/disabled                |
| `order`             | INTEGER      | NO       | 0         | Display order (for menus)                |
| `published_at`      | TIMESTAMP    | YES      | NULL      | Publication timestamp                    |
| `file_path`         | VARCHAR(255) | NO       | -         | Markdown file path (relative)            |
| `file_hash`         | VARCHAR(64)  | YES      | NULL      | SHA-256 hash (content integrity)         |
| `created_at`        | TIMESTAMP    | NO       | NOW       | Creation timestamp                       |
| `updated_at`        | TIMESTAMP    | NO       | NOW       | Last update timestamp                    |

**Indexes:**

- PRIMARY KEY: `id`
- UNIQUE INDEX: `uuid`, `slug`
- INDEX: `user_id`, `parent_id`, `status`
- FULLTEXT INDEX: `title`

**Relationships:**

- `user` → belongsTo(User)
- `parent` → belongsTo(Page)
- `children` → hasMany(Page)
- `featuredImage` → belongsTo(Media)

---

#### 2.2.7 Settings Table

**Table:** `settings`
**Purpose:** Site-wide settings (key-value store)

| Column       | Type         | Nullable | Default  | Description                    |
| ------------ | ------------ | -------- | -------- | ------------------------------ |
| `id`         | INTEGER      | NO       | AUTO     | Primary key                    |
| `key`        | VARCHAR(255) | NO       | -        | Setting key (unique)           |
| `value`      | TEXT         | YES      | NULL     | Setting value (JSON/string)    |
| `type`       | ENUM         | NO       | 'string' | string, boolean, integer, json |
| `encrypted`  | BOOLEAN      | NO       | FALSE    | Encrypted field (CipherSweet)  |
| `autoload`   | BOOLEAN      | NO       | TRUE     | Load on every request          |
| `created_at` | TIMESTAMP    | NO       | NOW      | Creation timestamp             |
| `updated_at` | TIMESTAMP    | NO       | NOW      | Last update timestamp          |

**Indexes:**

- PRIMARY KEY: `id`
- UNIQUE INDEX: `key`
- INDEX: `autoload`

**Example Settings:**

```json
{
    "site.name": "My Blog",
    "site.tagline": "A privacy-first blog",
    "site.timezone": "Europe/Berlin",
    "seo.meta_description": "My awesome blog",
    "security.encryption_key": "[encrypted]"
}
```

---

### 2.3 v1.1.0+ Tables

#### 2.3.1 Comments Table [v1.1.0]

**Table:** `comments`
**Purpose:** Blog comments

| Column         | Type         | Nullable | Default   | Description                        |
| -------------- | ------------ | -------- | --------- | ---------------------------------- |
| `id`           | INTEGER      | NO       | AUTO      | Primary key                        |
| `post_id`      | INTEGER      | NO       | -         | Post (FK: posts.id)                |
| `user_id`      | INTEGER      | YES      | NULL      | User (FK: users.id, NULL if guest) |
| `parent_id`    | INTEGER      | YES      | NULL      | Nested comments                    |
| `author_name`  | VARCHAR(255) | YES      | NULL      | Guest name (if user_id NULL)       |
| `author_email` | VARCHAR(255) | YES      | NULL      | Guest email (encrypted)            |
| `author_ip`    | VARCHAR(45)  | YES      | NULL      | IP address (encrypted, GDPR)       |
| `content`      | TEXT         | NO       | -         | Comment content (sanitized)        |
| `status`       | ENUM         | NO       | 'pending' | pending, approved, rejected, spam  |
| `created_at`   | TIMESTAMP    | NO       | NOW       | Creation timestamp                 |
| `updated_at`   | TIMESTAMP    | NO       | NOW       | Last update timestamp              |

**Indexes:**

- PRIMARY KEY: `id`
- INDEX: `post_id`, `user_id`, `parent_id`, `status`

---

#### 2.3.2 Workflow States Table [v1.1.0]

**Table:** `workflow_states`
**Purpose:** Post/page workflow states

| Column              | Type         | Nullable | Default | Description                       |
| ------------------- | ------------ | -------- | ------- | --------------------------------- |
| `id`                | INTEGER      | NO       | AUTO    | Primary key                       |
| `workflowable_type` | VARCHAR(255) | NO       | -       | Post or Page (polymorphic)        |
| `workflowable_id`   | INTEGER      | NO       | -       | Post/Page ID                      |
| `state`             | ENUM         | NO       | 'draft' | draft, review, approved, rejected |
| `assigned_to`       | INTEGER      | YES      | NULL    | User ID (FK: users.id)            |
| `notes`             | TEXT         | YES      | NULL    | Workflow notes                    |
| `created_at`        | TIMESTAMP    | NO       | NOW     | State change timestamp            |
| `updated_at`        | TIMESTAMP    | NO       | NOW     | Last update timestamp             |

**Indexes:**

- PRIMARY KEY: `id`
- INDEX: `workflowable_type`, `workflowable_id`, `state`, `assigned_to`

---

### 2.4 Pivot Tables

#### 2.4.1 Category-Post Pivot

**Table:** `category_post`

| Column        | Type    | Nullable | Default | Description       |
| ------------- | ------- | -------- | ------- | ----------------- |
| `category_id` | INTEGER | NO       | -       | FK: categories.id |
| `post_id`     | INTEGER | NO       | -       | FK: posts.id      |

**Indexes:**

- PRIMARY KEY: `category_id`, `post_id`

---

#### 2.4.2 Post-Tag Pivot

**Table:** `post_tag`

| Column    | Type    | Nullable | Default | Description  |
| --------- | ------- | -------- | ------- | ------------ |
| `post_id` | INTEGER | NO       | -       | FK: posts.id |
| `tag_id`  | INTEGER | NO       | -       | FK: tags.id  |

**Indexes:**

- PRIMARY KEY: `post_id`, `tag_id`

---

## 3. Flat-File Structure

### 3.1 Overview

**Storage:** Markdown files with YAML front matter
**Location:** `/storage/content/{type}/{year}/{uuid}.md`
**Format:** UTF-8 encoding
**Git-Friendly:** Line-based diffs, version controllable (for developers)

### 3.2 Post File Structure

**File Path:** `/storage/content/posts/{year}/{uuid}.md`

**Example:**

````markdown
---
uuid: 550e8400-e29b-41d4-a716-446655440000
title: 'My First Blog Post'
slug: my-first-blog-post
author_id: 1
status: published
published_at: 2025-11-07 10:00:00
categories:
    - technology
    - web-development
tags:
    - laravel
    - cms
    - php
featured_image: /media/2025/11/featured.jpg
excerpt: 'This is a short excerpt of the blog post...'
seo_title: 'My First Blog Post - PineCMS Blog'
seo_description: 'Learn how to create your first blog post with PineCMS...'
custom_fields:
    reading_time: 5
    difficulty: beginner
---

# My First Blog Post

This is the **main content** of the blog post written in Markdown.

## Section 1

Content here with [links](https://example.com) and **formatting**.

![Image Alt Text](/media/2025/11/image.jpg)

## Section 2

More content...

```php
// Code blocks supported
echo "Hello World";
```
````

````

**YAML Front Matter Fields:**
- `uuid` (required) - Unique identifier
- `title` (required) - Post title
- `slug` (required) - URL slug
- `author_id` (required) - User ID
- `status` (required) - draft, published, scheduled, archived
- `published_at` (optional) - Publication timestamp
- `categories` (optional) - Array of category slugs
- `tags` (optional) - Array of tag slugs
- `featured_image` (optional) - Featured image path
- `excerpt` (optional) - Short excerpt
- `seo_title` (optional) - SEO title override
- `seo_description` (optional) - SEO description
- `custom_fields` (optional) - Key-value pairs for custom data

---

### 3.3 Page File Structure

**File Path:** `/storage/content/pages/{uuid}.md`

**Example:**
```markdown
---
uuid: 650e8400-e29b-41d4-a716-446655440001
title: "About Us"
slug: about-us
author_id: 1
template: about
status: published
published_at: 2025-11-07 10:00:00
parent_id: null
order: 1
featured_image: /media/2025/11/about-hero.jpg
seo_title: "About Us - PineCMS"
seo_description: "Learn more about PineCMS..."
---

# About Us

This is the **About Us** page content.

We are a team of developers building privacy-first CMS solutions.
````

---

### 3.4 File Synchronization

**Database ↔ Flat-File Sync:**

**On Post/Page Save:**

1. Validate form data
2. Save metadata to database (`posts` table)
3. Generate Markdown file with YAML front matter
4. Write file to `/storage/content/{type}/{year}/{uuid}.md`
5. Calculate SHA-256 hash, store in `file_hash` column
6. Dispatch `PostSaved` event

**On Post/Page Load:**

1. Query database for metadata
2. Read Markdown file from `file_path`
3. Parse YAML front matter
4. Verify `file_hash` (integrity check)
5. Convert Markdown to HTML (for display)

**Integrity Check:**

- Compare stored `file_hash` with current file hash
- If mismatch: Log warning, notify admin
- Prevents unauthorized file modifications

---

## 4. Event System

### 4.1 Overview

**PineCMS uses Laravel's native Event system (NOT WordPress-style hooks).**

**Event Structure:**

```php
<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostPublished
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Post $post
    ) {}
}
```

**Listener Structure:**

```php
<?php

namespace App\Listeners;

use App\Events\PostPublished;

class SendNewPostNotification
{
    public function handle(PostPublished $event): void
    {
        // Send email notification
        // Update search index
        // Trigger webhook
    }
}
```

---

### 4.2 Core Events Catalog

#### 4.2.1 Content Events

| Event           | Parameters   | When Fired              |
| --------------- | ------------ | ----------------------- |
| `PostCreated`   | `Post $post` | After post created      |
| `PostUpdated`   | `Post $post` | After post updated      |
| `PostPublished` | `Post $post` | Post status → published |
| `PostDeleted`   | `Post $post` | Before post deleted     |
| `PageCreated`   | `Page $page` | After page created      |
| `PageUpdated`   | `Page $page` | After page updated      |
| `PagePublished` | `Page $page` | Page status → published |
| `PageDeleted`   | `Page $page` | Before page deleted     |

#### 4.2.2 Media Events

| Event           | Parameters     | When Fired             |
| --------------- | -------------- | ---------------------- |
| `MediaUploaded` | `Media $media` | After file uploaded    |
| `MediaUpdated`  | `Media $media` | After metadata updated |
| `MediaDeleted`  | `Media $media` | Before media deleted   |

#### 4.2.3 User Events

| Event            | Parameters   | When Fired            |
| ---------------- | ------------ | --------------------- |
| `UserRegistered` | `User $user` | After user created    |
| `UserLoggedIn`   | `User $user` | After login           |
| `UserLoggedOut`  | `User $user` | After logout          |
| `UserUpdated`    | `User $user` | After profile updated |
| `UserDeleted`    | `User $user` | Before user deleted   |

#### 4.2.4 Comment Events [v1.1.0]

| Event             | Parameters         | When Fired             |
| ----------------- | ------------------ | ---------------------- |
| `CommentCreated`  | `Comment $comment` | After comment created  |
| `CommentApproved` | `Comment $comment` | Status → approved      |
| `CommentRejected` | `Comment $comment` | Status → rejected      |
| `CommentDeleted`  | `Comment $comment` | Before comment deleted |

#### 4.2.5 Theme Events

| Event                 | Parameters     | When Fired           |
| --------------------- | -------------- | -------------------- |
| `theme.head.before`   | `string $html` | Before `</head>` tag |
| `theme.footer.before` | `string $html` | Before `</body>` tag |

**Use Case:** Inject analytics codes, custom scripts, meta tags

---

### 4.3 Event Registration

**Service Provider (`EventServiceProvider.php`):**

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PostPublished::class => [
            SendNewPostNotification::class,
            UpdateSearchIndex::class,
            ClearPostCache::class,
        ],
        MediaUploaded::class => [
            StripExifData::class,
            GenerateThumbnails::class,
            OptimizeImage::class,
        ],
    ];
}
```

**Plugin Event Registration:**
Plugins can register their own listeners via their Service Provider.

---

## 5. API Design (Future)

### 5.1 Overview

**API:** Optional JSON API (planned for v1.1.0+)
**Authentication:** Laravel Sanctum (token-based)
**Format:** JSON
**Versioning:** URL-based (`/api/v1/...`)

### 5.2 Planned Endpoints (v1.1.0+)

**Posts API:**

- `GET /api/v1/posts` - List posts
- `GET /api/v1/posts/{slug}` - Get single post
- `POST /api/v1/posts` - Create post (authenticated)
- `PUT /api/v1/posts/{id}` - Update post (authenticated)
- `DELETE /api/v1/posts/{id}` - Delete post (authenticated)

**Pages API:**

- `GET /api/v1/pages` - List pages
- `GET /api/v1/pages/{slug}` - Get single page

**Media API:**

- `POST /api/v1/media` - Upload file (authenticated)
- `GET /api/v1/media/{id}` - Get media metadata

**Authentication:**

- `POST /api/v1/auth/login` - Login (get token)
- `POST /api/v1/auth/logout` - Logout (revoke token)

**Rate Limiting:**

- 60 requests/minute (authenticated)
- 10 requests/minute (unauthenticated)

---

## 6. Security Architecture

### 6.1 Field-Level Encryption

**Technology:** CipherSweet (Searchable Encryption)
**Fields Encrypted:**

- User bio (if contains sensitive info)
- Comment author email
- Comment author IP
- Settings values (if marked as encrypted)
- Page/post password

**Example:**

```php
use ParagonIE\CipherSweet\EncryptedField;

// Encrypt email
$encrypted = $engine->encrypt($email);

// Search encrypted data
$blindIndex = $engine->getBlindIndex($email);
```

**Benefits:**

- Data encrypted at rest
- Searchable (blind indexes)
- GDPR compliant

---

### 6.2 Authentication & Authorization

**Authentication:** Laravel Fortify (session-based)
**Password Hashing:** bcrypt (cost: 12)
**Session:** Cookie-based, httpOnly, secure
**CSRF Protection:** Laravel CSRF tokens

**Authorization:** Laravel Gates & Policies

- `viewAny(Post)` - List posts
- `view(Post)` - View single post
- `create(Post)` - Create post
- `update(Post)` - Update post
- `delete(Post)` - Delete post

**Role-Based Access Control (RBAC):**

- **Admin:** Full access
- **Editor:** Publish posts, manage comments
- **Author:** Create/edit own posts
- **Contributor:** Create posts (pending approval)

---

### 6.3 Input Validation & Sanitization

**Validation:** Laravel FormRequest classes
**Sanitization:**

- HTMLPurifier for user-generated content
- Strip malicious scripts (XSS prevention)
- EXIF data stripping for uploaded images

**Example:**

```php
class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,scheduled',
        ];
    }
}
```

---

### 6.4 OWASP Top 10 Compliance

**Covered Vulnerabilities:**

1. **Injection (SQL, XSS):**
    - Eloquent ORM (parameterized queries)
    - HTMLPurifier (XSS prevention)
    - Blade `{{ }}` auto-escaping

2. **Broken Authentication:**
    - Laravel Fortify (secure sessions)
    - Password hashing (bcrypt)
    - Rate limiting (login attempts)

3. **Sensitive Data Exposure:**
    - Field-level encryption (CipherSweet)
    - HTTPS required
    - Secure cookies (httpOnly, sameSite)

4. **XML External Entities (XXE):**
    - Not applicable (no XML parsing)

5. **Broken Access Control:**
    - Laravel Policies & Gates
    - RBAC (role-based permissions)

6. **Security Misconfiguration:**
    - `.env` not in version control
    - Debug mode disabled in production
    - Directory listing disabled

7. **Cross-Site Scripting (XSS):**
    - Blade auto-escaping
    - HTMLPurifier for user content
    - CSP headers

8. **Insecure Deserialization:**
    - No user-controlled serialization

9. **Using Components with Known Vulnerabilities:**
    - Regular dependency updates
    - `composer audit`, `npm audit`

10. **Insufficient Logging & Monitoring:**
    - Laravel Log system
    - Failed login attempts logged
    - Critical errors logged

---

## 7. Performance Specifications

### 7.1 Database Optimization

**Query Performance:**

- < 100ms query execution time (P95)
- Eager loading (N+1 prevention)
- Proper indexing (see schema above)

**Example:**

```php
// BAD (N+1 query)
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->user->name; // N queries
}

// GOOD (eager loading)
$posts = Post::with('user')->get(); // 2 queries
```

**SQLite Optimization:**

- WAL mode (Write-Ahead Logging)
- Pragma settings:
    ```sql
    PRAGMA journal_mode=WAL;
    PRAGMA synchronous=NORMAL;
    PRAGMA cache_size=10000;
    ```

---

### 7.2 Caching Strategy

**Cache Driver:** File-based (default), Redis (optional)
**Cache Lifetime:** Configurable (default: 1 hour)

**Cached Items:**

- Site settings (autoload)
- Post/page lists
- Category/tag lists
- Menu navigation
- Search index

**Cache Invalidation:**

- Automatic on post/page update
- Manual "Clear Cache" button (admin panel)
- Event-driven cache clearing

---

### 7.3 File Storage

**Storage Driver:** Local filesystem (default), S3 (optional future)
**Media Path:** `/storage/media/{year}/{month}/{hash}.{ext}`
**Content Path:** `/storage/content/{type}/{year}/{uuid}.md`

**Size Limits:**

- Upload: 50MB (configurable)
- SQLite Database: < 50MB for 1,000 posts
- Flat-Files: < 100MB for 1,000 posts

---

## 8. Change History

| Date       | Version | Author       | Changes                                                                                                      |
| ---------- | ------- | ------------ | ------------------------------------------------------------------------------------------------------------ |
| 2025-11-07 | 1.0     | PineCMS Team | Initial technical specifications (database schema, flat-file structure, event system, security architecture) |

---

**Last Updated:** 2025-11-07
**Document Owner:** PineCMS Team
**Next Review:** 2025-12-07
