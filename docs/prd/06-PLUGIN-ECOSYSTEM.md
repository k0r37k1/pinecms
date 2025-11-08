# 06 - Plugin Ecosystem

**Version:** 1.0.0
**Last Updated:** 2025-11-07
**Status:** 📋 Planned
**Owner:** PineCMS Team

---

## 1. Overview

**PineCMS Official Plugins** extend the core CMS with specialized features that not every user requires. This document specifies the plugin architecture, official plugin features, installation process, and community plugin ecosystem.

### 1.1 Plugin Strategy

**Core Philosophy:**

- Core = Essential blogging features for 80% of users
- Official Plugins = High-demand specialized features (newsletters, multi-language, SEO)
- Community Plugins = Extensibility for unique requirements (custom fields, analytics, integrations)

**Official Plugins (3 Total):**

1. Newsletter Plugin (~6 features) - v1.1.0
2. Multi-Language Plugin (~8 features) - v1.1.0
3. SEO Pro Plugin (~3 features) - v1.1.0

**Total Official Plugin Features:** ~17

### 1.2 Key Principles

**All Official Plugins Are:**

- ✅ 100% Open Source (MIT License)
- ✅ Free for all users
- ✅ Developed and maintained by PineCMS Team
- ✅ Shared hosting compatible
- ✅ Easy to install (ZIP upload + activation)
- ✅ Deactivatable without data loss
- ✅ Event-driven (Laravel Events)

---

## 2. Plugin Philosophy

### 2.1 Why Plugins?

**Separation of Concerns:**

- Core handles essential blogging (95 features in v1.0.0)
- Official Plugins handle high-demand specialized needs (newsletters, multi-language, advanced SEO)
- Community Plugins extend for unique requirements (custom fields, analytics, integrations, page builders)

**Benefits:**

- **Performance:** Users only load features they need
- **Maintainability:** Smaller core codebase
- **Flexibility:** Easy to enable/disable features
- **Sustainability:** Core stays focused, plugins evolve independently

### 2.2 What Stays in Core vs. Plugins?

**Core (v1.0-1.2):**

- Posts, Pages, Categories, Tags
- Media Library, TipTap Editor
- User Management, Roles & Permissions
- Theme System, SEO Basics
- Comments (v1.1.0), Search (v1.1.0), Workflow (v1.1.0)

**Official Plugins (v1.1.0):**

- Newsletter & Email Campaigns
- Multi-Language Content Translation
- Advanced SEO (Schema, Broken Links, Advanced Sitemap)

**Community Plugins:**

- **Custom Fields** (Repeater, Gallery, Relationships, JSON, etc.)
- **Analytics** (Matomo, Plausible, Google Analytics 4, Fathom)
- Social Integrations (Twitter, Facebook, Instagram)
- Advanced Page Builders
- E-commerce Integrations
- Custom Authentication (OAuth2, SSO)

---

## 3. Official Plugins Specifications

### 3.1 Newsletter Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Planned
**Feature Count:** ~6

#### 3.1.1 Features

**Newsletter Editor:**

- TipTap WYSIWYG editor (same as posts)
- Separate content type (newsletters vs. posts)
- Template selection (3 responsive email templates)
- HTML/Text preview before sending

**Subscriber Management:**

- Import/Export subscribers (CSV)
- Subscriber groups/tags
- Subscriber status (Active, Unsubscribed)
- Subscriber segmentation (filter by tags/categories)

**Subscriber Sign-up:**

- Frontend widget/shortcode for signup forms
- Customizable form fields (name, email required)
- Success message configuration
- Double opt-in (GDPR compliance):
    - Email verification
    - Confirmation template
    - Opt-in timestamp tracking

**Email Campaign Scheduling:**

- Send now (immediate)
- Schedule for later (date/time picker)
- Queue system for background sending

**Privacy-Friendly Tracking (Optional):**

- Open rate tracking (disabled by default)
- Click tracking (disabled by default)
- Privacy mode (no tracking, GDPR compliant)

#### 3.1.2 Infrastructure Requirements

**SMTP:**

- External SMTP service required (Mailgun, SendGrid, Amazon SES)
- SMTP configuration in plugin settings
- Rate limiting (configurable, e.g., 100 emails/hour)
- Retry logic for failed sends

**Queue System:**

- Laravel Queues for background sending
- Manual queue processing (shared hosting compatible)
- Automatic retry on failure

**Scalability:**

- < 1,000 subscribers: Shared hosting sufficient
- 1,000+ subscribers: Dedicated SMTP recommended

**Compliance:**

