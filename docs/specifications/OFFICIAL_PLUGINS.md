# 🔌 PineCMS - Official Plugins

> **Last Updated:** 2025-01-26
> **License:** MIT - 100% Open Source & Kostenlos
> **Plugin API Version:** v1.1.0+
> **Breaking Changes:** See [PLUGIN_DEVELOPMENT.md](./PLUGIN_DEVELOPMENT.md) for API changes and migration guides

---

## 🎯 Plugin-Philosophie

**PineCMS Official Plugins** erweitern den Core um spezialisierte Features, die nicht jeder Nutzer benötigt. Alle Official Plugins sind:

- ✅ **100% Open Source** (MIT License)
- ✅ **Kostenlos** für alle Nutzer
- ✅ **Vom PineCMS Team** entwickelt und gewartet
- ✅ **Shared Hosting Compatible**
- ✅ **Einfach installierbar** (ZIP Upload + Aktivierung)
- ✅ **Deaktivierbar** ohne Datenverlust

---

## 📦 Official Plugins (8 Plugins)

### 1. Newsletter Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.1.0)

#### Features (~7 Features)

- **Newsletter Editor** - Separate von Posts, eigenes Content-Type
- **Subscriber Management** - Separate von Users:
    - Import/Export Subscribers (CSV)
    - Subscriber Groups/Tags
    - Subscriber Status (Active, Unsubscribed, Bounced)
- **Email Campaign Scheduling:**
    - Send Now
    - Schedule for Later
- **Newsletter Templates:**
    - Template Selection (5 vordefinierte Templates)
    - HTML/Text Preview
    - Responsive Email Templates (Mobile-First)
- **Privacy-Friendly Tracking** (optional):
    - Open Rate Tracking (optional, disable by default)
    - Click Tracking (optional, disable by default)
    - Privacy Mode (no tracking, GDPR-compliant)
- **Unsubscribe Management:**
    - One-Click Unsubscribe Link
    - Unsubscribe Reasons (optional feedback)
    - Unsubscribe Confirmation Page
- **Double Opt-In** (GDPR-compliant):
    - Email Verification before subscribing
    - Confirmation Email Template
    - Opt-In Timestamp
- **Subscriber Segmentation:**
    - Filter by Tags
    - Filter by Categories

**Removed (YAGNI):**

- ❌ A/B Testing → Enterprise Feature, Community Plugin or v2.0
- ❌ Recurring Newsletters → v2.0
- ❌ Newsletter Archive RSS Feed → Nobody needs RSS for archives
- ❌ Custom Segments → Filter by Tags/Categories is sufficient
- ❌ Drag & Drop Template Builder → Simplified to Template Selection

#### Infrastructure Requirements

**Email Sending:**
- **SMTP Server Required** - Configure external SMTP service (Mailgun, SendGrid, Amazon SES, or custom SMTP)
- **Shared Hosting Limits** - Most shared hosts limit emails/hour (typically 100-500/hour)
- **Queue System** - Laravel Queues used for background email processing
- **Rate Limiting:**
    - Configurable sending rate (e.g., 100 emails/hour)
    - Automatic throttling to prevent SMTP service blocks
    - Retry logic for failed sends

**Bounce & Complaint Handling:**
- **Bounce Detection** - Requires webhook support from SMTP service (Mailgun, SendGrid)
- **Automated Unsubscribe** - Hard bounces auto-mark subscribers as "Bounced"
- **Complaint Handling** - Spam complaints auto-unsubscribe users (via webhook)

**Scalability Considerations:**
- **Small Lists (< 1,000)** - Shared hosting SMTP sufficient
- **Medium Lists (1,000-10,000)** - Dedicated SMTP service recommended (Mailgun, SendGrid)
- **Large Lists (10,000+)** - Enterprise SMTP + dedicated queue workers required

**Privacy & Compliance:**
- **GDPR Compliant** - Double opt-in, unsubscribe tracking, data export
- **CAN-SPAM Act** - Physical address required in footer (configurable)
- **Cookie-Free Tracking** - Optional tracking mode without cookies

#### Use Cases

- 📧 Blog-Newsletter (neue Posts automatisch versenden)
- 📰 Wöchentlicher/Monatlicher Newsletter
- 🎯 Marketing Campaigns
- 📢 Ankündigungen & Updates

