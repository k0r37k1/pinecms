# 🔌 PineCMS - Official Plugins

> **Last Updated:** 2025-11-06
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

## 📦 Official Plugins (3 Plugins)

### 1. Newsletter Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.1.0)

#### Features (~6 Features)

- **Newsletter Editor** - TipTap WYSIWYG, separate Content-Type
- **Subscriber Management:**
    - Import/Export Subscribers (CSV)
    - Subscriber Groups/Tags
    - Subscriber Status (Active, Unsubscribed)
- **Subscriber Sign-up:**
    - Frontend Widget/Shortcode
    - Customizable Form Fields
    - Success Message
- **Email Campaign Scheduling:**
    - Send Now
    - Schedule for Later
- **Newsletter Templates:**
    - Template Selection (3 responsive templates)
    - HTML/Text Preview
- **Privacy-Friendly Tracking** (optional):
    - Open Rate Tracking (disable by default)
    - Click Tracking (disable by default)
    - Privacy Mode (no tracking, GDPR)
- **Double Opt-In** (GDPR):
    - Email Verification
    - Confirmation Template
    - Opt-In Timestamp
- **Subscriber Segmentation:**
    - Filter by Tags/Categories

**Removed (YAGNI):**

- ❌ A/B Testing → v2.0
- ❌ Recurring Newsletters → v2.0
- ❌ Bounce & Complaint Handling → Enterprise Feature, v2.0 (requires SMTP webhooks)
- ❌ Subscriber Status "Bounced" → Manual management only in v1.0

**Infrastructure:**

- **SMTP Required:** External SMTP service (Mailgun, SendGrid, Amazon SES)
- **Queue System:** Laravel Queues for background sending
- **Rate Limiting:** Configurable (e.g., 100 emails/hour), retry logic
- **Scalability:** < 1,000 subscribers (shared hosting), 1,000+ (dedicated SMTP)
- **Compliance:** GDPR (double opt-in), CAN-SPAM (footer address)

#### Use Cases

- 📧 Blog-Newsletter (neue Posts automatisch versenden)
- 📰 Wöchentlicher/Monatlicher Newsletter
- 🎯 Marketing Campaigns
- 📢 Ankündigungen & Updates

#### Why Plugin?

Nicht jeder Blogger/Website-Betreiber braucht Newsletter-Funktionalität. Externe SMTP-Services (Mailgun, SendGrid, Amazon SES) sind optional nötig für größere Listen.

---

### 2. Multi-Language Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.1.0)

#### Features (~8 Features)

- **Multi-Language Support:**
    - Unlimited Languages
    - Pre-configured Languages (DE, EN, FR, ES, IT, PT, AR, HE, etc.)
    - Custom Languages
- **Content Translation UI:**
    - Side-by-Side Editor (Original + Translation)
    - Copy from Original Button
    - Translation Progress Indicator
    - Missing Translations Warning
- **Translatable Fields:**
    - Post/Page Title & Content
    - Categories & Tags
    - Site Settings (Name, Tagline)
    - SEO Meta (Title, Description)
- **Locale Switcher** (Frontend):
    - Language Dropdown
    - Flag Icons
    - Language-Specific URLs
    - Auto-Detect Browser Language (optional, cookie-based)
- **Language-Specific URLs:**
    - URL Prefix (/en/, /de/)
    - Subdirectory (/en/, /de/)
- **Fallback Language:**
    - Default Language
    - Fallback if translation missing
- **SEO for Multi-Language:**
    - hreflang Tags (automatic)
    - Language-Specific Sitemap/RSS
- **RTL Support:**
    - Right-to-Left layout (Arabic, Hebrew)
    - Auto-detect RTL languages
    - Theme RTL CSS support

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

### 3. SEO+ Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.2.0
**Status:** 📋 Geplant (Planned for v1.2.0)

#### Features (~3 Features)

- **Schema.org Markup:**
    - Article Schema (automatic)
    - Blog Schema (automatic)
    - Organization Schema (configurable)
- **Open Graph & Twitter Cards** (Enhanced):
    - Custom OG Image per Post/Page
    - Custom Twitter Card Type
    - Social Media Preview Generator
- **Advanced Sitemap:**
    - Sitemap Priority per Post/Page
    - Image Sitemap
    - Video Sitemap
    - News Sitemap (Google News)
- **Broken Link Checker:**
    - Scan Posts/Pages for broken links
    - External & Internal Links
    - Email Alerts
    - Broken Links Report

**Removed (YAGNI):**

- ❌ FAQ/How-To/Product Schema → v2.0 (too specific)
- ❌ BreadcrumbList Schema → Theme-specific, not plugin
- ❌ SEO Analysis (Keyword Density, Readability, etc.) → v2.0 (over-engineered)
- ❌ Fix Suggestions (Broken Links) → Manual fixing only

#### Use Cases

- 🔍 SEO-optimierte Blogs
- 📰 News-Websites (Google News)
- 📊 Content Marketing
- 🏢 Corporate Websites (hohe SEO-Anforderungen)

#### Why Plugin?

Core hat SEO Basics (Slugs, Meta, Sitemap, RSS). SEO+ ist für Profis und Agenturen die maximale SEO-Performance wollen.

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
- Plugin wird registriert und Events werden registriert (Laravel Events)

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

- 📁 **Custom Fields Pro** (Repeater, Gallery, Relationships, JSON, Color Picker, File Upload, Conditional Logic)
- 📍 Address & Location Plugin (Address Fields, Google Maps, Geocoding)
- 👍 Voting System Plugin (Upvote/Downvote for Posts & Comments, Reddit/HackerNews-style)
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

| Plugin             | Features | Requires | Status  |
| ------------------ | -------- | -------- | ------- |
| **Newsletter**     | ~6       | v1.1.0+  | Geplant |
| **Multi-Language** | ~8       | v1.1.0+  | Geplant |
| **SEO+**           | ~3       | v1.1.0+  | Geplant |
| **Total**          | **~17**  | -        | -       |

**Software Engineering Improvements (2025-11-07):**

- **Removed 5 Complete Plugins (YAGNI):**
    - ❌ **Custom Fields Pro** (~7 features) → **Community Plugin** (not essential for 80% of users)
    - ❌ Webhooks (~11 features) → Over-engineered, v2.0
    - ❌ Two-Factor Auth (~5 features) → Core security sufficient, v2.0
    - ❌ Form Builder (~12 features) → Too complex, v2.0
    - ❌ Workflow (~6 features) → **Moved to Core v1.1.0** (essential for teams)
- **Plugin Feature Cleanup (YAGNI/KISS):**
    - Newsletter: Bounce Handling → v2.0, Added Signup Widget
    - Multi-Language: Added RTL Support (critical)
    - SEO+: 7 Schema → 3 Schema, SEO Analysis → v2.0
- Fixed 1 DRY violation (merged Rich Text + WYSIWYG)
- Eliminated deprecated features (AMP Support)
- **Result:** 8 Plugins → 3 Official Plugins, ~60 → ~17 Features (72% reduction)

---

**Last Updated:** 2025-11-06
**Maintained By:** PineCMS Team
**License:** MIT - 100% Open Source & Kostenlos