- GDPR: Double opt-in, unsubscribe link, privacy policy
- CAN-SPAM: Footer address, unsubscribe mechanism

#### 3.1.3 Use Cases

- 📧 Blog newsletter (new posts auto-send)
- 📰 Weekly/monthly newsletter campaigns
- 🎯 Marketing campaigns
- 📢 Announcements & updates

#### 3.1.4 Removed Features (YAGNI)

- ❌ A/B Testing → v2.0 or community plugin
- ❌ Recurring Newsletters → v2.0 or community plugin
- ❌ Bounce & Complaint Handling → Enterprise feature, v2.0
- ❌ Subscriber Status "Bounced" → Manual management only

#### 3.1.5 Why Plugin?

Not every blogger/website operator needs newsletter functionality. External SMTP services are optional and required for larger lists.

---

### 3.2 Multi-Language Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Planned
**Feature Count:** ~8

#### 3.2.1 Features

**Multi-Language Support:**

- Unlimited languages
- Pre-configured languages (DE, EN, FR, ES, IT, PT, AR, HE, etc.)
- Custom languages (add any language/locale)
- Language management UI (enable/disable languages)

**Content Translation UI:**

- Side-by-side editor (original + translation)
- Copy from original button
- Translation progress indicator (0%, 50%, 100%)
- Missing translations warning

**Translatable Fields:**

- Post/Page title & content
- Categories & tags
- Site settings (name, tagline, description)
- SEO meta (title, description, keywords)
- Custom fields (if Custom Fields plugin installed)

**Locale Switcher (Frontend):**

- Language dropdown widget
- Flag icons (optional)
- Language-specific URLs
- Auto-detect browser language (optional, cookie-based)
- Persistent language selection (cookies)

**Language-Specific URLs:**

- URL prefix format (/en/, /de/, /fr/)
- Subdirectory routing (/en/blog/, /de/blog/)
- Automatic URL generation
- Language code in URL (SEO-friendly)

**Fallback Language:**

- Default language configuration
- Fallback if translation missing
- Display original language if translation empty

**SEO for Multi-Language:**

- hreflang tags (automatic)
- Language-specific sitemap (separate XML sitemaps)
- Language-specific RSS feeds
- Canonical URLs per language

**RTL Support:**

- Right-to-left layout (Arabic, Hebrew)
- Auto-detect RTL languages
- Theme RTL CSS support
- Text direction attribute (dir="rtl")

#### 3.2.2 Use Cases

- 🌍 International websites
- 🏢 Multi-national companies
- 📚 Documentation sites (multiple languages)
- 🛒 E-commerce (international shops)

#### 3.2.3 Removed Features (YAGNI)

- ❌ Domain-Based URLs (en.example.com) → Nobody uses this for blogs
- ❌ Translation Memory → Enterprise translation tool feature, v2.0
- ❌ Auto-Translation API → Should be separate community plugin
- ❌ Language Switcher Widget → Merged into "Locale Switcher (Frontend)"

#### 3.2.4 Why Plugin?

Not every website needs multi-language content. Core has admin panel i18n (DE/EN), but content translation is optional for specific use cases.

---

### 3.3 SEO Pro Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Planned
**Feature Count:** ~3

#### 3.3.1 Features

**Schema.org Markup:**

- Article schema (automatic for posts)
- Blog schema (automatic for blog pages)
- Organization schema (configurable in settings)
- JSON-LD format (Google recommended)

**Open Graph & Twitter Cards (Enhanced):**

- Custom OG image per post/page (beyond core defaults)
- Custom Twitter card type (summary, large image)
- Social media preview generator (see how it looks before publishing)
- Fallback to core OG tags if not customized

**Advanced Sitemap:**

- Sitemap priority per post/page (custom priority values)
- Image sitemap (all images in posts/pages)
- Video sitemap (embedded videos)
- News sitemap (Google News format)
- Automatic sitemap regeneration

**Broken Link Checker:**

- Scan posts/pages for broken links
- External & internal link checking
- Email alerts for broken links
- Broken links report (admin panel)
- Manual fixing (no auto-fix suggestions)

#### 3.3.2 Use Cases

- 🔍 SEO-optimized blogs
- 📰 News websites (Google News)
- 📊 Content marketing
- 🏢 Corporate websites (high SEO requirements)

#### 3.3.3 Removed Features (YAGNI)

- ❌ FAQ/How-To/Product Schema → v2.0 (too specific)
- ❌ BreadcrumbList Schema → Theme-specific, not plugin
- ❌ SEO Analysis (Keyword Density, Readability, etc.) → v2.0 (over-engineered)
- ❌ Fix Suggestions (Broken Links) → Manual fixing only

