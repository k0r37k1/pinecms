# 🔌 PineCMS - Official Plugins

> **Last Updated:** 2025-01-26
> **License:** MIT - 100% Open Source & Kostenlos
> **Plugin API Version:** 1.0

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
**Status:** Geplant für v1.1.0 Release

#### Features (~10 Features)

- [ ] Newsletter Editor (separate von Posts, eigenes Content-Type)
- [ ] Subscriber Management (separate von Users):
  - Import/Export Subscribers (CSV)
  - Subscriber Groups/Tags
  - Subscriber Status (Active, Unsubscribed, Bounced)
- [ ] Email Campaign Scheduling:
  - Send Now
  - Schedule for Later
  - Recurring Newsletters (Weekly, Monthly)
- [ ] Newsletter Templates:
  - Drag & Drop Template Builder
  - HTML/Text Preview
  - Responsive Email Templates (Mobile-First)
- [ ] Privacy-Friendly Tracking (optional):
  - Open Rate Tracking (optional, disable by default)
  - Click Tracking (optional, disable by default)
  - Privacy Mode (no tracking, GDPR-compliant)
- [ ] Unsubscribe Management:
  - One-Click Unsubscribe Link
  - Unsubscribe Reasons (optional feedback)
  - Unsubscribe Confirmation Page
- [ ] Double Opt-In (GDPR-compliant):
  - Email Verification before subscribing
  - Confirmation Email Template
  - Opt-In Timestamp
- [ ] Subscriber Segmentation:
  - Filter by Tags
  - Filter by Categories
  - Custom Segments
- [ ] A/B Testing:
  - Subject Line Testing
  - Content Variants
  - Winner Selection (Auto/Manual)
- [ ] Newsletter Archive:
  - Public Archive Page (all past newsletters)
  - Private Archive (for subscribers only)
  - RSS Feed for Newsletter Archive

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
**Status:** Geplant für v1.1.0 Release

#### Features (~11 Features)

- [ ] Webhook Management UI (Create, Edit, Delete)
- [ ] Webhook Configuration:
  - Name & Description
  - Target URL
  - HTTP Method (POST, PUT, PATCH)
  - Event Selection (Multi-Select)
- [ ] 8 Event Types:
  - `post.published` - When a post is published
  - `post.updated` - When a post is edited
  - `post.deleted` - When a post is deleted
  - `user.created` - When a new user registers
  - `user.updated` - When user profile is updated
  - `comment.created` - When a comment is posted
  - `comment.approved` - When a comment is approved
  - `page.published` - When a page is published
- [ ] Custom Headers & Payloads:
  - Custom HTTP Headers (Authorization, Content-Type, etc.)
  - Payload Customization (JSON template)
  - Secret Token (for signature verification)
- [ ] Webhook Testing & Logs:
  - Test Webhook (Send test payload)
  - Webhook History (last 100 requests)
  - Request/Response Logs
  - Error Logs (failed webhooks)
- [ ] Retry Logic (on failure):
  - Auto-Retry on 5xx errors
  - Exponential Backoff (1s, 2s, 4s, 8s, 16s)
  - Max Retries (configurable, default: 5)
  - Disable Webhook after N failures
- [ ] Webhook Signing (for security):
  - HMAC-SHA256 Signature
  - Signature Header (X-PineCMS-Signature)
  - Secret Key Management
- [ ] Rate Limiting (per webhook):
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
**Status:** Geplant für v1.1.0 Release

#### Features (~8 Advanced Field Types)

- [ ] **Relationship Fields:**
  - Link to Posts
  - Link to Pages
  - Link to Users
  - Link to Categories/Tags
  - Bi-Directional Relationships
- [ ] **Repeater Field:**
  - Nested Fields (unlimited depth)
  - Add/Remove/Reorder Rows
  - Field Groups
  - Collapsible Rows
- [ ] **Gallery Field:**
  - Multiple Image Upload
  - Drag & Drop Reorder
  - Image Captions & Alt Text
  - Thumbnail Preview
- [ ] **JSON Field:**
  - JSON Editor (Monaco Editor)
  - Syntax Highlighting
  - Validation
  - Schema Validation (optional)
- [ ] **Color Picker:**
  - Hex, RGB, HSL
  - Opacity/Alpha Support
  - Preset Colors
  - Recent Colors