#### Why Plugin?

Nicht jeder Blogger/Website-Betreiber braucht Newsletter-Funktionalität. Externe SMTP-Services (Mailgun, SendGrid, Amazon SES) sind optional nötig für größere Listen.

---

### 2. Webhooks Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.1.0)

#### Features (~11 Features)

- **Webhook Management UI** - Create, Edit, Delete
- **Webhook Configuration:**
    - Name & Description
    - Target URL
    - HTTP Method (POST, PUT, PATCH)
    - Event Selection (Multi-Select)
- **8 Event Types:**
    - `post.published` - When a post is published
    - `post.updated` - When a post is edited
    - `post.deleted` - When a post is deleted
    - `user.created` - When a new user registers
    - `user.updated` - When user profile is updated
    - `comment.created` - When a comment is posted
    - `comment.approved` - When a comment is approved
    - `page.published` - When a page is published
- **Custom Headers & Payloads:**
    - Custom HTTP Headers (Authorization, Content-Type, etc.)
    - Payload Customization (JSON template)
    - Secret Token (for signature verification)
- **Webhook Testing & Logs:**
    - Test Webhook (Send test payload)
    - Webhook History (last 100 requests)
    - Request/Response Logs
    - Error Logs (failed webhooks)
- **Retry Logic** (on failure):
    - Auto-Retry on 5xx errors
    - Exponential Backoff (1s, 2s, 4s, 8s, 16s)
    - Max Retries (configurable, default: 5)
    - Disable Webhook after N failures
- **Webhook Signing** (for security):
    - HMAC-SHA256 Signature
    - Signature Header (X-PineCMS-Signature)
    - Secret Key Management
- **Rate Limiting** (per webhook):
    - Max Requests per Minute (configurable)
    - Queue Webhooks if rate limit exceeded

#### Use Cases

- 🔗 Zapier Integration
- 💬 Discord/Slack Notifications
- 🤖 Build Triggers (Vercel, Netlify)
- 📊 Analytics Integration
- 🔄 Multi-Site Synchronization

#### Why Plugin?

Webhooks sind ein fortgeschrittenes Feature für Automation & Integrations. Nicht jeder Nutzer braucht externe Integrationen.

---

### 3. Custom Fields Pro Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.0.0
**Status:** 📋 Geplant (Planned for v1.1.0)

#### Features (~7 Advanced Field Types)

- **Relationship Fields:**
    - Link to Posts
    - Link to Pages
    - Link to Users
    - Link to Categories/Tags
    - Bi-Directional Relationships
- **Repeater Field:**
    - Nested Fields (unlimited depth)
    - Add/Remove/Reorder Rows
    - Field Groups
    - Collapsible Rows
- **Gallery Field:**
    - Multiple Image Upload
    - Drag & Drop Reorder
    - Image Captions & Alt Text
    - Thumbnail Preview
- **JSON Field:**
    - JSON Textarea with Syntax Highlighting
    - Validation
- **Color Picker:**
    - Hex, RGB, HSL
    - Opacity/Alpha Support
    - Preset Colors
    - Recent Colors
- **Rich Text Field:**
    - TipTap Editor instance
    - Size Option (Full/Mini)
    - Independent formatting
    - Media Embedding (Full mode only)
- **File Field:**
    - Upload any file type
    - File Preview
    - File Metadata

**Simplified (KISS):**

- JSON Field: Removed Monaco Editor → Simple Textarea with Syntax Highlighting
- JSON Field: Schema Validation → Removed (optional in v2.0)

**Merged (DRY):**

- ❌ "Rich Text Field" + "WYSIWYG Field" → **ONE** "Rich Text Field" with Size Option (Full/Mini)

#### Use Cases

- 📁 Portfolio Websites (Project Fields: Screenshots, Tech Stack, Live URL)
- 🏨 Directories (Hotels, Restaurants: Custom repeater fields)
- 🎬 Event-Sites (Event Fields: Date, Location, Registration forms)
- 📚 Documentation (Code Examples, API References)

#### Why Plugin?

Core hat basic fields (Text, Number, Date, Boolean). Advanced field types sind für spezielle Use Cases (Portfolios, Verzeichnisse, komplexe Content-Strukturen).