#### 3.3.4 Why Plugin?

Core has SEO basics (slugs, meta tags, sitemap, RSS). SEO Pro is for professionals and agencies who want maximum SEO performance and advanced features.

---

## 4. Plugin Architecture

### 4.1 Event-Driven Architecture

**PineCMS plugins use Laravel's native Event system (NOT WordPress-style hooks).**

**Why Laravel Events?**

- ✅ Type-safe (PHP 8.3 type hints)
- ✅ Laravel-native (no custom implementation)
- ✅ Testable (easy to mock/test)
- ✅ Discoverable (event listeners auto-register)
- ✅ Queueable (async event processing)

**Event Flow:**

```
User Action → Controller → Service → Event Dispatch → Plugin Listener → Plugin Logic
```

**Example:**

```php
// Core dispatches event
Event::dispatch(new PostPublished($post));

// Plugin listens to event
class NewsletterListener
{
    public function handle(PostPublished $event): void
    {
        // Send newsletter when post published
        Newsletter::sendNewPostNotification($event->post);
    }
}
```

### 4.2 Plugin Structure

**Standard Plugin Directory:**

```
/plugins/
  ├── newsletter-plugin/
  │   ├── src/
  │   │   ├── NewsletterServiceProvider.php
  │   │   ├── Events/
  │   │   ├── Listeners/
  │   │   ├── Controllers/
  │   │   ├── Models/
  │   │   └── Views/
  │   ├── database/
  │   │   └── migrations/
  │   ├── resources/
  │   │   ├── js/
  │   │   └── css/
  │   ├── composer.json
  │   ├── package.json
  │   └── plugin.json (metadata)
  └── multi-language/
      └── [same structure]
```

**plugin.json (Metadata):**

```json
{
    "name": "Newsletter Plugin",
    "slug": "newsletter-plugin",
    "version": "1.0.0",
    "requires": "1.1.0",
    "author": "PineCMS Team",
    "license": "MIT",
    "description": "Email newsletters and campaigns",
    "events": ["PostPublished", "UserRegistered"]
}
```

### 4.3 Plugin Lifecycle

**Installation:**

1. User uploads ZIP file via Admin Panel
2. System validates plugin structure
3. System extracts to `/plugins/plugin-slug/`
4. Database stores plugin metadata
5. Plugin appears in "Inactive Plugins" list

**Activation:**

1. User clicks "Activate" button
2. System runs database migrations (if any)
3. Service Provider registers events/listeners
4. Plugin assets compiled (if needed)
5. Plugin status: Active

**Deactivation:**

1. User clicks "Deactivate" button
2. System unregisters events/listeners
3. Plugin data remains in database
4. Plugin status: Inactive

**Uninstallation:**

1. User clicks "Uninstall" button
2. System prompts: "Delete plugin data?" (Yes/No)
3. If Yes: Drop plugin tables, delete files
4. If No: Keep data, delete files only

---

## 5. Core Events Catalog

**PineCMS Core dispatches events for plugin integration.**

### 5.1 Content Events

| Event           | When Fired              | Parameters   |
| --------------- | ----------------------- | ------------ |
| `PostCreated`   | After post created      | `Post $post` |
| `PostUpdated`   | After post updated      | `Post $post` |
| `PostPublished` | Post status → Published | `Post $post` |
| `PostDeleted`   | Before post deleted     | `Post $post` |
| `PageCreated`   | After page created      | `Page $page` |
| `PageUpdated`   | After page updated      | `Page $page` |
| `PagePublished` | Page status → Published | `Page $page` |
| `PageDeleted`   | Before page deleted     | `Page $page` |

### 5.2 Media Events

| Event           | When Fired                   | Parameters     |
| --------------- | ---------------------------- | -------------- |
| `MediaUploaded` | After file uploaded          | `Media $media` |
| `MediaUpdated`  | After media metadata updated | `Media $media` |
| `MediaDeleted`  | Before media deleted         | `Media $media` |

### 5.3 User Events

| Event            | When Fired                 | Parameters   |
| ---------------- | -------------------------- | ------------ |
| `UserRegistered` | After user created         | `User $user` |
| `UserLoggedIn`   | After successful login     | `User $user` |
| `UserLoggedOut`  | After logout               | `User $user` |
| `UserUpdated`    | After user profile updated | `User $user` |
| `UserDeleted`    | Before user deleted        | `User $user` |

### 5.4 Comment Events (v1.1.0+)

