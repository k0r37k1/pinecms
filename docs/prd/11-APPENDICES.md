# 11 - Appendices & References

**Version:** 1.0.0
**Last Updated:** 2025-11-08
**Status:** 📋 Planned
**Owner:** PineCMS Team

---

## 1. Overview

This document provides supplementary information referenced throughout the PineCMS PRD, including glossary, acronyms, assumptions, references, and open questions.

---

## 2. Glossary

### A

**Alpine.js**

- Lightweight JavaScript framework for reactive UI components.
- Used in PineCMS public frontend for interactive elements (dropdowns, modals, tabs).
- **Version:** Alpine.js 3

**Auto-Save**

- Automatic saving of post/page content at regular intervals (default: 30 seconds, debounced).
- Prevents data loss during content creation.

---

### B

**Blade Templates**

- Laravel's templating engine for PHP.
- Used in PineCMS public frontend for server-side rendering.

**Breadcrumbs**

- Navigational aid showing current page location in site hierarchy.
- **Note:** Theme-specific in PineCMS (not in core).

---

### C

**CipherSweet**

- Field-level encryption library for sensitive data (email, secrets).
- Provides searchable encryption (blind indexes).
- Used in PineCMS for GDPR compliance.

**CommonMark**

- Markdown specification and parser.
- Used in PineCMS for rendering Markdown content to HTML.

**Composer**

- PHP dependency manager.
- **Note:** NOT required on shared hosting for PineCMS (web installer only).

**Content Security Policy (CSP)**

- HTTP header to prevent XSS attacks by restricting resource loading.
- Example: `script-src 'self' 'nonce-{random}'`

**CRUD**

- Create, Read, Update, Delete (basic database operations).

**CSRF (Cross-Site Request Forgery)**

- Attack forcing user to execute unwanted actions.
- Prevented in PineCMS via Laravel's `@csrf` token.

---

### D

**DST (Daylight Saving Time)**

- Seasonal time change.
- PineCMS stores all dates in UTC to avoid DST issues.

---

### E

**Eloquent ORM**

- Laravel's object-relational mapper for database queries.
- Prevents SQL injection via prepared statements.

**Event-Driven Architecture**

- Design pattern where components communicate via events.
- PineCMS uses Laravel Events (NOT WordPress-style hooks).

**EXIF Data**

- Metadata embedded in images (camera model, GPS coordinates, timestamp).
- Stripped by PineCMS on upload for privacy.

---

### F

**Flat-File Storage**

- Content stored as files (Markdown) instead of database.
- PineCMS uses hybrid: SQLite (metadata) + Markdown (content).

**Focal Point**

- User-defined center point for image cropping.
- Ensures important parts of image remain visible in thumbnails.

**Front Matter (YAML)**

- Metadata at top of Markdown files.
- Example:
    ```yaml
    ---
    title: 'My Post'
    slug: 'my-post'
    status: 'published'
    ---
    ```

---

### G

**GDPR (General Data Protection Regulation)**

- EU privacy law requiring data protection and user consent.
- PineCMS compliance: field-level encryption, cookie consent, no default analytics.

**Git**

- Version control system.
- **Note:** Used by PineCMS developers ONLY, not end-users.

**Gravatar**

- Globally recognized avatar service (avatar tied to email).
- Used in PineCMS comment system.

---

### H

**Hierarchical Categories**

- Categories with parent-child relationships (max 2 levels in PineCMS).
- Example: "Technology" → "Web Development"

**HSTS (HTTP Strict Transport Security)**

- Security header forcing HTTPS connections.
- Example: `Strict-Transport-Security: max-age=31536000`

---

### I

**Inertia.js**

- Modern monolith framework connecting Laravel backend to Vue frontend.
- Used in PineCMS admin panel for SPA-like experience without API.
- **Version:** Inertia.js 2.x

---

### L

**Lazy Loading**

- Deferring image loading until visible in viewport.
- Improves page load performance.
- Example: `<img loading="lazy" src="...">`

**Lighthouse**

- Google Chrome tool for auditing performance, accessibility, SEO.
- PineCMS target: > 90/100 score.

**Lucide Icons**

- Open-source icon set (fork of Feather Icons).
- Used in PineCMS public frontend.

---

### M

**Markdown**

- Lightweight markup language for formatting text.
- PineCMS content stored as Markdown files with YAML front matter.

