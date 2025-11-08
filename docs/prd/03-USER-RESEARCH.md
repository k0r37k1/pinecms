# 03 - User Research & Personas

**Version:** 1.0.0
**Last Updated:** 2025-11-07
**Status:** 📋 Planned
**Owner:** PineCMS Team

---

## 1. Executive Summary

This document presents comprehensive user research for **PineCMS**, including detailed personas, use cases, user journeys, and pain point analysis. Based on market research and privacy trends, PineCMS targets a focused niche of privacy-conscious bloggers, minimalists, small businesses, and developers building client sites.

**Key Findings:**

- 77% of Americans distrust social media platforms with personal data (Pew Research 2023)
- Privacy-focused users actively seek alternatives to WordPress and mainstream CMSs
- Minimalist bloggers value distraction-free writing over feature bloat
- Small businesses need "set it and forget it" reliability
- Modern shared hosting supports SSH/Composer but web installers remain most accessible

---

## 2. Research Methodology

### 2.1 Research Approach

**Methods Used:**

- Competitive analysis of 7 CMS platforms (WordPress, October CMS, Statamic, Ghost, Kirby, Grav, Winter CMS)
- Web research on privacy trends and user demographics (Brave Search, industry reports)
- Analysis of minimalist blogging platform communities (Reddit r/selfhosted, Indie Hackers)
- CMS user journey mapping best practices
- Product vision alignment with sustainable niche market

**Data Sources:**

- Pew Research Center (Privacy Trust Statistics 2023)
- CMS user communities (Reddit, Indie Hackers, WordPress forums)
- Industry publications (CMS Critic, Mashable, ServerAvatar)
- Competitor user reviews and feedback
- Privacy advocacy communities

### 2.2 Target Market Definition

**Market Size:**

- Privacy-focused bloggers: ~500,000 globally
- Minimalist bloggers: ~1 million globally
- **PineCMS Target:** 0.5-1% of this niche = 5,000-10,000 users (Year 5)

**Realistic Adoption Goals:**

- Year 1 (2025): 500-1,000 installations
- Year 2 (2026): 2,000-3,000 installations
- Year 5 (2030): 5,000-10,000 installations

**NOT Targeting:**

- Enterprise users (use WordPress/Drupal)
- E-commerce sites (use WooCommerce/Shopify)
- Complex web apps (use Laravel directly)
- Users needing 1,000+ features

---

## 3. User Personas

### 3.1 Primary Persona: Privacy-Conscious Blogger (40%)

**Name:** Sarah Mitchell
**Age:** 32
**Location:** Berlin, Germany
**Occupation:** Freelance Journalist & Privacy Advocate
**Technical Level:** Intermediate (comfortable with FTP, basic PHP)

#### Demographics

- **Education:** Master's in Journalism
- **Income:** €35,000-50,000/year
- **Household:** Single, rents apartment
- **Digital Habits:** Uses Signal, ProtonMail, VPN, Firefox with privacy extensions
- **Blogging Experience:** 5+ years on WordPress, frustrated with tracking

#### Goals & Motivations

**Primary Goals:**

- ✅ Publish investigative journalism without tracking readers
- ✅ GDPR compliance by default (EU-based)
- ✅ Full control over data (no third-party analytics)
- ✅ Fast, reliable platform for serious content

**Motivations:**

- Privacy is a human right, not a feature
- Distrust of big tech platforms (Google Analytics, Facebook tracking)
- Wants readers to focus on content, not be data products
- Professional credibility requires ethical publishing

#### Pain Points

**Current CMS Frustrations (WordPress):**

- ❌ Google Analytics embedded by default in themes
- ❌ Plugins track users without clear disclosure
- ❌ EXIF data not stripped from uploaded images
- ❌ Cookie banners everywhere due to tracking
- ❌ No built-in field-level encryption for sensitive content

**Technical Challenges:**

- ⚠️ Third-party privacy plugins often conflict
- ⚠️ Self-hosting Matomo requires technical knowledge
- ⚠️ SSL certificates and HTTPS setup still confusing
- ⚠️ Database backups expose unencrypted content

#### Technology Profile

**Current Setup:**

- WordPress 6.x on shared hosting (€15/month)
- Matomo self-hosted (separate server, €10/month)
- Privacy plugins: Cookie Notice, EXIF Cleaner, WP Security
- Hosting: European provider (GDPR compliance)

**Preferred Tools:**

- Markdown for writing (portable format)
- Self-hosted analytics (Matomo, privacy-focused)
- Open-source software (transparency)
- FTP backups (full data control)

#### Use Cases

**UC-1: Publish Investigation Without Tracking Readers**

- Sarah writes an exposé on government surveillance
- Needs to publish without revealing reader IP addresses or locations
- Wants analytics (aggregate only) without cookies or tracking pixels
- **PineCMS Solution:** No default analytics, optional Matomo plugin with privacy mode

**UC-2: Encrypt Sensitive Source Information**

- Sarah stores confidential source notes in custom fields
- Needs field-level encryption for names, locations, contacts
- Must be transparent about what's encrypted vs. public
- **PineCMS Solution:** CipherSweet field-level encryption for sensitive custom fields

**UC-3: GDPR-Compliant Image Uploads**

- Sarah uploads protest photos with EXIF location data
- Needs automatic EXIF stripping to protect subjects
- Wants alt text and captions for accessibility
- **PineCMS Solution:** Automatic EXIF removal on upload, image optimization, alt text UI

#### Success Metrics

**What "Success" Means for Sarah:**

- ✅ Zero cookies without consent (privacy-first)
- ✅ No external tracking scripts loaded
- ✅ Page load < 1 second (professional speed)
- ✅ Full data export available (portability)
- ✅ Transparent security practices (trust)

**KPIs:**

- Installation completion: < 10 minutes
- First post published: < 15 minutes
- Zero privacy violations reported
- Active usage: 12+ months retention

---

### 3.2 Secondary Persona: Minimalist Blogger (30%)

**Name:** Alex Chen
**Age:** 28
**Location:** Portland, Oregon, USA
**Occupation:** UX Designer & Personal Blogger
**Technical Level:** Beginner-Intermediate (knows HTML/CSS, avoids PHP)

#### Demographics

- **Education:** Bachelor's in Design
- **Income:** $55,000-70,000/year
- **Household:** Lives with partner, no kids
- **Digital Habits:** Uses Notion, Bear, iA Writer for writing
- **Blogging Experience:** 3 years, tried WordPress, Medium, Ghost

#### Goals & Motivations

**Primary Goals:**

- ✅ Distraction-free writing experience
- ✅ Beautiful, minimal design out of the box
- ✅ No plugin management or maintenance headaches
- ✅ Fast performance without optimization tweaks

**Motivations:**