| Event             | When Fired                | Parameters         |
| ----------------- | ------------------------- | ------------------ |
| `CommentCreated`  | After comment created     | `Comment $comment` |
| `CommentApproved` | Comment status → Approved | `Comment $comment` |
| `CommentRejected` | Comment status → Rejected | `Comment $comment` |
| `CommentDeleted`  | Before comment deleted    | `Comment $comment` |

### 5.5 Theme Events

| Event                 | When Fired           | Parameters     |
| --------------------- | -------------------- | -------------- |
| `theme.head.before`   | Before `</head>` tag | `string $html` |
| `theme.head.after`    | After `<head>` tag   | `string $html` |
| `theme.footer.before` | Before `</body>` tag | `string $html` |
| `theme.footer.after`  | After footer         | `string $html` |

**Use Case:** Inject custom scripts, analytics codes, meta tags

### 5.6 System Events

| Event                     | When Fired           | Parameters |
| ------------------------- | -------------------- | ---------- |
| `SystemInstalled`         | After CMS installed  | `null`     |
| `CacheCleared`            | After cache cleared  | `null`     |
| `MaintenanceModeEnabled`  | Maintenance mode ON  | `null`     |
| `MaintenanceModeDisabled` | Maintenance mode OFF | `null`     |

---

## 6. Plugin Installation & Activation

### 6.1 Installation Process

**Step 1: Download**