**Note:** Specialized field types like Address Fields (Street, City, ZIP, Country) and Google Maps Integration are available as separate community plugins to keep this plugin focused.

---

### 4. Multi-Language Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.1.0)

#### Features (~7 Features)

- **Multi-Language Support:**
    - Unlimited Languages
    - Pre-configured Languages (DE, EN, FR, ES, IT, PT, etc.)
    - Custom Languages (Add any language)
- **Content Translation UI:**
    - Side-by-Side Editor (Original + Translation)
    - Copy from Original Button
    - Translation Progress Indicator
    - Missing Translations Warning
- **Translatable Fields:**
    - Post Title & Content
    - Page Title & Content
    - Categories & Tags
    - Site Settings (Name, Tagline, etc.)
    - SEO Meta (Title, Description)
- **Locale Switcher** (Frontend):
    - Language Dropdown
    - Flag Icons
    - Language-Specific URLs
    - Auto-Detect Browser Language
- **Language-Specific URLs:**
    - URL Prefix (/en/, /de/)
    - Subdirectory (/en/, /de/)
- **Fallback Language:**
    - Default Language Selection
    - Fallback if translation missing
- **SEO for Multi-Language:**
    - hreflang Tags (automatic)
    - Language-Specific Sitemap
    - Language-Specific RSS

**Removed (YAGNI):**

- ❌ Domain-Based URLs (en.example.com) → Nobody uses this for blogs, URL Prefix is sufficient
- ❌ Translation Memory → Enterprise Translation Tool feature, v2.0 or remove
- ❌ Auto-Translation API → Should be separate Community Plugin
- ❌ Language Switcher Widget → Merged into "Locale Switcher (Frontend)"

#### Use Cases

- 🌍 International Websites
- 🏢 Multi-National Companies
- 📚 Documentation Sites (multiple languages)
- 🛒 E-Commerce (international shops)

#### Why Plugin?

Nicht jede Website braucht mehrsprachige Inhalte. Core hat Admin Panel i18n (DE/EN), aber Content Translation ist optional.

---

### 5. Workflow Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.1.0)

#### Features (~6 Features)

- **Content Status Workflow:**
    - **Draft** - Work in progress
    - **Review** - Submitted for review
    - **Published** - Live on website
- **Reviewer Assignment:**
    - Assign specific users as reviewers
    - Multiple reviewers per post
    - Reviewer Notifications (Email)
- **Approval/Rejection:**
    - Approve Button (publish immediately)
    - Reject Button (send back to draft)
    - Request Changes (with feedback)
- **Review Comments:**
    - General Feedback
    - Comment Thread (Discussion)
- **Workflow History:**
    - Who Reviewed (User, Date, Time)
    - Decision (Approved, Rejected, Changes Requested)
    - Feedback/Comments
    - Status Change Log
- **Email Notifications:**
    - Review Request (to Reviewer)
    - Approval Notification (to Author)
    - Rejection Notification (to Author)
    - Changes Requested (to Author)

**Removed (YAGNI/KISS):**

- ❌ Inline Comments (per paragraph) → Too complex for MVP, General Feedback is sufficient, v2.0
- ❌ Workflow Dashboard → Removed, use Admin Panel filters instead
- ❌ Custom Workflow States → 3 states (Draft, Review, Published) are sufficient, v2.0

#### Use Cases

- 👥 Team Blogs (multiple authors + editors)
- 📰 Redaktionen (Journalisten + Chefredakteur)
- 🏢 Corporate Websites (Marketing + Legal Review)
- 🎓 Education (Students + Teachers)

#### Why Plugin?

Solo-Blogger brauchen keinen Workflow. Nur Teams mit mehreren Autoren und Reviewern profitieren davon.

---

### 6. Two-Factor Auth Plugin (2FA)

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.1.0)

#### Features (~5 Features)

- **2FA Setup:**
    - QR Code Generation
    - Manual Secret Key (for manual entry)
    - Verify Setup (test 2FA before enabling)
- **TOTP Support:**
    - Google Authenticator
    - Microsoft Authenticator
    - Authy
    - 1Password, Bitwarden, etc.
- **Backup Codes:**
    - Generate 10 backup codes
    - One-time use
    - Regenerate codes
    - Download codes (TXT)
- **Recovery Options:**
    - Email-based recovery
    - Admin can reset 2FA for users