- [ ] **Rich Text Field:**
  - Full TipTap Editor instance
  - Independent formatting
  - Media Embedding
- [ ] **File Field:**
  - Upload any file type
  - File Preview
  - File Metadata
- [ ] **WYSIWYG Field:**
  - Mini TipTap instance
  - Basic formatting only
  - Lightweight

#### Use Cases

- 📁 Portfolio Websites (Project Fields: Screenshots, Tech Stack, Live URL)
- 🏨 Verzeichnisse (Hotels, Restaurants: Address, Phone, Hours)
- 🎬 Event-Sites (Event Fields: Date, Location, Tickets)
- 📚 Documentation (Code Examples, API References)

#### Why Plugin?

Core hat basic fields (Text, Number, Date, Boolean). Advanced field types sind für spezielle Use Cases (Portfolios, Verzeichnisse, komplexe Content-Strukturen).

---

### 4. Multi-Language Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** Geplant für v1.1.0 Release

#### Features (~10 Features)

- [ ] Multi-Language Support:
  - Unlimited Languages
  - Pre-configured Languages (DE, EN, FR, ES, IT, PT, etc.)
  - Custom Languages (Add any language)
- [ ] Content Translation UI:
  - Side-by-Side Editor (Original + Translation)
  - Copy from Original Button
  - Translation Progress Indicator
  - Missing Translations Warning
- [ ] Translatable Fields:
  - Post Title & Content
  - Page Title & Content
  - Categories & Tags
  - Site Settings (Name, Tagline, etc.)
  - SEO Meta (Title, Description)
- [ ] Locale Switcher (Frontend):
  - Language Dropdown
  - Flag Icons
  - Language-Specific URLs
  - Auto-Detect Browser Language
- [ ] Language-Specific URLs:
  - URL Prefix (/en/, /de/)
  - Domain-Based (en.example.com, de.example.com)
  - Subdirectory (/en/, /de/)
- [ ] Fallback Language:
  - Default Language Selection
  - Fallback if translation missing
- [ ] Translation Memory:
  - Reuse previous translations
  - Translation Suggestions
- [ ] Language Switcher Widget:
  - Frontend Widget
  - Admin Panel Language Switcher
- [ ] SEO for Multi-Language:
  - hreflang Tags (automatic)
  - Language-Specific Sitemap
  - Language-Specific RSS
- [ ] Auto-Translation API (optional):
  - DeepL Integration (API Key required)
  - Google Translate Integration
  - Manual Review after auto-translation

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
**Status:** Geplant für v1.1.0 Release

#### Features (~8 Features)

- [ ] Content Status Workflow:
  - **Draft** - Work in progress
  - **Review** - Submitted for review
  - **Published** - Live on website
- [ ] Reviewer Assignment:
  - Assign specific users as reviewers
  - Multiple reviewers per post
  - Reviewer Notifications (Email)
- [ ] Approval/Rejection:
  - Approve Button (publish immediately)
  - Reject Button (send back to draft)
  - Request Changes (with feedback)
- [ ] Review Comments:
  - Inline Comments (per paragraph)
  - General Feedback
  - Comment Thread (Discussion)
- [ ] Workflow History:
  - Who Reviewed (User, Date, Time)
  - Decision (Approved, Rejected, Changes Requested)
  - Feedback/Comments
  - Status Change Log
- [ ] Email Notifications:
  - Review Request (to Reviewer)
  - Approval Notification (to Author)
  - Rejection Notification (to Author)
  - Changes Requested (to Author)