- Download plugin ZIP from [plugins.pinecms.org](https://plugins.pinecms.org) (future)
- Or: Clone from GitHub (developers)

**Step 2: Upload**

- Navigate to: Admin Panel → Plugins → Upload Plugin
- Upload ZIP file (max 50MB)
- System validates:
    - `plugin.json` exists
    - Required PineCMS version met
    - No conflicts with existing plugins

**Step 3: Extract**

- Automatic extraction to `/plugins/plugin-slug/`
- Permissions check (writable directory)
- Database entry created (status: Inactive)

**Step 4: Activation**

- Admin Panel → Plugins → Plugin List
- Click "Activate" button
- System runs:
    - Database migrations
    - Event registration
    - Asset compilation (if needed)
- Plugin status: Active

### 6.2 Configuration

**Step 5: Configure (Optional)**

- Admin Panel → Plugins → Plugin Settings
- Plugin-specific settings (SMTP, API keys, etc.)
- Save settings

**Settings Storage:**

- Database table: `plugin_settings`
- JSON format for flexibility
- Encrypted if sensitive (API keys, passwords)

---

## 7. Community Plugin Ecosystem

### 7.1 Community Plugin Vision

**Goal:** Enable community to extend PineCMS for unique requirements.

**Community Plugins Can:**

- Integrate external services (Mailchimp, Google Analytics, social media)
- Add custom content types (events, products, portfolios)
- Add advanced custom fields (repeater, gallery, relationships, JSON, color picker, file upload)
- Extend UI (page builders, widgets, dashboards)
- Add authentication methods (OAuth2, SSO)

### 7.2 Priority Community Plugins

**Custom Fields:**

- 📁 **Custom Fields Pro** (Repeater, Gallery, Relationships, JSON, Color Picker, Rich Text, File Upload, Conditional Logic)
- 📍 Address & Location Plugin (Address Fields, Google Maps, Geocoding)

**Analytics & Tracking:**

- 📊 Matomo Analytics Plugin
- 📊 Plausible Analytics Plugin
- 📊 Google Analytics 4 Plugin
- 📊 Fathom Analytics Plugin

**Social Media:**

- 📱 Social Media Auto-Post (Twitter, Facebook, LinkedIn)
- 🖼️ Instagram Gallery Widget
- 💬 Discord/Slack Notifications

**Content & Forms:**

- 📧 Mailchimp Integration
- 🎨 Page Builder (Drag & Drop)
- 📝 Advanced Form Builder
- 👍 Voting System (Upvote/Downvote, Reddit/HackerNews-style)

**E-commerce & Payments:**

- 🛒 WooCommerce Integration
- 💳 Stripe Payments
- 🛍️ Simple Product Catalog

**Security & Auth:**

- 🔐 OAuth2 Login (GitHub, Google, Facebook)
- 🛡️ Advanced Security (IP Whitelist/Blacklist, Login Limits)
- 🔑 Two-Factor Authentication

**Advanced Features:**

- 🌐 Advanced Multi-Language (DeepL Translation)
- 📈 Advanced Analytics (Heatmaps, Session Recording)
- 🎥 Video Hosting (YouTube, Vimeo playlists)
- 🔄 Newsletter A/B Testing
- 📅 Recurring Newsletters

### 7.3 Plugin Directory (Future)

**Plugin Directory:** [plugins.pinecms.org](https://plugins.pinecms.org)

**Planned for:** v2.0 or later

**Features:**

- Browse/search community plugins
- Plugin ratings & reviews
- Download statistics
- Security audit status
- Compatibility matrix

---

## 8. Plugin Development Guidelines

### 8.1 Technical Requirements

**Development Stack:**

- PHP 8.3+ (same as core)
- Laravel 12 Service Provider pattern
- Event-Driven Architecture (Laravel Events)
- Vue 3.5 + Inertia.js (for admin UI)
- Blade templates (for frontend)
- TailwindCSS 4 (styling)
- Vite (asset compilation)

**Testing:**

- PHPUnit for backend tests
- Vitest for frontend tests
- Feature tests required for core functionality
- Test coverage > 80% recommended

**Code Quality:**

- PSR-12 coding standards
- PHPStan level 8 (strict types)
- Laravel Pint for formatting
- ESLint + Prettier for JavaScript

### 8.2 Best Practices

**Do:**

- ✅ Use Laravel Events (NOT custom hooks)
- ✅ Follow Laravel naming conventions
- ✅ Type-hint everything (PHP 8.3)
- ✅ Write tests for core features
- ✅ Document all public APIs
- ✅ Use semantic versioning
- ✅ Provide upgrade guides

**Don't:**

- ❌ Modify core files
- ❌ Use global variables
- ❌ Hardcode URLs/paths
- ❌ Ignore security best practices
- ❌ Bundle large dependencies unnecessarily

### 8.3 Security Guidelines

**Required:**

- Validate all user input
- Sanitize output (XSS prevention)
- Use Laravel's CSRF protection
- Follow OWASP Top 10 guidelines
- Encrypt sensitive data (API keys, passwords)
- Use prepared statements (SQL injection prevention)

**Recommended:**

- Regular security audits
- Dependency updates (npm audit, composer audit)
- Security disclosure policy

---

## 9. Plugin Summary

### 9.1 Official Plugins Overview

| Plugin             | Features | Requires | Release |
| ------------------ | -------- | -------- | ------- |
| **Newsletter**     | ~6       | v1.1.0+  | v1.1.0  |
| **Multi-Language** | ~8       | v1.1.0+  | v1.1.0  |
| **SEO Pro**        | ~3       | v1.1.0+  | v1.1.0  |
| **Total**          | **~17**  | -        | -       |

### 9.2 Removed from Official Plugins

**Complete Plugins Removed:**

- ❌ **Custom Fields Pro** → **Community Plugin** (Repeater, Gallery, Relationships, JSON, etc.)
- ❌ Webhooks (~11 features) → Over-engineered, v2.0
- ❌ Two-Factor Auth (~5 features) → Core security sufficient, v2.0
- ❌ Form Builder (~12 features) → Too complex, v2.0
- ❌ Workflow (~6 features) → **Moved to Core v1.1.0** (essential for teams)
- ❌ Matomo Analytics → **Completely removed** (users integrate own analytics)

**Result:** Original 8 Plugins → 3 Official Plugins, ~60 → ~17 Features (72% reduction)

### 9.3 Analytics Integration Strategy

**PineCMS does NOT include analytics by default.**

**Users can integrate analytics via:**

1. **Community Plugins** (recommended):
    - Matomo Plugin
    - Plausible Plugin
    - Google Analytics 4 Plugin
    - Fathom Plugin

2. **Theme Event Listeners:**
    - Hook into `theme.head.before` event
    - Inject analytics code in theme

3. **Custom Code Injection:**
    - Admin Panel → Settings → Advanced → Custom Code
    - Paste tracking code in "Header Scripts" field

**Why No Default Analytics?**

- Privacy-first approach (no tracking by default)
- User choice (integrate what they prefer)
- GDPR compliance (opt-in, not opt-out)
- Performance (no unused analytics code)

---

## 10. Change History

| Date       | Version | Author       | Changes                                                                                                                                 |
| ---------- | ------- | ------------ | --------------------------------------------------------------------------------------------------------------------------------------- |
| 2025-11-07 | 1.0     | PineCMS Team | Initial plugin ecosystem specification (3 official plugins all v1.1.0, Custom Fields Pro → Community Plugin, event-driven architecture) |

---

**Last Updated:** 2025-11-07
**Document Owner:** PineCMS Team
**Next Review:** 2025-12-07