**MIME Type**

- File format identifier (e.g., `image/jpeg`, `application/pdf`).
- Validated in PineCMS upload security (5-layer protection).

---

### N

**N+1 Query Problem**

- Performance issue where 1 query becomes N+1 queries in loop.
- Prevented in PineCMS via eager loading (`with()` method).

**Nesting (Comments)**

- Replies to comments (threaded conversations).
- PineCMS allows max depth of 3 levels.

---

### O

**OpenGraph**

- Metadata protocol for social media sharing (Facebook, LinkedIn).
- Example: `<meta property="og:title" content="Post Title">`

**OWASP (Open Web Application Security Project)**

- Nonprofit focused on web security.
- **OWASP Top 10:** List of critical web security risks.

---

### P

**PHPStan**

- Static analysis tool for PHP (finds bugs without running code).
- PineCMS uses Level 8 (strictest).

**Pinia**

- Vue state management library (successor to Vuex).
- Used in PineCMS admin panel for global state.

**PrimeVue**

- Vue 3 component library (buttons, inputs, tables, etc.).
- Used in PineCMS admin panel.

**PSR (PHP Standard Recommendation)**

- PHP coding standards (PSR-1, PSR-12, etc.).
- PineCMS follows PSR-1, PSR-12 via Laravel Pint.

---

### R

**RBAC (Role-Based Access Control)**

- Permission system based on user roles.
- PineCMS v1.0.0: 2 roles (Administrator, Author).
- PineCMS v1.1.0: 4 roles (Administrator, Author, User, Guest).

**Revision**

- Saved version of post/page content (snapshot).
- PineCMS stores revisions as flat-files with auto-cleanup.

**RTL (Right-to-Left)**

- Text direction for languages like Arabic, Hebrew.
- Supported in PineCMS Multi-Language Plugin.

---

### S

**Sanctum**

- Laravel's token-based authentication system for APIs.
- Used in PineCMS API authentication.

**Schema.org**

- Structured data vocabulary for search engines.
- Example: `<script type="application/ld+json">{ "@type": "Article" }</script>`

**SEO (Search Engine Optimization)**

- Improving site visibility in search engines.
- PineCMS includes sitemap, meta tags, slugs, RSS.

**Shiki**

- Syntax highlighter using VS Code themes.
- Server-side rendering (no client-side JS).
- Used in PineCMS for code blocks.

**Slug**

- URL-friendly identifier (e.g., "my-post" for "My Post").
- Auto-generated in PineCMS via Eloquent Sluggable.

**SQLite**

- Serverless SQL database (single file).
- Used in PineCMS for relational data (users, posts metadata).

**SRI (Subresource Integrity)**

- Security feature verifying CDN files not tampered.
- Example: `<script src="..." integrity="sha384-..."></script>`

**SSRF (Server-Side Request Forgery)**

- Attack tricking server into making unintended requests.
- Prevented in PineCMS by validating URLs, blocking private IPs.

---

### T

**TailwindCSS**

- Utility-first CSS framework.
- Used throughout PineCMS (admin panel & public frontend).
- **Version:** TailwindCSS 4

**TipTap**

- Headless WYSIWYG editor based on ProseMirror.
- Used in PineCMS for post/page content editing.
- **Version:** TipTap Vue 3

**TNTSearch**

- Full-text search engine for Laravel (no external dependencies).
- Used in PineCMS for searching posts/pages/tags.

**TOTP (Time-based One-Time Password)**

- 2FA authentication method (Google Authenticator, Authy).
- Optional in PineCMS user authentication.

**Twitter Cards**

- Metadata for Twitter link previews.
- Example: `<meta name="twitter:card" content="summary_large_image">`

---

### U

**UTC (Coordinated Universal Time)**

- Time standard without timezone/DST.
- PineCMS stores all dates in UTC, displays in user's timezone.

**UUID (Universally Unique Identifier)**

- 128-bit unique identifier (e.g., `550e8400-e29b-41d4-a716-446655440000`).
- Used in PineCMS for post IDs, file references.

---

### V

**Vite**

- Modern frontend build tool (fast HMR, code splitting).
- Used in PineCMS for asset compilation.

**Vue.js**

- Progressive JavaScript framework for UIs.
- Used in PineCMS admin panel (Vue 3.5 Composition API).