- Writing is therapy, not marketing
- Hates WordPress dashboard complexity (too many menus)
- Prefers "set it and forget it" simplicity
- Values aesthetics and typography
- Wants to own content (not Medium's platform)

#### Pain Points

**Current CMS Frustrations (WordPress):**

- ❌ Dashboard overwhelms with 50+ menu items
- ❌ Needs 15+ plugins for basic features
- ❌ Plugin updates break sites monthly
- ❌ Block editor is clunky for writing
- ❌ Themes are bloated with features Alex doesn't need

**Ghost Experience (tried but left):**

- ⚠️ Required VPS knowledge (DigitalOcean too complex)
- ⚠️ $9/month Ghost Pro too expensive for hobby blog
- ⚠️ Node.js updates required maintenance
- ⚠️ Limited themes without coding

#### Technology Profile

**Current Setup:**

- WordPress on shared hosting (€8/month)
- GeneratePress theme (minimal, fast)
- Only 5 plugins installed (fighting to keep it minimal)
- Writes drafts in iA Writer, pastes into WordPress

**Preferred Tools:**

- Markdown for writing (clean, portable)
- Simple WYSIWYG editor (TipTap ideal)
- Shared hosting (no server management)
- Drag-and-drop themes (no coding)

#### Use Cases

**UC-4: Launch Personal Blog in 30 Minutes**

- Alex has an idea for a blog on minimalist living
- Needs to install CMS, choose theme, write first post, publish
- Wants to avoid spending hours on configuration
- **PineCMS Solution:** Web installer (5 min) + 3 built-in themes + TipTap editor

**UC-5: Write Without Distractions**

- Alex sits down to write a 2,000-word essay
- Needs clean editor without sidebars, widgets, SEO analyzers
- Wants Markdown support for formatting
- **PineCMS Solution:** TipTap WYSIWYG with Markdown shortcuts, fullscreen mode

**UC-6: Never Worry About Plugin Updates**

- Alex wants to write, not maintain a CMS
- Needs essential features built-in (no plugin hunting)
- Updates should be one-click and reliable
- **PineCMS Solution:** Core features built-in (v1.0: 95 features), manual update (upload ZIP)

#### Success Metrics

**What "Success" Means for Alex:**

- ✅ Dashboard simplicity (< 10 main menu items)
- ✅ Zero plugin installation required
- ✅ Write first post within 15 minutes
- ✅ No maintenance required (monthly check-ins only)
- ✅ Beautiful typography out of the box

**KPIs:**

- Installation completion: < 5 minutes
- Time to first post: < 15 minutes
- Plugin count: 0-2 (optional only)
- Active usage: 12+ months retention

---

### 3.3 Tertiary Persona: Small Business Blog (20%)

**Name:** Maria Rodriguez
**Age:** 41
**Location:** Austin, Texas, USA
**Occupation:** Marketing Manager at Local Accounting Firm (15 employees)
**Technical Level:** Non-Technical (knows Word, Excel, basic web browsing)

#### Demographics

- **Education:** MBA in Marketing
- **Income:** $65,000-80,000/year (company blog, not personal)
- **Company Size:** 15 employees, family-owned firm
- **Digital Habits:** Uses LinkedIn, Google Workspace, Canva
- **Blogging Experience:** Manages company blog (WordPress), 2 years

#### Goals & Motivations

**Primary Goals:**

- ✅ Publish weekly SEO blog posts to attract local clients
- ✅ Simple enough for non-technical staff to post
- ✅ Reliable platform (no downtime during tax season)
- ✅ Professional appearance for client trust

**Motivations:**

- Blog drives 30% of new client inquiries
- SEO is critical for local search visibility
- Budget is limited ($20-50/month max)
- Can't afford dedicated developer
- Needs staff writers to self-publish

#### Pain Points

**Current CMS Frustrations (WordPress):**

- ❌ Plugin conflicts break site before client presentations
- ❌ Staff writers accidentally delete important pages
- ❌ Security plugins send cryptic alerts Maria doesn't understand
- ❌ Site speed dropped after adding "necessary" plugins
- ❌ No clear workflow for post approval

**Technical Challenges:**

- ⚠️ WordPress security updates scare Maria (what if it breaks?)
- ⚠️ Staff overwrites each other's drafts
- ⚠️ No version control or post history
- ⚠️ Categories and tags confusing for staff

#### Technology Profile

**Current Setup:**

- WordPress on shared hosting (€25/month via agency)
- Yoast SEO, Wordfence, UpdraftPlus, Contact Form 7
- Pays agency $500/year for "maintenance" (plugin updates)
- Staff of 3 writers (non-technical)

**Preferred Tools:**

- Google Docs for draft writing
- Simple editor (like Medium)
- Clear roles (writer, editor, admin)
- Automatic backups (no manual intervention)

#### Use Cases

**UC-7: Non-Technical Staff Publishes Weekly Blog Post**

- Writer drafts post in Google Docs
- Maria reviews and approves in CMS
- Writer publishes after approval
- SEO fields (meta, slug) clearly labeled
- **PineCMS Solution:** Workflow system (v1.1.0), clear roles, TipTap editor, SEO fields

**UC-8: Recover from Accidental Deletion**

- Staff writer accidentally deletes published post
- Maria needs to restore without developer help
- Wants revision history to see what changed
- **PineCMS Solution:** Post revisions (v1.0.0), trash/restore functionality

**UC-9: Monthly Security and Backup Routine**

- Maria sets aside first Monday of month for CMS maintenance
- Needs clear checklist: backups, updates, security checks
- Wants one-click backup download
- **PineCMS Solution:** Admin dashboard checklist, backup export, manual update (clear instructions)

#### Success Metrics

**What "Success" Means for Maria:**

- ✅ Zero unplanned downtime (reliability)
- ✅ Staff can publish without Maria's help
- ✅ Clear approval workflow for compliance
- ✅ Page load < 2 seconds (client patience)
- ✅ Monthly maintenance < 1 hour

**KPIs:**

- Installation: Completed by hosting provider
- Staff onboarding: < 30 minutes per person
- Post approval workflow: < 5 minutes per post
- Active usage: 24+ months retention

---

### 3.4 Quaternary Persona: Developer Building Client Sites (10%)

**Name:** David Kim
**Age:** 35
**Location:** Toronto, Canada
**Occupation:** Freelance Web Developer
**Technical Level:** Expert (Full-stack Laravel developer)

#### Demographics

- **Education:** Computer Science degree
- **Income:** $80,000-120,000 CAD/year (freelance)
- **Household:** Married, 1 child
- **Digital Habits:** GitHub, Slack, VS Code, Laravel ecosystem
- **Blogging Experience:** Builds 10-15 client sites/year

#### Goals & Motivations

**Primary Goals:**

- ✅ Build client blog sites quickly (5-10 hours max)
- ✅ Modern codebase (Laravel 12, Vue 3.5, no legacy code)
- ✅ Easy client handoff (non-technical clients)
- ✅ Minimal maintenance after launch

**Motivations:**

- WordPress maintenance is unprofitable (plugin updates, security)
- Clients break WordPress sites constantly
- Wants clean Laravel code (PSR-12, typed properties)
- Values event-driven architecture over WordPress hooks
- Needs modern frontend (Vue, Inertia, not jQuery)

#### Pain Points

**Current CMS Frustrations (WordPress):**

- ❌ Legacy PHP code (no type safety)
- ❌ Plugin conflicts waste billable hours
- ❌ Clients install "premium" plugins that break sites
- ❌ WordPress hooks are a tangled mess
- ❌ jQuery-based admin (outdated UX)

**October CMS Experience (tried but left):**

- ⚠️ Composer installation too technical for clients
- ⚠️ Paid plugins add up ($300-500 per site)
- ⚠️ Client can't update without developer
- ⚠️ Documentation assumes developer knowledge

#### Technology Profile

**Current Setup:**

- Laravel 12 custom builds for web apps
- WordPress for client blogs (reluctantly)
- Forge for server management (Laravel hosting)
- Vite, TailwindCSS, Vue 3.5 for custom projects

**Preferred Tools:**

- Laravel ecosystem (Fortify, Sanctum, Policies)
- Composer + Git workflow
- Vue 3.5 Composition API
- TailwindCSS 4 (utility-first)
- PHPStan level 8 (type safety)

#### Use Cases

**UC-10: Build Client Blog Site in 5 Hours**

- Client needs blog for local law firm
- David installs PineCMS via web installer (shares link with client)
- Customizes theme via TailwindCSS
- Sets up 3 user roles (admin, editor, writer)
- Hands off to client with 30-minute training session
- **PineCMS Solution:** Web installer, Laravel codebase, theme system, RBAC

**UC-11: Extend Core with Custom Event Listener**

- Client needs custom workflow: Post published → Slack notification
- David writes Event Listener for `PostPublished` event
- Adds to `EventServiceProvider`, no core modification
- **PineCMS Solution:** Event-driven architecture (Laravel Events), documented core events

**UC-12: Client Self-Maintenance After Handoff**

- David trains client on admin panel
- Client adds posts, uploads images, manages users
- David gets zero support calls for 6+ months
- **PineCMS Solution:** Intuitive Vue/Inertia admin, clear UI labels, built-in help text

#### Success Metrics

**What "Success" Means for David:**

- ✅ Modern Laravel code (PSR-12, typed)
- ✅ Event-driven architecture (no hooks)
- ✅ Web installer (client can install themselves)
- ✅ Zero post-launch support calls
- ✅ 5-10 hour project completion

**KPIs:**

- Installation: < 5 minutes (web installer)
- Theme customization: 2-3 hours (TailwindCSS)
- Client training: < 30 minutes
- Post-launch support: < 1 hour/month

---

## 4. User Journey Maps

### 4.1 Journey Map: Privacy-Conscious Blogger (Sarah)

#### Phase 1: Awareness & Research (1-2 weeks)

**Trigger:** Frustrated with WordPress tracking plugins conflicting

**Actions:**

1. Searches: "privacy-focused CMS", "WordPress alternative no tracking"
2. Finds PineCMS via Reddit r/privacy, r/selfhosted
3. Reads product vision: "Privacy-first by default"
4. Checks features: Field-level encryption ✅, No default analytics ✅

**Touchpoints:**

- PineCMS.org homepage
- GitHub repository
- Reddit community discussion
- Documentation site

**Thoughts & Emotions:**

- 😐 "Another CMS claiming privacy? Let me verify..."
- 🤔 "Field-level encryption is rare, interesting..."
- 😊 "Open source MIT license, can audit code myself"

**Pain Points:**

- ⚠️ Skeptical of "privacy-first" marketing claims
- ⚠️ Needs proof (technical documentation, code audit)

---

#### Phase 2: Installation & Setup (30 minutes)

**Trigger:** Downloads PineCMS v1.0.0 from GitHub

**Actions:**

1. Uploads ZIP to shared hosting via FTP
2. Creates MySQL database (or uses SQLite)
3. Navigates to `yoursite.com/install`
4. Completes web installer:
    - Site details (name, tagline, language)
    - Admin account (username, email, password)
    - Privacy settings (EXIF stripping ✅, No analytics ✅)
5. Installer creates config, runs migrations, seeds database

**Touchpoints:**

- Installation documentation
- Web installer UI (Vue/Inertia)
- Success screen: "Your blog is ready!"

**Thoughts & Emotions:**

- 😊 "5 minutes, faster than WordPress!"
- 😍 "Privacy settings during install, not afterthought"
- 😌 "SQLite option saves me from MySQL hassle"

**Pain Points:**

- ⚠️ FTP upload might be unclear for first-time users (docs critical)
- ⚠️ Database creation still manual step (acceptable trade-off)

**Success Criteria:**

- ✅ Installation completion rate: > 95%
- ✅ Time to install: < 10 minutes
- ✅ Zero cryptic errors

---

#### Phase 3: First Content Creation (15 minutes)

**Trigger:** Clicks "Create Post" in admin dashboard

**Actions:**

1. Opens TipTap WYSIWYG editor
2. Writes post title and content (Markdown shortcuts)
3. Uploads image: EXIF data automatically stripped ✓
4. Adds alt text for accessibility
5. Sets category, tags, SEO meta (title, description)
6. Saves as draft → Reviews → Publishes

**Touchpoints:**

- Admin dashboard (Vue/Inertia)
- TipTap editor
- Media library
- SEO fields UI

**Thoughts & Emotions:**

- 😊 "Editor is clean, no clutter"
- 😍 "EXIF stripped automatically, peace of mind!"
- 😌 "Markdown shortcuts work, familiar workflow"

**Pain Points:**

- ⚠️ Needs confirmation that EXIF was removed (show notification)

**Success Criteria:**

- ✅ Time to first post: < 15 minutes
- ✅ EXIF stripping confirmed visually
- ✅ Markdown shortcuts work seamlessly

---

#### Phase 4: Long-Term Usage (3-6 months)

**Trigger:** Regular blogging routine (2-3 posts/week)

**Actions:**

1. Writes 50+ posts over 3 months
2. Installs Matomo Analytics Plugin (optional, privacy mode)
3. Uses Custom Fields for source notes (encrypted)
4. Backs up SQLite + flat-files via FTP monthly
5. Updates PineCMS manually (upload new version)

**Touchpoints:**

- Admin dashboard (daily)
- Plugin system (Matomo)
- Backup/export tools
- Update notifications

**Thoughts & Emotions:**

- 😊 "Still fast after 50 posts, impressive"
- 😍 "No tracking scripts, readers appreciate it"
- 😌 "Backups are straightforward (FTP download)"

**Pain Points:**

- ⚠️ Manual updates require FTP (acceptable for privacy trade-off)
- ⚠️ Wishes for easier backup scheduling (future feature)

**Success Criteria:**

- ✅ 12+ months active usage (retention)
- ✅ Zero privacy violations
- ✅ Performance remains < 1 second page load

---

### 4.2 Journey Map: Minimalist Blogger (Alex)

#### Phase 1: Awareness & Research (2-3 days)

**Trigger:** Frustrated with WordPress plugin bloat

**Actions:**

1. Searches: "simple CMS", "WordPress alternative minimal"
2. Reads comparison: WordPress vs. Ghost vs. PineCMS
3. Likes: "Essential features only, no bloat"
4. Checks demo site (live preview)

**Touchpoints:**

- PineCMS.org homepage
- Comparison article (blog post)
- Demo site (demo.pinecms.org)

**Thoughts & Emotions:**

- 😐 "Another 'minimal' CMS? Let me check demo..."
- 😊 "Admin panel is clean, no clutter!"
- 😍 "Built-in features, no plugins needed!"

**Pain Points:**

- ⚠️ Needs visual proof (screenshots, demo site)

---

#### Phase 2: Installation & Setup (10 minutes)

**Trigger:** Downloads PineCMS after testing demo

**Actions:**

1. Uploads ZIP via hosting control panel (file manager)
2. Runs web installer at `yoursite.com/install`
3. Chooses "Minimalist" theme (clean typography)
4. Completes setup in 5 minutes

**Touchpoints:**

- File manager (hosting control panel)
- Web installer
- Theme selection screen

**Thoughts & Emotions:**

- 😊 "Easier than WordPress, no database hassle"
- 😍 "Theme looks beautiful out of the box!"

**Pain Points:**

- ⚠️ None (web installer is familiar)

**Success Criteria:**

- ✅ Installation: < 5 minutes
- ✅ Zero technical jargon in installer

---

#### Phase 3: First Content Creation (10 minutes)

**Trigger:** Opens admin panel after installation

**Actions:**

1. Clicks "New Post"
2. Writes first post using TipTap (distraction-free)
3. Uploads header image (drag & drop)
4. Publishes immediately (no draft needed)

**Touchpoints:**

- Admin dashboard
- TipTap editor (fullscreen mode)
- Media library (drag & drop)

**Thoughts & Emotions:**

- 😍 "This editor is perfect, no distractions!"
- 😊 "Published in 10 minutes, new record!"

**Pain Points:**

- ⚠️ None

**Success Criteria:**

- ✅ First post: < 10 minutes
- ✅ Zero configuration needed

---

#### Phase 4: Long-Term Usage (6-12 months)

**Trigger:** Regular blogging (1-2 posts/week)

**Actions:**

1. Writes 100+ posts over 12 months
2. Never installs a plugin (core features sufficient)
3. Updates PineCMS manually every 3 months
4. Zero maintenance issues

**Touchpoints:**

- Admin dashboard (weekly)
- TipTap editor (writing)
- Update notifications

**Thoughts & Emotions:**

- 😍 "Still love this CMS, zero regrets!"
- 😊 "No plugin management, bliss!"

**Pain Points:**

- ⚠️ None

**Success Criteria:**

- ✅ 12+ months retention
- ✅ Zero plugins installed
- ✅ Writes weekly without friction

---

### 4.3 Journey Map: Small Business Blog (Maria)

#### Phase 1: Awareness & Research (1 week)

**Trigger:** WordPress security plugin sends cryptic alert

**Actions:**

1. Asks IT consultant for "simple, reliable CMS"
2. Consultant recommends PineCMS (easy client handoff)
3. Maria reads case study: Law firm blog
4. Likes: Workflow system, approval process

**Touchpoints:**

- Consultant recommendation
- Case study (PineCMS.org/case-studies)
- Feature list (workflow, roles)

**Thoughts & Emotions:**

- 😐 "Is this really easier than WordPress?"
- 😊 "Approval workflow is exactly what we need!"

**Pain Points:**

- ⚠️ Needs consultant approval (trust factor)

---

#### Phase 2: Installation & Setup (30 minutes)

**Trigger:** IT consultant installs PineCMS on company hosting

**Actions:**

1. Consultant runs web installer
2. Creates 4 user accounts (Maria: Admin, 3 Staff: Writers)
3. Configures workflow: Draft → Review → Publish
4. Trains Maria on admin panel (30 minutes)

**Touchpoints:**

- Web installer (consultant-led)
- User management UI
- Workflow configuration
- Training session (screenshare)

**Thoughts & Emotions:**

- 😊 "Setup was quick, consultant happy!"
- 😍 "User roles are clear, I understand this!"

**Pain Points:**

- ⚠️ Needs consultant for setup (acceptable for business)

**Success Criteria:**

- ✅ Installation: < 30 minutes (consultant)
- ✅ User training: < 30 minutes

---

#### Phase 3: First Content Creation (20 minutes)

**Trigger:** Staff writer creates first post

**Actions:**

1. Writer logs in → Creates draft post
2. Saves as "Pending Review" (workflow)
3. Maria receives email notification
4. Maria reviews, approves → Writer publishes

**Touchpoints:**

- Admin dashboard (writer)
- Workflow UI (pending review)
- Email notification (Maria)
- Approval button (Maria)

**Thoughts & Emotions:**

- 😊 "Workflow works perfectly!"
- 😍 "No more accidental publishes!"

**Pain Points:**

- ⚠️ None

**Success Criteria:**

- ✅ First post workflow: < 20 minutes
- ✅ Clear approval process

---

#### Phase 4: Long-Term Usage (12+ months)

**Trigger:** Routine weekly blog publishing

**Actions:**

1. Staff publishes 50+ posts/year
2. Maria performs monthly maintenance (< 1 hour)
3. Zero unplanned downtime in 12 months
4. Consultant checks in quarterly (optional)

**Touchpoints:**

- Admin dashboard (daily)
- Monthly maintenance checklist
- Quarterly consultant check-ins

**Thoughts & Emotions:**

- 😍 "This is set-it-and-forget-it perfection!"
- 😊 "Blog drives 30% new clients, working great!"

**Pain Points:**

- ⚠️ None

**Success Criteria:**

- ✅ 24+ months retention
- ✅ Zero unplanned downtime
- ✅ Monthly maintenance < 1 hour

---

### 4.4 Journey Map: Developer Building Client Sites (David)

#### Phase 1: Awareness & Research (1 day)

**Trigger:** Client asks for "simple blog, not WordPress"

**Actions:**

1. Searches: "Laravel CMS", "modern blog CMS"
2. Finds PineCMS on GitHub
3. Reads: Laravel 12, Vue 3.5, Inertia.js ✅
4. Checks: Event-driven (not hooks) ✅
5. Tests local installation (Laravel Valet)

**Touchpoints:**

- GitHub repository
- Documentation (technical specs)
- Local development setup

**Thoughts & Emotions:**

- 😊 "Laravel 12, finally modern code!"
- 😍 "Event-driven, no WordPress hooks mess!"

**Pain Points:**

- ⚠️ None (perfect fit for tech stack)

---

#### Phase 2: Installation & Setup (2 hours)

**Trigger:** Client project kickoff

**Actions:**

1. Sends client web installer link (yoursite.com/install)
2. Client completes installation (guided by David)
3. David customizes theme (TailwindCSS utility classes)
4. Sets up 3 user roles (admin, editor, writer)

**Touchpoints:**

- Web installer (client-facing)
- Theme files (Blade + TailwindCSS)
- User management UI

**Thoughts & Emotions:**

- 😊 "Web installer makes client handoff easy!"
- 😍 "TailwindCSS 4, no custom CSS needed!"

**Pain Points:**

- ⚠️ None

**Success Criteria:**

- ✅ Installation: < 5 minutes (client-led)
- ✅ Theme customization: 2-3 hours

---

#### Phase 3: Custom Development (2 hours)

**Trigger:** Client needs Slack notification on post publish

**Actions:**

1. Creates `PostPublished` event listener
2. Adds to `EventServiceProvider`
3. Tests locally with Tinker
4. Deploys to production

**Touchpoints:**

- Event documentation
- Laravel Tinker
- Git deployment

**Thoughts & Emotions:**

- 😍 "Event-driven is so clean, no core modifications!"
- 😊 "Documentation is clear, saved time!"

**Pain Points:**

- ⚠️ None

**Success Criteria:**

- ✅ Custom feature: < 2 hours
- ✅ Zero core modifications

---

#### Phase 4: Client Handoff & Maintenance (6-12 months)

**Trigger:** Project completion, client goes live

**Actions:**

1. 30-minute training session with client
2. Client publishes 100+ posts over 12 months
3. David gets zero support calls
4. Quarterly check-ins (optional)

**Touchpoints:**

- Training session (screenshare)
- Client admin dashboard
- Quarterly invoices (maintenance retainer)

**Thoughts & Emotions:**

- 😍 "Zero support calls, perfect!"
- 😊 "Client loves admin panel, easy win!"

**Pain Points:**

- ⚠️ None

**Success Criteria:**

- ✅ Zero post-launch support calls
- ✅ Client retention: 12+ months

---

## 5. Pain Point Analysis

### 5.1 Common Pain Points Across Personas

| Pain Point                  | Sarah | Alex | Maria | David | PineCMS Solution                                     |
| --------------------------- | ----- | ---- | ----- | ----- | ---------------------------------------------------- |
| **WordPress Plugin Bloat**  | ✅    | ✅   | ✅    | ✅    | Core features built-in (95 features v1.0)            |
| **Tracking by Default**     | ✅    | ❌   | ❌    | ❌    | No default analytics, optional Matomo plugin         |
| **Complex Setup**           | ⚠️    | ✅   | ✅    | ❌    | Web installer (< 10 minutes)                         |
| **Plugin Conflicts**        | ✅    | ✅   | ✅    | ✅    | Event-driven architecture, minimal plugins           |
| **Security Confusion**      | ✅    | ⚠️   | ✅    | ❌    | Built-in security (field encryption, EXIF stripping) |
| **Slow Performance**        | ⚠️    | ✅   | ✅    | ⚠️    | < 1 second page load target                          |
| **Legacy Code**             | ❌    | ❌   | ❌    | ✅    | Laravel 12, PHP 8.3+, Vue 3.5                        |
| **Non-Technical Usability** | ❌    | ⚠️   | ✅    | ❌    | Intuitive admin (Vue/Inertia), clear labels          |
| **Maintenance Burden**      | ⚠️    | ✅   | ✅    | ⚠️    | Manual updates (web installer), minimal maintenance  |
| **Vendor Lock-In**          | ✅    | ⚠️   | ❌    | ❌    | Portable flat-files, open source MIT                 |

**Legend:**

- ✅ Critical pain point for persona
- ⚠️ Moderate pain point
- ❌ Not a pain point for persona

---

### 5.2 Privacy-Specific Pain Points

**Problem:** WordPress and mainstream CMSs embed tracking by default

**Affected Personas:** Sarah (critical), Alex (moderate)

**Current Workarounds:**

- Install privacy plugins (conflicts common)
- Self-host Matomo (technical complexity)
- Disable Google Analytics (themes break)
- Cookie consent banners (poor UX)

**PineCMS Solution:**

- ✅ No default analytics/tracking
- ✅ Optional Matomo plugin (privacy mode)
- ✅ Field-level encryption (CipherSweet)
- ✅ EXIF stripping on upload
- ✅ Cookieless by default

---

### 5.3 Simplicity-Specific Pain Points

**Problem:** WordPress dashboard overwhelms with features

**Affected Personas:** Alex (critical), Maria (moderate)

**Current Workarounds:**

- Hide admin menus via plugins
- Use "Distraction-Free Writing" mode
- Train staff to ignore 90% of features
- Use external editors (Google Docs → WordPress)

**PineCMS Solution:**

- ✅ Clean admin (< 10 main menu items)
- ✅ Essential features only (YAGNI principle)
- ✅ TipTap WYSIWYG (distraction-free)
- ✅ No plugin installation required

---

### 5.4 Developer-Specific Pain Points

**Problem:** WordPress legacy code and hooks architecture

**Affected Personas:** David (critical)

**Current Workarounds:**

- Build custom Laravel apps (more profitable)
- Use October CMS (Composer + paid plugins)
- Tolerate WordPress for client familiarity
- Refactor WordPress code constantly

**PineCMS Solution:**

- ✅ Laravel 12 modern codebase
- ✅ Event-driven architecture (no hooks)
- ✅ Vue 3.5 + Inertia.js admin
- ✅ TailwindCSS 4 + Vite
- ✅ PHPStan level 8 (type safety)

---

## 6. Use Case Catalog

### 6.1 Installation & Setup

**UC-1: Complete Web Installation (All Personas)**

- **Actor:** New user (any persona)
- **Preconditions:** Hosting with PHP 8.3+, SQLite or MySQL
- **Steps:**
    1. Upload PineCMS ZIP via FTP or file manager
    2. Extract to web root or subdirectory
    3. Navigate to `yoursite.com/install`
    4. Complete web installer form:
        - Site details (name, tagline, language)
        - Admin account (username, email, password)
        - Database selection (SQLite or MySQL)
        - Privacy settings (EXIF stripping, analytics)
    5. Installer creates config, runs migrations, seeds database
    6. Redirect to admin dashboard
- **Postconditions:** Fully functional blog with default theme
- **Success Criteria:** Installation completion < 10 minutes, 95%+ success rate

---

**UC-2: SQLite Database Selection (Sarah, Alex)**

- **Actor:** Privacy-conscious or minimalist blogger
- **Preconditions:** Shared hosting supports SQLite (PHP 8.3+)
- **Steps:**
    1. During web installer, select "SQLite" option
    2. Installer creates `database/database.sqlite` file
    3. No MySQL configuration required
- **Postconditions:** CMS runs on SQLite (< 50MB for 1,000 posts)
- **Success Criteria:** Zero manual database setup

---

### 6.2 Content Creation

**UC-3: Create First Blog Post (All Personas)**

- **Actor:** Any user (writer, admin)
- **Preconditions:** Logged into admin panel
- **Steps:**
    1. Click "Posts" → "New Post"
    2. Enter title in TipTap editor
    3. Write content using WYSIWYG or Markdown shortcuts
    4. Upload featured image (drag & drop)
    5. Add alt text for accessibility
    6. Set category, tags, SEO meta (title, description, slug)
    7. Save as draft OR publish immediately
- **Postconditions:** Post visible on public site (if published)
- **Success Criteria:** Time to first post < 15 minutes

---

**UC-4: Upload Image with Automatic EXIF Stripping (Sarah)**

- **Actor:** Privacy-conscious blogger
- **Preconditions:** Post editor open
- **Steps:**
    1. Drag & drop image into TipTap editor
    2. PineCMS automatically strips EXIF metadata (GPS, camera model)
    3. Image optimized (WebP conversion, compression)
    4. Alt text prompt appears
    5. Image embedded in post
- **Postconditions:** Image published without location/camera data
- **Success Criteria:** EXIF removal confirmation visible to user

---

**UC-5: Use Markdown Shortcuts for Formatting (Alex)**

- **Actor:** Minimalist blogger
- **Preconditions:** TipTap editor open
- **Steps:**
    1. Type `# Heading` → Auto-converts to H1
    2. Type `**bold**` → Auto-converts to bold text
    3. Type `- list item` → Auto-converts to bullet list
    4. Type ``` → Creates code block
- **Postconditions:** Markdown shortcuts work seamlessly
- **Success Criteria:** Zero friction formatting, familiar workflow

---

**UC-6: Draft → Review → Publish Workflow (Maria)**

- **Actor:** Staff writer (Maria's team)
- **Preconditions:** Workflow enabled (v1.1.0), writer role assigned
- **Steps:**
    1. Writer creates post, saves as "Pending Review"
    2. Maria (admin) receives email notification
    3. Maria logs in, reviews post
    4. Maria approves → Status changes to "Approved"
    5. Writer publishes post
- **Postconditions:** Post published after approval
- **Success Criteria:** Clear approval workflow, email notifications work

---

### 6.3 Privacy & Security

**UC-7: Enable Field-Level Encryption for Sensitive Data (Sarah)**

- **Actor:** Privacy-conscious blogger
- **Preconditions:** Custom field created (v1.1.0 plugin)
- **Steps:**
    1. Create custom field: "Source Name" (text field)
    2. Enable "Encrypt this field" checkbox
    3. Enter confidential source name
    4. Save post → Field encrypted with CipherSweet
    5. View database → Encrypted ciphertext visible (not plaintext)
- **Postconditions:** Sensitive data encrypted at rest
- **Success Criteria:** Encryption transparent to user, database shows ciphertext

---

**UC-8: No Default Analytics Tracking (Sarah)**

- **Actor:** Privacy-conscious blogger
- **Preconditions:** Fresh PineCMS installation
- **Steps:**
    1. Publish first post
    2. View public site
    3. Check browser dev tools → Network tab
    4. Verify: No Google Analytics, no tracking scripts
    5. (Optional) Install Matomo plugin later
- **Postconditions:** Zero tracking by default
- **Success Criteria:** No external scripts loaded without user consent

---

### 6.4 Media Management

**UC-9: Organize Media Library with Folders (Alex, Maria)**

- **Actor:** Blogger with 100+ images
- **Preconditions:** Media library has multiple images
- **Steps:**
    1. Navigate to "Media Library"
    2. Create folder: "Blog Headers"
    3. Drag & drop images into folder
    4. Search/filter by folder
    5. Insert image from folder into post
- **Postconditions:** Media organized logically
- **Success Criteria:** Folder-based organization, drag & drop works

---

**UC-10: Automatic Image Optimization on Upload (All Personas)**

- **Actor:** Any user uploading images
- **Preconditions:** Image upload in progress
- **Steps:**
    1. Upload high-res PNG (5 MB, 4000x3000px)
    2. PineCMS automatically:
        - Resizes to max 2000px width
        - Converts to WebP (if browser supports)
        - Compresses to < 500KB
        - Strips EXIF metadata
    3. Original uploaded to `/storage/media/originals/`
    4. Optimized version served on public site
- **Postconditions:** Optimized images, faster page load
- **Success Criteria:** Optimization automatic, transparent to user

---

### 6.5 User Management

**UC-11: Create User with Role-Based Permissions (Maria, David)**

- **Actor:** Admin user
- **Preconditions:** Admin panel access
- **Steps:**
    1. Navigate to "Users" → "Add New User"
    2. Enter username, email, password
    3. Select role: Writer, Editor, or Admin
    4. Send welcome email (optional)
    5. User logs in with assigned permissions
- **Postconditions:** User can perform role-specific actions
- **Success Criteria:** RBAC works (writer can't delete posts, editor can't manage users)

---

**UC-12: Author Publishes Post Without Admin Approval (Alex)**

- **Actor:** Single-author blogger (Alex)
- **Preconditions:** Workflow disabled (default for single-user sites)
- **Steps:**
    1. Write post in TipTap editor
    2. Click "Publish" button
    3. Post immediately visible on public site
- **Postconditions:** No approval step needed
- **Success Criteria:** Immediate publish for single-user sites

---

### 6.6 Backup & Portability

**UC-13: Export Full Site Backup via FTP (Sarah)**

- **Actor:** Privacy-conscious blogger
- **Preconditions:** FTP access to hosting
- **Steps:**
    1. Connect via FTP client (FileZilla)
    2. Download entire PineCMS directory
    3. Backup includes:
        - SQLite database (`database/database.sqlite`)
        - Flat-file content (`storage/content/`)
        - Uploaded media (`storage/media/`)
        - Themes and plugins
    4. Store backup locally or cloud (encrypted)
- **Postconditions:** Complete site backup (portable)
- **Success Criteria:** Backup restorable to new hosting, full portability

---

**UC-14: Export Content as Markdown ZIP (Sarah, Alex)**

- **Actor:** Blogger migrating to another platform
- **Preconditions:** Admin panel access
- **Steps:**
    1. Navigate to "Tools" → "Export Content"
    2. Select "Markdown ZIP" format
    3. Download ZIP containing:
        - All posts as `.md` files (YAML front matter)
        - All pages as `.md` files
        - Media files in `/images/` folder
    4. Import into another CMS or static site generator
- **Postconditions:** Content portable (no vendor lock-in)
- **Success Criteria:** Markdown export includes all metadata (categories, tags, dates)

---

### 6.7 Theme Customization

**UC-15: Switch Theme (Alex)**

- **Actor:** Minimalist blogger
- **Preconditions:** 3 built-in themes (Default, Minimalist, Business)
- **Steps:**
    1. Navigate to "Appearance" → "Themes"
    2. Preview "Minimalist" theme (live preview)
    3. Click "Activate"
    4. View public site → New theme applied
- **Postconditions:** Theme changed, no data loss
- **Success Criteria:** Live preview works, one-click activation

---

**UC-16: Customize Theme Colors with TailwindCSS (David)**

- **Actor:** Developer building client site
- **Preconditions:** Theme files accessible
- **Steps:**
    1. Open `tailwind.config.js`
    2. Edit color palette:
        ```js
        theme: {
          extend: {
            colors: {
              primary: '#1E40AF', // Client brand blue
            }
          }
        }
        ```
    3. Run `npm run build`
    4. Theme reflects new brand colors
- **Postconditions:** Custom branded theme
- **Success Criteria:** TailwindCSS utilities work, no custom CSS needed

---

### 6.8 SEO & Performance

**UC-17: Optimize Post for SEO (Maria)**

- **Actor:** Small business blog manager
- **Preconditions:** Post editor open
- **Steps:**
    1. Enter SEO meta title (< 60 chars)
    2. Enter SEO meta description (< 160 chars)
    3. Edit slug (URL-friendly: `/blog/post-title`)
    4. Add focus keyword (for internal reference)
    5. PineCMS auto-generates:
        - Open Graph tags (social media previews)
        - Twitter Card tags
        - JSON-LD schema (Article)
- **Postconditions:** Post optimized for search engines
- **Success Criteria:** SEO fields clearly labeled, auto-generated tags work

---

**UC-18: Verify Page Load Speed (Alex, Maria)**

- **Actor:** Performance-conscious blogger
- **Preconditions:** Post published
- **Steps:**
    1. Open Google PageSpeed Insights
    2. Enter post URL
    3. Run test → Score > 90/100 (Lighthouse)
    4. Verify: < 1 second page load (P95)
- **Postconditions:** Fast performance confirmed
- **Success Criteria:** Lighthouse score > 90, < 1s load

---

### 6.9 Plugin Installation (Optional)

**UC-19: Install Matomo Analytics Plugin (Sarah)**

- **Actor:** Privacy-conscious blogger (wants analytics)
- **Preconditions:** v1.1.0+, Matomo plugin available
- **Steps:**
    1. Navigate to "Plugins" → "Available Plugins"
    2. Find "Matomo Analytics (Official)"
    3. Click "Install" → Plugin ZIP uploaded
    4. Activate plugin
    5. Configure Matomo settings:
        - Self-hosted Matomo URL
        - Site ID
        - Privacy mode (no cookies, anonymized IPs)
    6. Analytics visible in admin dashboard
- **Postconditions:** Privacy-focused analytics enabled
- **Success Criteria:** Matomo integration works, privacy mode default

---

**UC-20: Install Custom Fields Pro Plugin (Maria)**

- **Actor:** Small business needing event fields (date, location)
- **Preconditions:** v1.1.0+, Custom Fields Pro plugin available
- **Steps:**
    1. Navigate to "Plugins" → "Install Plugin"
    2. Upload Custom Fields Pro ZIP
    3. Activate plugin
    4. Create custom field group: "Event Details"
    5. Add fields: Date (date picker), Location (text), Registration Link (URL)
    6. Assign to "Events" post type
    7. Staff adds event posts with custom fields
- **Postconditions:** Custom fields available for posts
- **Success Criteria:** Custom fields render correctly, repeater works

---

### 6.10 Updates & Maintenance

**UC-21: Update PineCMS Manually (All Personas)**

- **Actor:** Any admin user
- **Preconditions:** New version available (v1.0.1 → v1.0.2)
- **Steps:**
    1. Admin dashboard shows update notification
    2. Backup site via FTP (recommended)
    3. Download new version from PineCMS.org
    4. Upload ZIP via FTP (replace files)
    5. Navigate to `yoursite.com/update`
    6. Update wizard runs migrations (if needed)
    7. Dashboard shows "v1.0.2" confirmation
- **Postconditions:** CMS updated, content intact
- **Success Criteria:** Update process < 10 minutes, zero data loss

---

**UC-22: Rollback Failed Update (Maria)**

- **Actor:** Non-technical admin (update broke site)
- **Preconditions:** Site backup exists (pre-update)
- **Steps:**
    1. Site shows error after update
    2. Maria accesses FTP
    3. Deletes new files
    4. Restores backup files via FTP
    5. Site functional again
- **Postconditions:** Site restored to pre-update state
- **Success Criteria:** Rollback possible via FTP, clear docs available

---

## 7. Requirements Matrix

### 7.1 Functional Requirements by Persona

| Requirement                | Priority    | Sarah | Alex | Maria | David | Version |
| -------------------------- | ----------- | ----- | ---- | ----- | ----- | ------- |
| **Web Installer**          | 🔴 Critical | ✅    | ✅   | ✅    | ✅    | v1.0.0  |
| **SQLite Support**         | 🔴 Critical | ✅    | ✅   | ⚠️    | ⚠️    | v1.0.0  |
| **No Default Analytics**   | 🔴 Critical | ✅    | ⚠️   | ❌    | ❌    | v1.0.0  |
| **EXIF Stripping**         | 🔴 Critical | ✅    | ❌   | ❌    | ❌    | v1.0.0  |
| **Field-Level Encryption** | 🟠 High     | ✅    | ❌   | ⚠️    | ❌    | v1.0.0  |
| **TipTap WYSIWYG**         | 🔴 Critical | ✅    | ✅   | ✅    | ⚠️    | v1.0.0  |
| **Markdown Support**       | 🟠 High     | ✅    | ✅   | ❌    | ⚠️    | v1.0.0  |
| **Built-in Themes (3)**    | 🔴 Critical | ⚠️    | ✅   | ✅    | ✅    | v1.0.0  |
| **RBAC (3 Roles)**         | 🟠 High     | ❌    | ❌   | ✅    | ✅    | v1.0.0  |
| **SEO Fields**             | 🟠 High     | ⚠️    | ⚠️   | ✅    | ⚠️    | v1.0.0  |
| **Image Optimization**     | 🟠 High     | ⚠️    | ✅   | ✅    | ⚠️    | v1.0.0  |
| **Media Library**          | 🟠 High     | ✅    | ✅   | ✅    | ⚠️    | v1.0.0  |
| **Post Revisions**         | 🟡 Medium   | ⚠️    | ❌   | ✅    | ❌    | v1.0.0  |
| **Backup/Export**          | 🟠 High     | ✅    | ⚠️   | ⚠️    | ❌    | v1.0.0  |
| **Workflow System**        | 🟡 Medium   | ❌    | ❌   | ✅    | ⚠️    | v1.1.0  |
| **Plugin System**          | 🟠 High     | ⚠️    | ❌   | ⚠️    | ✅    | v1.1.0  |
| **Matomo Plugin**          | 🟡 Medium   | ✅    | ❌   | ❌    | ❌    | v1.1.0  |
| **Custom Fields Pro**      | 🟡 Medium   | ⚠️    | ❌   | ✅    | ⚠️    | v1.1.0  |
| **Event-Driven Arch**      | 🟠 High     | ❌    | ❌   | ❌    | ✅    | v1.0.0  |
| **Laravel 12 Code**        | 🟠 High     | ❌    | ❌   | ❌    | ✅    | v1.0.0  |
| **< 1s Page Load**         | 🔴 Critical | ✅    | ✅   | ✅    | ⚠️    | v1.0.0  |

**Legend:**

- ✅ Critical requirement for persona
- ⚠️ Desirable but not critical
- ❌ Not important for persona
- 🔴 Blocker for release
- 🟠 High priority
- 🟡 Medium priority

---

### 7.2 Non-Functional Requirements by Persona

| Requirement                | Target   | Sarah | Alex | Maria | David | Version |
| -------------------------- | -------- | ----- | ---- | ----- | ----- | ------- |
| **Installation Time**      | < 10 min | ✅    | ✅   | ✅    | ✅    | v1.0.0  |
| **Page Load (P95)**        | < 1s     | ✅    | ✅   | ✅    | ⚠️    | v1.0.0  |
| **Database Query**         | < 100ms  | ⚠️    | ⚠️   | ⚠️    | ✅    | v1.0.0  |
| **Lighthouse Score**       | > 90/100 | ⚠️    | ✅   | ✅    | ⚠️    | v1.0.0  |
| **WCAG 2.1 AA**            | 100%     | ✅    | ⚠️   | ✅    | ❌    | v1.0.0  |
| **Uptime (Production)**    | > 99.5%  | ✅    | ⚠️   | ✅    | ⚠️    | v1.0.0  |
| **Zero Tracking Scripts**  | 100%     | ✅    | ❌   | ❌    | ❌    | v1.0.0  |
| **Mobile Responsive**      | 100%     | ✅    | ✅   | ✅    | ⚠️    | v1.0.0  |
| **Code Coverage**          | > 80%    | ❌    | ❌   | ❌    | ✅    | v1.0.0  |
| **PHPStan Level**          | 8        | ❌    | ❌   | ❌    | ✅    | v1.0.0  |
| **Flat-File Backup**       | < 100MB  | ✅    | ⚠️   | ⚠️    | ❌    | v1.0.0  |
| **SQLite Size (1K posts)** | < 50MB   | ✅    | ⚠️   | ⚠️    | ✅    | v1.0.0  |

---

## 8. Accessibility Requirements

### 8.1 WCAG 2.1 AA Compliance

**Target:** 100% compliance with WCAG 2.1 Level AA

**Affected Personas:** Sarah (journalism ethics), Maria (business compliance)

**Key Requirements:**

**Perceivable:**

- ✅ All images have descriptive alt text (required field)
- ✅ Color contrast ratio ≥ 4.5:1 for normal text
- ✅ Color contrast ratio ≥ 3:1 for large text (18pt+)
- ✅ Media players have captions (if video support added)
- ✅ Admin panel supports dark mode (high contrast)

**Operable:**

- ✅ All functionality keyboard-accessible (no mouse required)
- ✅ Focus indicators clearly visible (outline: 2px solid)
- ✅ No flashing content (seizure risk)
- ✅ Skip to content link (keyboard navigation)
- ✅ Logical tab order (left-to-right, top-to-bottom)

**Understandable:**

- ✅ Form labels clearly associated with inputs
- ✅ Error messages descriptive and actionable
- ✅ Language declared in HTML (`<html lang="en">`)
- ✅ Consistent navigation across pages

**Robust:**

- ✅ Valid HTML5 (W3C validator)
- ✅ ARIA landmarks for screen readers
- ✅ Semantic HTML (`<article>`, `<nav>`, `<main>`)
- ✅ Accessible name for all interactive elements

---

### 8.2 Admin Panel Accessibility

**Admin Panel (Vue/Inertia) Requirements:**

**Keyboard Navigation:**

- ✅ All buttons/links focusable via Tab
- ✅ Modal dialogs trap focus (Esc to close)
- ✅ Dropdowns navigable with arrow keys
- ✅ TipTap editor supports keyboard shortcuts (Ctrl+B for bold)

**Screen Reader Support:**

- ✅ ARIA live regions for dynamic content (notifications)
- ✅ ARIA labels for icon-only buttons
- ✅ Form validation errors announced
- ✅ Page title updates on navigation (Inertia.js meta)

**Visual:**

- ✅ Font size ≥ 16px (default body text)
- ✅ Line height ≥ 1.5 (readability)
- ✅ Resizable text up to 200% without horizontal scroll
- ✅ Dark mode support (user preference)

---

### 8.3 Public Frontend Accessibility

**Default Theme Requirements:**

**Typography:**

- ✅ Font size: 18px minimum (body text)
- ✅ Line height: 1.6-1.8 (optimal readability)
- ✅ Maximum line width: 70 characters (readability)
- ✅ Sufficient spacing between paragraphs (1em)

**Navigation:**

- ✅ Skip to main content link (hidden until focused)
- ✅ Breadcrumbs for hierarchical navigation
- ✅ Current page highlighted in navigation

**Forms:**

- ✅ Labels visually associated with inputs
- ✅ Required fields marked with asterisk + ARIA
- ✅ Error messages next to invalid fields
- ✅ Submit button has descriptive text (not just "Submit")

---

## 9. Usability Heuristics

### 9.1 Nielsen's 10 Usability Heuristics Applied

**1. Visibility of System Status**

- ✅ Loading spinners for async operations (post save, image upload)
- ✅ Success notifications (green toast: "Post published!")
- ✅ Error notifications (red toast: "Upload failed, file too large")
- ✅ Progress bars for long operations (backup export)

**2. Match Between System and Real World**

- ✅ Familiar terminology ("Posts" not "Content Items")
- ✅ Chronological order (newest first, like email)
- ✅ Trash can icon for delete (not "Remove")
- ✅ Metaphors: "Folders" for media organization

**3. User Control and Freedom**

- ✅ Undo/redo in TipTap editor (Ctrl+Z, Ctrl+Y)
- ✅ Trash/restore for deleted posts (30-day retention)
- ✅ Post revisions (rollback to previous version)
- ✅ Cancel buttons clearly visible (modal dialogs)

**4. Consistency and Standards**

- ✅ Primary action buttons blue (publish, save)
- ✅ Destructive actions red (delete, trash)
- ✅ Cancel buttons gray (secondary action)
- ✅ Consistent icon set (Heroicons)

**5. Error Prevention**

- ✅ Confirmation dialogs for destructive actions ("Delete post?")
- ✅ Auto-save drafts every 30 seconds
- ✅ Required field validation before submit
- ✅ File type validation on upload (images only)

**6. Recognition Rather Than Recall**

- ✅ Recently used categories visible in dropdown
- ✅ Image thumbnails in media library (not filenames)
- ✅ Post preview before publish (visual confirmation)
- ✅ Clear breadcrumbs (where am I?)

**7. Flexibility and Efficiency of Use**

- ✅ Keyboard shortcuts (Ctrl+S to save, Ctrl+P to publish)
- ✅ Bulk actions (select multiple posts → delete)
- ✅ Quick filters (published, draft, trash)
- ✅ Search autocomplete (find posts quickly)

**8. Aesthetic and Minimalist Design**

- ✅ Clean admin interface (white space, no clutter)
- ✅ Only essential actions visible (advanced in "More")
- ✅ Collapsed sidebars by default (focus on content)
- ✅ TipTap editor: fullscreen mode (distraction-free)

**9. Help Users Recognize, Diagnose, and Recover from Errors**

- ✅ Error messages in plain language ("Image too large" not "Error 413")
- ✅ Suggestions for fixing ("Max 10MB, try compressing")
- ✅ Validation errors next to specific fields
- ✅ Support link in error messages (docs.pinecms.org)

**10. Help and Documentation**

- ✅ Inline help text (tooltips on hover)
- ✅ Contextual help ("?" icon → relevant docs page)
- ✅ Onboarding checklist for new users (first login)
- ✅ Video tutorials linked in dashboard

---

## 10. Key Insights & Recommendations

### 10.1 Research Insights

**Privacy is a Growing Priority:**

- 77% of Americans distrust social media platforms with data (Pew 2023)
- Google's third-party cookie phase-out signals industry shift
- Users actively seek "no tracking by default" alternatives
- **Recommendation:** Position PineCMS as "privacy-first" in all marketing

**Minimalism is a Strong Selling Point:**

- Bloggers frustrated with WordPress's 50+ admin menu items
- Demand for "distraction-free writing" environments growing
- Successful minimal CMSs: Ghost, Write.as, Anchor CMS
- **Recommendation:** Keep core features < 100 (v1.0.0: 95 features)

**Shared Hosting Still Relevant:**

- Small businesses and hobbyists prefer managed hosting ($5-20/month)
- VPS/Docker complexity is a barrier for non-technical users
- Web installers remain most accessible entry point
- **Recommendation:** Web-installer-only deployment (no Composer requirement)

**Workflow Systems Critical for Teams:**

- Small businesses need approval workflows (draft → review → publish)
- Non-technical staff require clear role permissions
- Email notifications essential for team coordination
- **Recommendation:** Prioritize Workflow System in v1.1.0

---

### 10.2 Strategic Recommendations

**Focus on Niche, Not Mass Market:**

- Target: 5,000-10,000 loyal users (Year 5)
- NOT targeting: Millions of installations
- Quality over quantity: Engaged community > download numbers
- **Action:** All marketing emphasizes "focused, sustainable CMS"

**5-10 Year Sustainability Commitment:**

- Users value long-term reliability over cutting-edge features
- Communicate 5-10 year maintenance commitment clearly
- Regular, predictable updates (quarterly releases)
- **Action:** Publish 5-year roadmap, sustainability plan

**Developer-Friendly Codebase:**

- Modern Laravel code attracts developers building client sites
- Event-driven architecture differentiates from WordPress
- Developer word-of-mouth powerful for CMS adoption
- **Action:** Comprehensive technical documentation for developers

**Plugin Ecosystem Gradual Growth:**

- Official plugins only (4-5 total, v1.1-1.2)
- Community plugins later (v2.0+, plugin directory)
- Quality over quantity (no plugin bloat)
- **Action:** Focus on 4 official plugins (Newsletter, Custom Fields Pro, Multi-Language, SEO Pro) + optional Matomo

---

### 10.3 Feature Prioritization

**v1.0.0 MVP (Must-Have for All Personas):**

- Web installer ✅
- TipTap WYSIWYG editor ✅
- SQLite + MySQL support ✅
- No default analytics ✅
- EXIF stripping ✅
- Field-level encryption ✅
- 3 built-in themes ✅
- RBAC (3 roles) ✅
- SEO fields ✅
- Backup/export ✅

**v1.1.0 Enhanced (Team Workflows + Plugins):**

- Workflow system ✅ (Maria's critical need)
- Plugin system ✅ (David's extensibility)
- Matomo Analytics Plugin ✅ (Sarah's optional analytics)
- Custom Fields Pro ✅ (Maria's custom content needs)

**v1.2.0 (Advanced Features):**

- Multi-Language Plugin ✅ (International users)
- SEO Pro Plugin ✅ (Maria's advanced SEO)
- Menu Builder ✅ (Complex navigation)
- Redirects Manager ✅ (Site migrations)

---

## 11. Assumptions & Constraints

### 11.1 Assumptions

**User Assumptions:**

- ✅ Users have basic web hosting with PHP 8.3+ and SQLite/MySQL
- ✅ Users can upload files via FTP or file manager
- ✅ Users understand basic CMS concepts (posts, pages, categories)
- ✅ Developers have Laravel ecosystem knowledge

**Market Assumptions:**

- ✅ Privacy-conscious niche will grow over next 5 years
- ✅ Shared hosting remains viable for 5-10 years
- ✅ WordPress will remain dominant (we're not competing directly)
- ✅ Flat-file CMSs have sustained demand (Grav, Kirby, Statamic exist)

**Technical Assumptions:**

- ✅ Laravel 12 will be supported for 5+ years
- ✅ SQLite performance sufficient for < 10,000 posts
- ✅ Vue 3.5 + Inertia.js stable for 5+ years
- ✅ Modern shared hosting supports PHP 8.3+, SSH, Composer

---

### 11.2 Constraints

**Budget Constraints:**

- ⚠️ Open-source project (no VC funding)
- ⚠️ Volunteer-driven development (Year 1-2)
- ⚠️ Target: Self-sustaining via donations (Year 3+)

**Technical Constraints:**

- ⚠️ Shared hosting limits (CPU, memory, no Node.js server required)
- ⚠️ SQLite size limit (< 50MB for 1,000 posts)
- ⚠️ Manual updates (no auto-update system in v1.0-1.2)

**Timeline Constraints:**

- ⚠️ v1.0.0 MVP: Q2 2025 (10-11 weeks development)
- ⚠️ v1.2.0: Q3 2025 (15-18 weeks total)
- ⚠️ Realistic scope: 95 features (v1.0), not 300+

---

## 12. Open Questions

### 12.1 User Research Questions

- ❓ What is optimal onboarding flow for non-technical users? (Maria)
- ❓ Should dark mode be default or opt-in? (Alex preference)
- ❓ How many built-in themes sufficient? (3 or 5?)
- ❓ Is Markdown support critical for all personas or just Sarah/Alex?

### 12.2 Feature Questions

- ❓ Should Custom Fields Pro be in core or plugin? (Currently plugin)
- ❓ Is Comments System needed in v1.0.0 or v1.1.0? (Currently v1.1.0)
- ❓ Should Workflow System support custom statuses? (Currently: Draft/Pending/Approved)
- ❓ Is auto-save frequency 30 seconds optimal or too frequent?

### 12.3 Technical Questions

- ❓ Should MySQL be prioritized over SQLite for Maria's use case?
- ❓ Is field-level encryption performance acceptable for 1,000+ posts?
- ❓ Should web installer support subdirectory installation?
- ❓ Is manual update process sufficient or should v1.2.0 add one-click updates?

---

## 13. Change History

| Date       | Version | Author       | Changes                                                                       |
| ---------- | ------- | ------------ | ----------------------------------------------------------------------------- |
| 2025-11-07 | 1.0     | PineCMS Team | Initial user research (4 personas, 22 use cases, journey maps, accessibility) |

---

**Last Updated:** 2025-11-07
**Document Owner:** PineCMS Team
**Next Review:** 2025-12-07