- [ ] Workflow Dashboard:
  - Pending Reviews (Reviewer's view)
  - My Submissions (Author's view)
  - Review Statistics
- [ ] Custom Workflow States (optional):
  - Add custom states (e.g., "Final Review", "Legal Check")
  - Custom workflow rules

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
**Status:** Geplant für v1.1.0 Release

#### Features (~5 Features)

- [ ] 2FA Setup:
  - QR Code Generation
  - Manual Secret Key (for manual entry)
  - Verify Setup (test 2FA before enabling)
- [ ] TOTP Support:
  - Google Authenticator
  - Microsoft Authenticator
  - Authy
  - 1Password, Bitwarden, etc.
- [ ] Backup Codes:
  - Generate 10 backup codes
  - One-time use
  - Regenerate codes
  - Download codes (TXT)
- [ ] Recovery Options:
  - Email-based recovery
  - Admin can reset 2FA for users
- [ ] Per-User Enable/Disable:
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
**Status:** Geplant für v1.2.0 Release

#### Features (~15 Features)

- [ ] Drag & Drop Form Builder:
  - Visual Form Editor
  - Drag & Drop Field Placement
  - Field Reordering
  - Field Duplication
- [ ] 12 Field Types:
  - Text (Short, Long, Textarea)
  - Email, URL, Phone
  - Number, Date, Time
  - Select (Dropdown, Multi-Select, Radio)
  - Checkbox, Toggle
  - File Upload
- [ ] Validation Rules:
  - Required Field
  - Min/Max Length
  - Email Validation
  - URL Validation
  - Custom Regex
- [ ] Form Submissions:
  - Submissions Management (Admin Panel)
  - View Submission Details
  - Export Submissions (CSV, JSON)
  - Delete Submissions
  - Search & Filter Submissions
- [ ] Email Notifications:
  - Send to Admin
  - Send to User (with custom message)
  - Custom Email Templates
  - Multiple Recipients
- [ ] Form Settings:
  - Success Message
  - Redirect URL (after submission)
  - Submit Button Text
  - Form Title & Description
- [ ] Anti-Spam:
  - Honeypot Field (hidden)
  - reCAPTCHA v2 Integration (optional)
  - hCaptcha Integration (optional)
  - Rate Limiting (submissions per IP)
- [ ] Conditional Logic (optional):
  - Show/Hide fields based on other fields
  - Required if...
- [ ] Multi-Step Forms (optional):
  - Step-by-Step wizard
  - Progress Indicator
  - Save & Continue Later

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
**Status:** Geplant für v1.2.0 Release

#### Features (~8 Features)

- [ ] Schema.org Markup (Advanced):
  - Article Schema (automatic)
  - Blog Schema
  - Organization Schema
  - BreadcrumbList Schema
  - FAQ Schema (manual)
  - How-To Schema (manual)
  - Product Schema (manual)
- [ ] Open Graph & Twitter Cards (Enhanced):
  - Custom OG Image per Post/Page
  - Custom Twitter Card Type
  - Preview Generator (see how it looks on social media)
- [ ] AMP Support (Accelerated Mobile Pages):
  - Auto-Generate AMP Pages
  - AMP Validator
  - AMP Analytics Support
- [ ] Advanced Sitemap:
  - Sitemap Priority per Post/Page
  - Image Sitemap
  - Video Sitemap
  - News Sitemap (for Google News)
- [ ] Redirect Management (Enhanced):
  - Import Redirects (CSV)
  - Redirect Chains Detection
  - Redirect Performance Tracking
- [ ] Broken Link Checker:
  - Scan all Posts/Pages for broken links
  - External Link Checking
  - Internal Link Checking
  - Email Alerts (when broken link found)
  - Fix Suggestions
- [ ] Canonical URL Management:
  - Auto-Canonical (default)
  - Custom Canonical per Post/Page
  - Cross-Domain Canonical
- [ ] SEO Analysis (per Post/Page):
  - Keyword Density
  - Readability Score
  - Meta Length Check (Title, Description)
  - Image Alt Text Check
  - Internal/External Link Count

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

**Plugin Directory:** [plugins.pinecms.org](https://plugins.pinecms.org) (geplant für v2.0)

---

## 📊 Plugin Summary

| Plugin | Features | Requires | Status |
|--------|----------|----------|--------|
| **Newsletter** | ~10 | v1.1.0+ | Geplant |
| **Webhooks** | ~11 | v1.1.0+ | Geplant |
| **Custom Fields Pro** | ~8 | v1.0.0+ | Geplant |
| **Multi-Language** | ~10 | v1.1.0+ | Geplant |
| **Workflow** | ~8 | v1.1.0+ | Geplant |
| **Two-Factor Auth** | ~5 | v1.1.0+ | Geplant |
| **Form Builder** | ~15 | v1.1.0+ | Geplant |
| **SEO Pro** | ~8 | v1.2.0+ | Geplant |
| **Total** | **~75** | - | - |

---

**Last Updated:** 2025-01-26
**Maintained By:** PineCMS Team
**License:** MIT - 100% Open Source & Kostenlos