---

### W

**WAL (Write-Ahead Logging)**

- SQLite journaling mode (better concurrency).
- Enabled in PineCMS for performance.

**WCAG (Web Content Accessibility Guidelines)**

- Accessibility standards for web content.
- PineCMS complies with WCAG 2.1 AA (4.5:1 contrast ratio, keyboard navigation).

**WebP**

- Modern image format (smaller file size than JPEG/PNG).
- PineCMS auto-converts images to WebP (80% quality).

**WYSIWYG (What You See Is What You Get)**

- Editor where content appears as it will when published.
- TipTap provides WYSIWYG editing in PineCMS.

---

### X

**XSS (Cross-Site Scripting)**

- Attack injecting malicious scripts into pages.
- Prevented in PineCMS via Blade escaping (`{{ }}`), Vue sanitization, CSP.

---

### Y

**YAGNI (You Aren't Gonna Need It)**

- Principle of not implementing features until actually needed.
- Applied in PineCMS to remove social sharing, upvote/downvote, breadcrumbs from core.

**YAML (YAML Ain't Markup Language)**

- Human-readable data serialization format.
- Used in PineCMS Markdown front matter.

---

## 3. Acronyms

| Acronym     | Full Form                             | Description                                           |
| ----------- | ------------------------------------- | ----------------------------------------------------- |
| **AA**      | Level AA                              | WCAG accessibility conformance level (4.5:1 contrast) |
| **API**     | Application Programming Interface     | Interface for software interaction                    |
| **CMS**     | Content Management System             | Software for managing digital content                 |
| **CORS**    | Cross-Origin Resource Sharing         | Security feature for cross-domain requests            |
| **CRUD**    | Create, Read, Update, Delete          | Basic database operations                             |
| **CSP**     | Content Security Policy               | Security header to prevent XSS                        |
| **CSRF**    | Cross-Site Request Forgery            | Attack forcing unwanted actions                       |
| **CSS**     | Cascading Style Sheets                | Styling language for web pages                        |
| **CSV**     | Comma-Separated Values                | File format for tabular data                          |
| **CVE**     | Common Vulnerabilities and Exposures  | Security vulnerability database                       |
| **DNS**     | Domain Name System                    | Translates domain names to IPs                        |
| **DST**     | Daylight Saving Time                  | Seasonal time change                                  |
| **EXIF**    | Exchangeable Image File Format        | Image metadata (camera, GPS, etc.)                    |
| **GDPR**    | General Data Protection Regulation    | EU privacy law                                        |
| **GIF**     | Graphics Interchange Format           | Image format (animated)                               |
| **HMR**     | Hot Module Replacement                | Vite feature for instant updates                      |
| **HSTS**    | HTTP Strict Transport Security        | Security header forcing HTTPS                         |
| **HTML**    | HyperText Markup Language             | Web page structure language                           |
| **HTTP**    | HyperText Transfer Protocol           | Web communication protocol                            |
| **HTTPS**   | HTTP Secure                           | Encrypted HTTP                                        |
| **IP**      | Internet Protocol                     | Network address                                       |
| **JPEG**    | Joint Photographic Experts Group      | Image format (lossy)                                  |
| **JS**      | JavaScript                            | Programming language for web                          |
| **JSON**    | JavaScript Object Notation            | Data interchange format                               |
| **JWT**     | JSON Web Token                        | Token-based authentication                            |
| **MIME**    | Multipurpose Internet Mail Extensions | File type identifier                                  |
| **MIT**     | Massachusetts Institute of Technology | Open-source license                                   |
| **MVP**     | Minimum Viable Product                | First release with core features                      |
| **OG**      | Open Graph                            | Metadata protocol for social sharing                  |
| **ORM**     | Object-Relational Mapping             | Database abstraction layer                            |
| **OWASP**   | Open Web Application Security Project | Web security organization                             |
| **PDF**     | Portable Document Format              | Document format                                       |
| **PHP**     | PHP: Hypertext Preprocessor           | Server-side scripting language                        |
| **PNG**     | Portable Network Graphics             | Image format (lossless)                               |
| **PRD**     | Product Requirements Document         | Detailed feature specifications                       |
| **PSR**     | PHP Standard Recommendation           | PHP coding standards                                  |
| **RBAC**    | Role-Based Access Control             | Permission system by role                             |
| **REST**    | Representational State Transfer       | API architectural style                               |
| **RSS**     | Really Simple Syndication             | Web feed format                                       |
| **RTL**     | Right-to-Left                         | Text direction (Arabic, Hebrew)                       |
| **SaaS**    | Software as a Service                 | Cloud-based software                                  |
| **SEO**     | Search Engine Optimization            | Improving search visibility                           |
| **SMTP**    | Simple Mail Transfer Protocol         | Email sending protocol                                |
| **SPA**     | Single-Page Application               | Web app loading single HTML page                      |
| **SQL**     | Structured Query Language             | Database query language                               |
| **SRI**     | Subresource Integrity                 | Security for CDN files                                |
| **SSRF**    | Server-Side Request Forgery           | Attack via server requests                            |
| **SVG**     | Scalable Vector Graphics              | Vector image format                                   |
| **TOTP**    | Time-based One-Time Password          | 2FA authentication method                             |
| **TLS**     | Transport Layer Security              | Encryption protocol                                   |
| **UI**      | User Interface                        | Visual interface                                      |
| **URI**     | Uniform Resource Identifier           | Web resource identifier                               |
| **URL**     | Uniform Resource Locator              | Web address                                           |
| **UTC**     | Coordinated Universal Time            | Time standard (no timezone)                           |
| **UUID**    | Universally Unique Identifier         | Unique 128-bit identifier                             |
| **UX**      | User Experience                       | User interaction design                               |
| **WAL**     | Write-Ahead Logging                   | SQLite journaling mode                                |
| **WCAG**    | Web Content Accessibility Guidelines  | Accessibility standards                               |
| **WebP**    | Web Picture                           | Modern image format                                   |
| **WYSIWYG** | What You See Is What You Get          | Visual editor                                         |
| **XHR**     | XMLHttpRequest                        | AJAX request method                                   |
| **XML**     | Extensible Markup Language            | Markup language for data                              |
| **XSS**     | Cross-Site Scripting                  | Attack injecting malicious scripts                    |
| **YAML**    | YAML Ain't Markup Language            | Data serialization format                             |
| **ZIP**     | ZIP Archive                           | Compressed file format                                |

---

## 4. Assumptions

### 4.1 Technical Assumptions

**Hosting Environment:**

- ✅ Shared hosting providers support PHP 8.3+ (most do as of 2025)
- ✅ SQLite is widely available on shared hosting
- ✅ Users have FTP/SFTP access for uploads
- ✅ Users have access to web browser (no command-line required)

**Performance:**

- ✅ Shared hosting can handle < 1 second page loads with proper optimization
- ✅ SQLite performs well for up to 10,000 posts
- ✅ Flat-file storage is acceptable for users (portable, Git-compatible)

**Security:**

- ✅ TLS 1.3 is standard by 2025
- ✅ Laravel 12 security features remain stable
- ✅ CipherSweet library continues maintenance

---

### 4.2 User Assumptions

**Technical Skill:**

- ✅ Users can upload ZIP files via FTP
- ✅ Users can access web URLs in browser
- ✅ Users can follow step-by-step installation guide
- ✅ Users do NOT need command-line experience

**Privacy Awareness:**

- ✅ Target users care about privacy (GDPR compliance)
- ✅ Users prefer NO default analytics
- ✅ Users value field-level encryption

**Content Migration:**

- ✅ Users migrating from WordPress/Ghost have XML/JSON exports
- ✅ Import/Export is critical for adoption

---

### 4.3 Market Assumptions

**Niche Adoption:**

- ✅ 500-1,000 users achievable in Year 1 (privacy-focused niche)
- ✅ Community will contribute plugins/themes
- ✅ Donations can support 1-2 part-time maintainers by Year 3

**Long-Term Viability:**

- ✅ Shared hosting remains relevant for 5-10 years
- ✅ Laravel 12 LTS receives security updates for 5+ years
- ✅ Flat-file storage remains desirable (simplicity, portability)

**Competition:**

- ✅ PineCMS is NOT competing with WordPress (different audience)
- ✅ Privacy-first positioning fills gap in market

---

### 4.4 Development Assumptions

**Timeline:**

- ✅ 15-18 weeks is realistic for v1.0.0-v1.2.0
- ✅ Plugin development can happen in parallel (weeks 11-18)

**Team:**

- ✅ Core team can maintain velocity for 18 weeks
- ✅ Community contributors join after v1.0.0 release

**Technology:**

- ✅ Laravel 12 remains stable (no breaking changes mid-development)
- ✅ Vue 3.5, Inertia.js 2.x, TailwindCSS 4 remain stable

---

## 5. References

### 5.1 Standards & Specifications

**Security:**

- OWASP Top 10 2024: https://owasp.org/www-project-top-ten/
- CWE Top 25: https://cwe.mitre.org/top25/
- GDPR Official Text: https://gdpr-info.eu/

**Accessibility:**

- WCAG 2.1 Guidelines: https://www.w3.org/WAI/WCAG21/quickref/
- Section 508 Standards: https://www.section508.gov/
- EN 301 549: https://www.etsi.org/deliver/etsi_en/301500_301599/301549/

**Web Standards:**

- CommonMark Spec: https://spec.commonmark.org/
- OpenGraph Protocol: https://ogp.me/
- Schema.org: https://schema.org/
- Twitter Cards: https://developer.twitter.com/en/docs/twitter-for-websites/cards/

---

### 5.2 Framework Documentation

**Laravel:**

- Laravel 12 Docs: https://laravel.com/docs/12.x
- Laravel Security: https://laravel.com/docs/12.x/security
- Eloquent ORM: https://laravel.com/docs/12.x/eloquent

**Vue.js:**

- Vue 3 Docs: https://vuejs.org/guide/
- Vue Composition API: https://vuejs.org/guide/extras/composition-api-faq.html
- Pinia: https://pinia.vuejs.org/

**Inertia.js:**

- Inertia.js Docs: https://inertiajs.com/
- Inertia.js Vue Adapter: https://inertiajs.com/client-side-setup

**TailwindCSS:**

- TailwindCSS v4 Docs: https://tailwindcss.com/docs
- TailwindCSS Colors: https://tailwindcss.com/docs/customizing-colors

---

### 5.3 Libraries & Tools

**Editor:**

- TipTap: https://tiptap.dev/
- Shiki: https://github.com/shikijs/shiki

**Security:**

- CipherSweet: https://github.com/paragonie/ciphersweet
- Laravel Sanctum: https://laravel.com/docs/12.x/sanctum

**Search:**

- TNTSearch: https://github.com/teamtnt/tntsearch

**Icons:**

- Lucide Icons: https://lucide.dev/
- PrimeIcons: https://primevue.org/icons/

**UI Components:**

- PrimeVue: https://primevue.org/

---

### 5.4 Competitive Analysis

**Competitors Analyzed:**

- WordPress: https://wordpress.org/
- October CMS: https://octobercms.com/
- Winter CMS: https://wintercms.com/
- Statamic: https://statamic.com/
- Ghost: https://ghost.org/
- Kirby: https://getkirby.com/
- Grav: https://getgrav.org/

---

### 5.5 Design Inspiration

**Color Palettes:**

- Forest Green: https://www.figma.com/colors/forest-green/

**Dashboards:**

- Dribbble Dashboards: https://dribbble.com/search/dashboard
- Figma Dashboard Templates: https://www.figma.com/templates/dashboard-designs/

**Blog Examples:**

- HackTheBox Blog: https://www.hackthebox.com/blog
- Anomal Blog: https://www.anomal.xyz/blog
- Medium: https://medium.com/

---

## 6. Open Questions

### 6.1 Technical Decisions

**Q1: MySQL Support?**

- **Question:** Should PineCMS support MySQL in addition to SQLite?
- **Current Decision:** SQLite only (v1.0-1.2)
- **Rationale:** Simplicity, shared hosting compatibility
- **Future:** MySQL support via plugin (v2.0+)?

**Q2: API/Headless Mode?**

- **Question:** Should PineCMS offer REST/GraphQL API for headless CMS usage?
- **Current Decision:** No API in v1.0-1.2 (focus on core CMS)
- **Future:** REST API in v2.0+, GraphQL as premium plugin?

**Q3: Multi-Site Support?**

- **Question:** Should PineCMS support multi-site networks (like WordPress Multisite)?
- **Current Decision:** Single site only (v1.0-1.2)
- **Future:** Network plugin in v2.0+?

---

### 6.2 Business Model

**Q1: Optimal Donation/Sponsorship Model?**

- **Question:** What's the best way to fund long-term maintenance?
- **Options:**
    - GitHub Sponsors (recurring donations)
    - Open Collective (transparent funding)
    - Managed Hosting Service ($10-20/month)
    - Premium Themes/Plugins (one-time purchase)
- **Current Decision:** GitHub Sponsors + Open Collective (Year 1-2)

**Q2: Part-Time vs. Volunteer Maintenance?**

- **Question:** Should maintainers be paid part-time or remain volunteers?
- **Current Plan:** Volunteer-driven (Year 1-2), part-time funding via donations (Year 3+)

---

### 6.3 Feature Prioritization

**Q1: Should Analytics be an Official Plugin?**

- **Question:** Should PineCMS offer an official Analytics Plugin (Matomo, Plausible)?
- **Current Decision:** NO - Users integrate their own via plugins/themes/custom code
- **Rationale:** Privacy-first philosophy, no default tracking

**Q2: E-Commerce Support?**

- **Question:** Should PineCMS support e-commerce (Stripe, PayPal)?
- **Current Decision:** NO in v1.0-1.2 (community plugin in v2.0+)
- **Rationale:** Focus on blogging, avoid scope creep

**Q3: Forum/Community Plugin?**

- **Question:** Should PineCMS offer forum/community features?
- **Current Decision:** NO in v1.0-1.2 (community plugin in v2.0+)
- **Rationale:** Comments system sufficient for blogging

---

## 7. Decision Log

### 7.1 Major Decisions

**Decision 1: Web Installer Only (No Composer Requirement)**

- **Date:** 2025-11-06
- **Decision:** Simplify deployment to web-based installer only (no dual-deployment)
- **Rationale:** Easier for non-technical users, aligns with shared hosting target
- **Impact:** Removed Composer installation path from all documentation
- **Status:** ✅ Approved

**Decision 2: Matomo Analytics Completely Removed**

- **Date:** 2025-11-07
- **Decision:** Remove Matomo Analytics from core and official plugins
- **Rationale:** Privacy-first philosophy, no default analytics/tracking
- **Impact:** Users integrate their own analytics via plugins/themes/custom code
- **Status:** ✅ Approved

**Decision 3: Custom Fields Pro → Community Plugin**

- **Date:** 2025-11-08
- **Decision:** Remove Custom Fields Pro from official plugins (make it community plugin)
- **Rationale:** Focus on essential plugins, reduce maintenance burden
- **Impact:** Official plugins reduced from 4 to 3
- **Status:** ✅ Approved

**Decision 4: SEO+ Plugin in v1.1.0**

- **Date:** 2025-11-08
- **Decision:** SEO+ Plugin (renamed from "SEO Pro") in v1.1.0
- **Rationale:** SEO is essential for blogging, should be available earlier
- **Impact:** v1.1.0 includes 3 official plugins (Newsletter, Multi-Language, SEO+)
- **Status:** ✅ Approved

**Decision 5: Plugin System in v1.1.0 (Not v1.0.0)**

- **Date:** 2025-11-06
- **Decision:** Plugin system as core feature in v1.1.0 (Week 13)
- **Rationale:** Focus v1.0.0 on core blogging features, add extensibility in v1.1.0
- **Impact:** Official plugins available starting v1.1.0
- **Status:** ✅ Approved

---

### 7.2 Architectural Decisions

**ADR-003: Web Installer Only (No Composer Requirement)**

- **Decision:** Use web-based installer exclusively
- **Alternatives:** Dual-deployment (web installer + Composer)
- **Chosen:** Web installer only
- **Rationale:** Simplicity, accessibility for non-technical users

**ADR-005: No Auto-Update System (v1.0-1.2)**

- **Decision:** Manual updates via web installer (download ZIP, upload, run migrations)
- **Alternatives:** Auto-update via GitHub API
- **Chosen:** Manual updates only
- **Rationale:** Security concerns, user control, simplicity

**ADR-008: No Default Analytics Tracking**

- **Decision:** NO analytics by default, users integrate their own
- **Alternatives:** Bundled Matomo, optional analytics plugin
- **Chosen:** No default analytics
- **Rationale:** Privacy-first philosophy, GDPR compliance

**Full ADRs:** See **04-ARCHITECTURE.md Section 8**

---

## 8. Related Documents

### 8.1 PRD Documents (PineCMS)

**Core Vision:**

- [00-INDEX.md](./00-INDEX.md) - Master navigation and PRD overview
- [01-PRODUCT-VISION.md](./01-PRODUCT-VISION.md) - Strategic vision, positioning, value proposition

**Market & Users:**

- [02-COMPETITIVE-ANALYSIS.md](./02-COMPETITIVE-ANALYSIS.md) - 7 competitors analyzed (WordPress, Ghost, etc.)
- [03-USER-RESEARCH.md](./03-USER-RESEARCH.md) - 4 personas, 22 use cases, journey maps

**Technical Foundation:**

- [04-ARCHITECTURE.md](./04-ARCHITECTURE.md) - System design, 10 ADRs, event-driven architecture
- [07-TECHNICAL-SPECIFICATIONS.md](./07-TECHNICAL-SPECIFICATIONS.md) - Database schema, flat-file structure, APIs

**Features:**

- [05-CORE-FEATURES.md](./05-CORE-FEATURES.md) - 159 core features (v1.0-1.2), NO Matomo
- [06-PLUGIN-ECOSYSTEM.md](./06-PLUGIN-ECOSYSTEM.md) - 3 official plugins (Newsletter, Multi-Language, SEO+)

**Design & Quality:**

- [08-UX-UI-DESIGN.md](./08-UX-UI-DESIGN.md) - Forest Green palette, Shiki syntax highlighting, wireframes
- [09-QUALITY-REQUIREMENTS.md](./09-QUALITY-REQUIREMENTS.md) - OWASP, WCAG 2.1 AA, performance benchmarks

**Planning:**

- [10-RELEASE-ROADMAP.md](./10-RELEASE-ROADMAP.md) - 15-18 week timeline, v1.0.0-v1.2.0
- [11-APPENDICES.md](./11-APPENDICES.md) - This document (glossary, assumptions, references)

---

### 8.2 Planning Documents (Source)

**Original Planning (Read-Only):**

- `docs/planning/CORE_FEATURES.md` - Feature list (transformed into 05-CORE-FEATURES.md)
- `docs/planning/OFFICIAL_PLUGINS.md` - Plugin specs (transformed into 06-PLUGIN-ECOSYSTEM.md)
- `docs/planning/ROADMAP.md` - Timeline (transformed into 10-RELEASE-ROADMAP.md)

---

### 8.3 External References

**Laravel Documentation:**

- Laravel 12 Official Docs: https://laravel.com/docs/12.x
- Laracasts (Video Tutorials): https://laracasts.com/

**Security Resources:**

- OWASP Cheat Sheets: https://cheatsheetseries.owasp.org/
- Laravel Security Best Practices: https://github.com/Zabamund/laravel-security

**Accessibility Resources:**

- WCAG Quick Reference: https://www.w3.org/WAI/WCAG21/quickref/
- WebAIM (Accessibility Testing): https://webaim.org/

---

## 9. Document Metadata

### 9.1 Version History

| Date       | Version | Author       | Changes                                                                |
| ---------- | ------- | ------------ | ---------------------------------------------------------------------- |
| 2025-11-08 | 1.0     | PineCMS Team | Initial appendices (glossary, assumptions, references, open questions) |

---

### 9.2 Review Schedule

**Next Review:** 2025-12-08 (30 days)
**Review Frequency:** Monthly during active development

**Review Checklist:**

- [ ] Glossary updated with new terms
- [ ] Assumptions validated (still accurate?)
- [ ] Open questions resolved or updated
- [ ] Decision log updated with new decisions
- [ ] References checked for broken links

---

### 9.3 Document Ownership

**Owner:** PineCMS Team
**Contributors:** Core Team, Community Contributors
**License:** MIT (same as PineCMS)

---

**Last Updated:** 2025-11-08
**Document Owner:** PineCMS Team
**Next Review:** 2025-12-08

---

## 10. Changelog

**2025-11-08 (v1.0):**

- Initial appendices created
- Glossary (A-Y, 50+ terms)
- Acronyms table (60+ acronyms)
- Assumptions (technical, user, market, development)
- References (standards, frameworks, competitors, design)
- Open questions (technical, business, features)
- Decision log (5 major decisions, 3 ADRs)
- Related documents (11 PRD files, 3 planning files)

---

**End of Document**