- **Per-User Enable/Disable:**
    - Users can enable 2FA in profile
    - Optional: Enforce 2FA for Administrator role
    - Optional: Enforce 2FA for all roles

#### Use Cases

- 🔒 High-Security Websites
- 💼 Corporate/Business Sites
- 🏦 Financial/Banking Content
- 👥 Multi-User Sites (protect admin accounts)

#### Why Plugin?

2FA ist ein zusätzliches Security-Feature, aber nicht jeder braucht es (z.B. Personal Blogs). Core hat bereits sichere Password-Policies und Rate Limiting.

---

### 7. Form Builder Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.2.0)

#### Features (~12 Features)

- **Drag & Drop Form Builder:**
    - Visual Form Editor
    - Drag & Drop Field Placement
    - Field Reordering
    - Field Duplication
- **12 Field Types:**
    - Text (Short, Long, Textarea)
    - Email, URL, Phone
    - Number, Date, Time
    - Select (Dropdown, Multi-Select, Radio)
    - Checkbox, Toggle
    - File Upload
- **Validation Rules:**
    - Required Field
    - Min/Max Length
    - Email Validation
    - URL Validation
    - Custom Regex
- **Form Submissions:**
    - Submissions Management (Admin Panel)
    - View Submission Details
    - Export Submissions (CSV, JSON)
    - Delete Submissions
    - Search & Filter Submissions
- **Email Notifications:**
    - Send to Admin
    - Send to User (with custom message)
    - Custom Email Templates
    - Multiple Recipients
- **Form Settings:**
    - Success Message
    - Redirect URL (after submission)
    - Submit Button Text
    - Form Title & Description
- **Anti-Spam:**
    - Honeypot Field (hidden)
    - reCAPTCHA v2 Integration (optional)
    - hCaptcha Integration (optional)
    - Rate Limiting (submissions per IP)

**Removed (YAGNI/Enterprise):**

- ❌ Conditional Logic → Enterprise Feature, too complex for v1.0, v2.0
- ❌ Multi-Step Forms (Step-by-Step wizard, Save & Continue Later) → Enterprise Feature, v2.0

#### Use Cases

- 📧 Kontaktformulare
- 📝 Umfragen & Feedback
- 🎟️ Event-Anmeldungen
- 📞 Support-Tickets
- 💼 Job-Bewerbungen

#### Why Plugin?

Nicht jede Website braucht Formulare. Core hat keine Formular-Funktionalität, dieses Plugin fügt sie hinzu.

---

### 8. SEO Pro Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.2.0
**Status:** 📋 Geplant (Planned for v1.2.0)

#### Features (~5 Features)

- **Schema.org Markup** (Advanced):
    - Article Schema (automatic)
    - Blog Schema
    - Organization Schema
    - BreadcrumbList Schema
    - FAQ Schema (manual)
    - How-To Schema (manual)
    - Product Schema (manual)
- **Open Graph & Twitter Cards** (Enhanced):
    - Custom OG Image per Post/Page
    - Custom Twitter Card Type
    - Preview Generator (see how it looks on social media)
- **Advanced Sitemap:**
    - Sitemap Priority per Post/Page
    - Image Sitemap
    - Video Sitemap
    - News Sitemap (for Google News)
- **Broken Link Checker:**
    - Scan all Posts/Pages for broken links
    - External Link Checking
    - Internal Link Checking
    - Email Alerts (when broken link found)
    - Fix Suggestions
- **SEO Analysis** (per Post/Page):
    - Keyword Density
    - Readability Score
    - Meta Length Check (Title, Description)
    - Image Alt Text Check
    - Internal/External Link Count

**Removed (Deprecated/DRY):**

- ❌ AMP Support → Google deprecated AMP in 2021, nobody uses it anymore
- ❌ Redirect Management (Enhanced) → DRY violation, Core v1.2.0 already has Redirect Management
    - Note: Import Redirects (CSV) merged into Core Redirect Management feature
- ❌ Canonical URL Management → Should be Core feature, not plugin

#### Use Cases

- 🔍 SEO-optimierte Blogs
- 📰 News-Websites (Google News)
- 📊 Content Marketing
- 🏢 Corporate Websites (hohe SEO-Anforderungen)

#### Why Plugin?

Core hat SEO Basics (Slugs, Meta, Sitemap, RSS). SEO Pro ist für Profis und Agenturen die maximale SEO-Performance wollen.

---

## 🚀 Plugin Installation

### Schritt 1: Download

- Download Plugin ZIP von [plugins.pinecms.org](https://plugins.pinecms.org) (später)
- Oder: Clone von GitHub

### Schritt 2: Upload

- Admin Panel → Plugins → Upload Plugin
- ZIP-Datei hochladen
- Automatische Entpackung nach `/plugins/plugin-name/`

### Schritt 3: Aktivierung

- Admin Panel → Plugins → Plugin Liste
- "Aktivieren" Button klicken
- Plugin wird registriert und Events werden gehookt

### Schritt 4: Konfiguration (optional)

- Admin Panel → Plugins → Plugin Settings
- Plugin-spezifische Einstellungen vornehmen

---

## 🛠️ Plugin-Entwicklung

Möchtest du eigene Plugins entwickeln?

➡️ Siehe `docs/PLUGIN_DEVELOPMENT.md` für detaillierte Anleitung

**Key Features:**

- Laravel Service Provider Pattern
- Event-Driven Architecture (Laravel Events)
- Vue/Inertia.js für Admin UI
- Blade Templates für Frontend
- Database Migrations support
- Asset Compilation (Vite)

---

## 🌍 Community Plugins

Die Community kann eigene Plugins entwickeln und teilen:

**Beispiele (zukünftig):**

- 📧 Mailchimp Integration
- 📱 Social Media Auto-Post (Twitter, Facebook, LinkedIn)
- 🖼️ Instagram Gallery Widget
- 📊 Advanced Charts & Graphs
- 🎨 Page Builder (Drag & Drop)
- 🔐 OAuth2 Login (GitHub, Google, Facebook)
- 🛒 WooCommerce Integration
- 💬 Discord/Slack Notifications
- 🎥 Video Hosting (YouTube, Vimeo playlists)
- 📈 Google Analytics 4 Integration

**Potential Community Plugins:**

- 👍 Voting System Plugin (Upvote/Downvote for Posts & Comments, Reddit/HackerNews-style)
- 📍 Address & Location Plugin (Address Fields, Google Maps, Geocoding)
- 🌐 Advanced Multi-Language (DeepL Translation, Language Detection)
- 🎨 Advanced Page Builder (Visual Drag & Drop Layouts)
- 📊 Advanced Analytics (Heatmaps, Session Recording, A/B Testing)
- 🛡️ Advanced Security (IP Whitelist/Blacklist, Login Attempts Limit, Security Headers)
- 🎨 Social Sharing Plugin (Share Buttons for Twitter, Facebook, LinkedIn, etc.)
- 🔄 Newsletter A/B Testing Plugin (Subject Line & Content Testing)
- 📅 Recurring Newsletters Plugin (Weekly/Monthly Automation)

**Plugin Directory:** [plugins.pinecms.org](https://plugins.pinecms.org) (geplant für v2.0)

---

## 📊 Plugin Summary

| Plugin | Features | Requires | Status |
|--------|----------|----------|--------|
| **Newsletter** | ~7 | v1.1.0+ | Geplant |
| **Webhooks** | ~11 | v1.1.0+ | Geplant |
| **Custom Fields Pro** | ~7 | v1.0.0+ | Geplant |
| **Multi-Language** | ~7 | v1.1.0+ | Geplant |
| **Workflow** | ~6 | v1.1.0+ | Geplant |
| **Two-Factor Auth** | ~5 | v1.1.0+ | Geplant |
| **Form Builder** | ~12 | v1.1.0+ | Geplant |
| **SEO Pro** | ~5 | v1.2.0+ | Geplant |
| **Total** | **~60** | - | - |

**Software Engineering Improvements:**

- Removed 15 YAGNI features (Enterprise/overengineered features → v2.0 or Community Plugins)
- Fixed 1 DRY violation (merged Rich Text + WYSIWYG fields)
- Simplified 3 KISS violations (JSON Editor, Template Builder, Workflow)
- Eliminated deprecated features (AMP Support)

---

**Last Updated:** 2025-01-26
**Maintained By:** PineCMS Team
**License:** MIT - 100% Open Source & Kostenlos
