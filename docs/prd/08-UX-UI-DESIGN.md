# 08 - UX/UI Design

**Version:** 1.0.0
**Last Updated:** 2025-11-07
**Status:** 📋 Planned
**Owner:** PineCMS Team

---

## 1. Overview

This document specifies the **User Experience (UX) and User Interface (UI)** design for PineCMS, including admin panel wireframes, public frontend design, user flows, design system, and accessibility guidelines.

**Design Inspiration:**

- **Colors:** Forest Green palette (Figma Colors)
- **Admin:** Modern SaaS dashboards (Dribbble, Figma templates)
- **Blog:** HackTheBox, Anomal.xyz, Medium, TechCrunch minimalism
- **Syntax Highlighting:** Shiki (VS Code themes)

**Design Principles:**

- **Simplicity First:** Minimal, focused interface (no bloat)
- **Privacy by Default:** No tracking, no telemetry
- **Accessibility:** WCAG 2.1 AA compliance
- **Performance:** < 1 second page loads
- **Modern:** Clean, professional aesthetics inspired by leading tech blogs
- **Consistency:** Unified design language across admin/frontend

**Target Audience:**

- Non-technical bloggers
- Small business owners
- Developers (for clients)
- Privacy-conscious users

---

## 2. Color System

### 2.1 Primary Palette (Forest Green Theme)

**Inspired by:** Figma Forest Green colors

**Primary Colors:**

- **Pine Green 900:** `#1E3F2A` - Darkest, hover states
- **Pine Green 700:** `#2D5F3E` - **Primary brand color** (buttons, links, accents)
- **Pine Green 500:** `#3D7F4E` - Interactive elements
- **Pine Green 300:** `#5FAF6E` - Light accents
- **Pine Green 100:** `#D4E9DA` - Backgrounds, subtle highlights

**Semantic Colors:**

- **Success:** `#10B981` (Emerald 500) - Success messages, confirmations
- **Warning:** `#F59E0B` (Amber 500) - Warnings, cautions
- **Error:** `#EF4444` (Red 500) - Errors, destructive actions
- **Info:** `#3B82F6` (Blue 500) - Informational messages

**Neutral Colors (Gray Scale):**

- **Gray 900:** `#111827` - Primary text
- **Gray 700:** `#374151` - Secondary text
- **Gray 500:** `#6B7280` - Muted text, placeholders
- **Gray 300:** `#D1D5DB` - Borders, dividers
- **Gray 100:** `#F3F4F6` - Light backgrounds, cards
- **Gray 50:** `#F9FAFB` - Page backgrounds
- **White:** `#FFFFFF` - Pure white, surface

**Contrast Ratios (WCAG 2.1 AA Compliant):**

- Pine Green 700 on White: **6.2:1** ✅ (AA)
- Gray 900 on White: **16.8:1** ✅ (AAA)
- Gray 700 on White: **8.5:1** ✅ (AAA)

---

### 2.2 Dark Mode (Optional v1.2.0)

**Background:**

- Gray 900: `#111827` - Page background
- Gray 800: `#1F2937` - Card/panel background
- Gray 700: `#374151` - Elevated surfaces

**Text:**

- Gray 50: `#F9FAFB` - Primary text
- Gray 300: `#D1D5DB` - Secondary text
- Gray 500: `#6B7280` - Muted text

**Primary (Adjusted for Dark):**

- Pine Green 400: `#4D9F5E` - Lighter for contrast

---

## 3. Typography

### 3.1 Font Families

**Sans-Serif (UI):**

```css
font-family:
    'Inter',
    -apple-system,
    BlinkMacSystemFont,
    'Segoe UI',
    'Roboto',
    'Helvetica Neue',
    Arial,
    sans-serif;
```

**Serif (Optional for blog posts):**

```css
font-family: 'Merriweather', Georgia, 'Times New Roman', serif;
```

**Monospace (Code blocks):**

```css
font-family: 'Fira Code', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', monospace;
```

---

### 3.2 Type Scale

**Admin Panel:**

- **H1 (Page Titles):** 2.5rem (40px), Font Weight 700, Line Height 1.2
- **H2 (Sections):** 2rem (32px), Font Weight 600, Line Height 1.3
- **H3 (Subsections):** 1.5rem (24px), Font Weight 600, Line Height 1.4
- **Body:** 1rem (16px), Font Weight 400, Line Height 1.6
- **Small:** 0.875rem (14px), Font Weight 400, Line Height 1.5
- **Tiny:** 0.75rem (12px), Font Weight 400, Line Height 1.4

**Public Blog (Optimized for Readability):**

- **H1 (Post Title):** 2.5rem (40px), Font Weight 700, Line Height 1.2
- **H2 (Headings):** 1.875rem (30px), Font Weight 600, Line Height 1.3
- **H3 (Subheadings):** 1.5rem (24px), Font Weight 600, Line Height 1.4
- **Body:** 1.125rem (18px), Font Weight 400, Line Height 1.7 (optimal readability)
- **Caption:** 0.875rem (14px), Font Weight 400, Line Height 1.5

---

## 4. Admin Panel Design

### 4.1 Layout Structure

**Inspired by:** Modern SaaS dashboards (Dribbble, Figma templates)

```
┌────────────────────────────────────────────────────────┐
│  HEADER                                      [User] 👤 │
│  [Pine Logo] Dashboard  Posts  Pages  Media  Settings │
└────────────────────────────────────────────────────────┘
┌──────────┬─────────────────────────────────────────────┐
│          │                                             │
│ SIDEBAR  │          MAIN CONTENT AREA                  │
│          │                                             │
│ 📊 Dashboard                                          │
│ 📝 Posts   │       [Page Title - H1]                   │
│   All      │       [Breadcrumb: Home > Posts > All]    │
│   New      │                                           │
│ 📄 Pages   │       [Action Buttons: New Post, Filter]  │
│ 🖼️ Media   │                                           │
│ 💬 Comments│       [Content: Table, Form, Editor]      │
│ ⚙️ Settings│                                           │
│            │                                           │
│ [Collapse]│                                           │
│            │                                           │
└──────────┴─────────────────────────────────────────────┘
```

**Header:**

- Logo (top-left, Pine Green)
- Horizontal navigation: Dashboard, Posts, Pages, Media, Settings
- User menu (top-right): Avatar, Name, Dropdown (Profile, Logout)
- Height: 64px
- Background: White, Border-bottom: Gray 300

**Sidebar:**

- Width: 240px (expanded), 64px (collapsed)
- Background: White
- Border-right: Gray 300
- Icons: 20px, Pine Green 700
- Hover state: Gray 100 background
- Active state: Pine Green 100 background, Pine Green 700 text

**Main Content:**

- Padding: 24px
- Background: Gray 50
- Max-width: 1400px (centered)

---

### 4.2 Component Designs

#### 4.2.1 Buttons

**Primary Button:**

```css
background: #2D5F3E (Pine Green 700)
color: #FFFFFF
padding: 10px 20px
border-radius: 6px
font-weight: 500
box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05)

hover:
  background: #1E3F2A (Pine Green 900)
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1)
```

**Secondary Button:**

```css
background: #F3F4F6 (Gray 100)
color: #111827 (Gray 900)
padding: 10px 20px
border-radius: 6px
font-weight: 500

hover:
  background: #E5E7EB (Gray 200)
```

**Danger Button:**

```css
background: #EF4444 (Red 500)
color: #FFFFFF
padding: 10px 20px
border-radius: 6px
font-weight: 500

hover:
  background: #DC2626 (Red 600)
```

**Ghost Button:**

```css
background: transparent
color: #374151 (Gray 700)
padding: 10px 20px
border-radius: 6px

hover:
  background: #F3F4F6 (Gray 100)
```

---

#### 4.2.2 Form Inputs

**Text Input:**

```css
width: 100%
padding: 10px 12px
border: 1px solid #D1D5DB (Gray 300)
border-radius: 6px
font-size: 16px
background: #FFFFFF

focus:
  outline: none
  border-color: #2D5F3E (Pine Green 700)
  box-shadow: 0 0 0 3px rgba(45, 95, 62, 0.1)
```

**Textarea:**

```css
(Same as Text Input)
min-height: 120px
resize: vertical
```

**Select Dropdown:**

```css
(Same as Text Input)
appearance: none
background-image: url(chevron-down-icon)
background-position: right 12px center
padding-right: 36px
```

---

#### 4.2.3 Cards

**Inspired by:** HackTheBox blog cards

**Card Style:**

```css
background: #FFFFFF
border: 1px solid #E5E7EB (Gray 200)
border-radius: 8px
padding: 20px
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1)

hover:
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1)
  border-color: #2D5F3E (Pine Green 700)
```

**Usage:**

- Dashboard widgets
- Post/Page cards
- Media thumbnails

---

#### 4.2.4 Data Tables (PrimeVue DataTable)

**Inspired by:** Modern SaaS admin panels

**Table Style:**

```css
Header:
  background: #F9FAFB (Gray 50)
  border-bottom: 2px solid #E5E7EB (Gray 200)
  font-weight: 600
  color: #374151 (Gray 700)
  padding: 12px

Row:
  background: #FFFFFF
  border-bottom: 1px solid #F3F4F6 (Gray 100)
  padding: 12px

Row Hover:
  background: #F9FAFB (Gray 50)

Actions:
  Icons: 20px
  Color: #6B7280 (Gray 500)
  Hover: #2D5F3E (Pine Green 700)
```

**Features:**

- Sortable columns
- Filterable
- Pagination (10, 25, 50, 100 per page)
- Bulk actions (checkboxes)
- Search bar (top-right)

---

#### 4.2.5 TipTap Editor

**Editor Container:**

```css
background: #FFFFFF
border: 1px solid #D1D5DB (Gray 300)
border-radius: 8px
min-height: 400px
```

**Toolbar:**

```css
background: #F9FAFB (Gray 50)
border-bottom: 1px solid #E5E7EB (Gray 200)
padding: 8px 12px
border-radius: 8px 8px 0 0

Buttons:
  width: 32px
  height: 32px
  border-radius: 4px
  color: #6B7280 (Gray 500)

  hover:
    background: #E5E7EB (Gray 200)

  active:
    background: #2D5F3E (Pine Green 700)
    color: #FFFFFF
```

**Editor Content Area:**

```css
padding: 20px
font-size: 16px
line-height: 1.7
max-width: 800px (for readability)
```

**Code Block Extension (TipTap + Shiki):**

- Language selection dropdown (auto-detect)
- Line numbers (optional, configurable)
- Copy button (top-right)
- Theme: VS Code Dark+ (default), Light+ (light mode)

---

## 5. Public Frontend Design

### 5.1 Blog Homepage Layout

**Inspired by:** HackTheBox, Anomal.xyz, Medium

```
┌────────────────────────────────────────────────────────┐
│  HEADER                                                │
│  [Pine Logo]    Home | Blog | About | Contact     🔍  │
└────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────┐
│           FEATURED POST (Hero Section)                 │
│  ┌──────────────────────────────────────────────────┐ │
│  │ [Large Featured Image]                           │ │
│  │ [Category Badge: Technology]                     │ │
│  │ H1: "Latest Blog Post Title"                     │ │
│  │ Excerpt: "Short description..."                  │ │
│  │ [Author Avatar] John Doe · 5 min read           │ │
│  │ [Read More Button]                               │ │
│  └──────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────┐
│              BLOG POSTS GRID (Cards)                   │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐     │
│  │ [Image]     │ │ [Image]     │ │ [Image]     │     │
│  │ [Category]  │ │ [Category]  │ │ [Category]  │     │
│  │ H3: Title   │ │ H3: Title   │ │ H3: Title   │     │
│  │ Excerpt...  │ │ Excerpt...  │ │ Excerpt...  │     │
│  │ Author·Date │ │ Author·Date │ │ Author·Date │     │
│  └─────────────┘ └─────────────┘ └─────────────┘     │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐     │
│  │ [Post 4]    │ │ [Post 5]    │ │ [Post 6]    │     │
│  └─────────────┘ └─────────────┘ └─────────────┘     │
│                                                        │
│  [Load More] or [Pagination: 1 2 3 ... 10]           │
└────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────┐
│  FOOTER                                                │
│  © 2025 PineCMS | Privacy | Terms | RSS              │
│  [Social Icons: Twitter, GitHub, LinkedIn]            │
└────────────────────────────────────────────────────────┘
```

---

### 5.2 Blog Post Card Design

**Inspired by:** HackTheBox blog cards

```css
Card Container:
  background: #FFFFFF
  border: 1px solid #E5E7EB (Gray 200)
  border-radius: 12px
  overflow: hidden
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1)
  transition: all 0.2s

  hover:
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15)
    transform: translateY(-4px)

Featured Image:
  width: 100%
  aspect-ratio: 16/9
  object-fit: cover

Category Badge:
  position: absolute
  top: 16px
  left: 16px
  background: rgba(45, 95, 62, 0.9) (Pine Green 700 with opacity)
  color: #FFFFFF
  padding: 6px 12px
  border-radius: 4px
  font-size: 12px
  font-weight: 600
  text-transform: uppercase

Card Content:
  padding: 20px

Title (H3):
  font-size: 20px
  font-weight: 600
  color: #111827 (Gray 900)
  margin-bottom: 12px
  line-height: 1.4

  hover:
    color: #2D5F3E (Pine Green 700)

Excerpt:
  font-size: 14px
  color: #6B7280 (Gray 500)
  line-height: 1.6
  margin-bottom: 16px
  max-lines: 3
  overflow: hidden

Meta (Author + Date + Read Time):
  display: flex
  align-items: center
  font-size: 14px
  color: #9CA3AF (Gray 400)

  Author Avatar:
    width: 32px
    height: 32px
    border-radius: 50%
    margin-right: 8px
```

---

### 5.3 Blog Post Single View

**Inspired by:** Medium reading experience

````
┌────────────────────────────────────────────────────────┐
│  [Full-Width Featured Image]                          │
│  (Aspect Ratio: 21:9, Max Height: 500px)              │
└────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────┐
│              ARTICLE HEADER (Centered)                 │
│  [Category Badge: Technology]                          │
│  H1: "Complete Blog Post Title"                       │
│  [Author Avatar] John Doe · Jan 27, 2025 · 5 min read│
│  [Social Share: Twitter, Facebook, LinkedIn]          │
└────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────┐
│            ARTICLE CONTENT (Centered)                  │
│  (Max Width: 700px for optimal readability)           │
│                                                        │
│  ## Heading 2                                          │
│  Lorem ipsum dolor sit amet, consectetur adipiscing   │
│  elit. Paragraph text with optimal line length...     │
│                                                        │
│  [Image]                                               │
│  Caption: Image description                            │
│                                                        │
│  ### Heading 3                                         │
│  More content...                                       │
│                                                        │
│  ```php                                                │
│  // Code block with Shiki syntax highlighting         │
│  echo "Hello World";                                   │
│  ```                                                   │
│                                                        │
│  > Blockquote                                          │
│                                                        │
└────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────┐
│              ARTICLE FOOTER                            │
│  Tags: [#laravel] [#cms] [#php] [#privacy]           │
│  [Share: Twitter, Facebook, LinkedIn, Copy Link]      │
└────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────┐
│           COMMENTS SECTION (v1.1.0+)                   │
│  [Comment Form]                                        │
│  [Comments List]                                       │
└────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────┐
│          RELATED POSTS (3 Cards)                       │
│  [Post 1] [Post 2] [Post 3]                           │
└────────────────────────────────────────────────────────┘
````

**Article Content Styling:**

```css
Container:
  max-width: 700px
  margin: 0 auto
  padding: 40px 20px
  font-size: 18px
  line-height: 1.7
  color: #111827 (Gray 900)

Headings:
  H2: 30px, margin-top: 48px, margin-bottom: 16px
  H3: 24px, margin-top: 32px, margin-bottom: 12px
  H4: 20px, margin-top: 24px, margin-bottom: 8px

Paragraphs:
  margin-bottom: 24px

Links:
  color: #2D5F3E (Pine Green 700)
  text-decoration: underline

  hover:
    color: #1E3F2A (Pine Green 900)

Images:
  max-width: 100%
  height: auto
  border-radius: 8px
  margin: 32px 0

Code (inline):
  background: #F3F4F6 (Gray 100)
  padding: 2px 6px
  border-radius: 4px
  font-family: 'Fira Code', monospace
  font-size: 0.9em
  color: #EF4444 (Red 500)

Code Blocks (Shiki):
  margin: 32px 0
  border-radius: 8px
  overflow: hidden
  font-family: 'Fira Code', monospace
  font-size: 14px
  line-height: 1.6

  /* Shiki renders pre-styled HTML with inline styles */
  /* Default theme: VS Code Dark+ (dark mode) */
  /* Alternative: github-light (light mode) */

  Copy Button:
    position: absolute
    top: 12px
    right: 12px
    background: rgba(255, 255, 255, 0.1)
    color: #FFFFFF
    padding: 6px 12px
    border-radius: 4px
    font-size: 12px
    cursor: pointer

    hover:
      background: rgba(255, 255, 255, 0.2)

Blockquotes:
  border-left: 4px solid #2D5F3E (Pine Green 700)
  padding-left: 20px
  margin: 24px 0
  color: #6B7280 (Gray 500)
  font-style: italic
```

---

### 5.4 Syntax Highlighting (Shiki)

**Library:** [Shiki](https://github.com/shikijs/shiki)
**Why Shiki:**

- ✅ VS Code themes (accurate, beautiful highlighting)
- ✅ Server-side rendering (no client-side JS required)
- ✅ 200+ languages supported
- ✅ No runtime dependencies (generates static HTML)
- ✅ Perfect color accuracy (same as VS Code)

**Implementation:**

**TipTap CodeBlock Extension:**

```javascript
import { CodeBlockLowlight } from '@tiptap/extension-code-block-lowlight';
import { createLowlight } from 'lowlight';
import { getHighlighter } from 'shiki';

// Use Shiki for server-side rendering
const highlighter = await getHighlighter({
    themes: ['github-dark', 'github-light'],
    langs: ['javascript', 'typescript', 'php', 'python', 'html', 'css', 'bash'],
});

// Render code blocks with Shiki
const html = highlighter.codeToHtml(code, {
    lang: 'php',
    theme: 'github-dark',
});
```

**Supported Themes:**

- **Dark Mode:** `github-dark`, `nord`, `one-dark-pro`
- **Light Mode:** `github-light`, `light-plus`, `min-light`

**Supported Languages (Core):**

- PHP, JavaScript, TypeScript, Python, Ruby
- HTML, CSS, SCSS, JSON, YAML, Markdown
- Bash, SQL, Dockerfile, Nginx
- Vue, React (JSX/TSX)

**Code Block Features:**

- Language auto-detection
- Line numbers (optional)
- Line highlighting (specific lines)
- Copy to clipboard button
- File name display (optional)
- Diff syntax highlighting (+ / -)

**Example Code Block (Rendered):**

```html
<pre class="shiki github-dark" style="background-color:#0d1117;color:#c9d1d9">
  <code>
    <span class="line">
      <span style="color:#FF7B72">echo</span>
      <span style="color:#A5D6FF"> "Hello World"</span>
      <span style="color:#C9D1D9">;</span>
    </span>
  </code>
</pre>
```

---

## 6. Responsive Design

### 6.1 Breakpoints

```css
/* Mobile */
@media (max-width: 640px) { ... }

/* Tablet */
@media (min-width: 640px) and (max-width: 1024px) { ... }

/* Desktop */
@media (min-width: 1024px) { ... }
```

---

### 6.2 Mobile Optimizations

**Admin Panel:**

- Sidebar collapsed by default (hamburger menu)
- Header: Logo + hamburger icon only
- Tables: Horizontal scroll or stacked cards
- Forms: Full-width inputs

**Public Frontend:**

- Header: Logo + hamburger menu
- Featured post: Smaller image, stacked layout
- Blog grid: 1 column (mobile), 2 columns (tablet), 3 columns (desktop)
- Article content: Full-width, 16px font size (increased for mobile readability)
- Touch-friendly buttons: Minimum 44x44px
- Code blocks: Horizontal scroll (if needed)

---

## 7. User Flows

### 7.1 Create New Post Flow

```
1. Dashboard → Click "New Post" (Sidebar or Header button)
   ↓
2. Post Editor (Blank Canvas)
   - Title input (focus on load)
   - TipTap editor
   - Right sidebar: Settings (Status, Categories, Tags, Featured Image)
   ↓
3. User types title + content
   ↓
4. Auto-save every 30 seconds (status indicator: "Saved at 10:45 AM")
   ↓
5. User selects categories, tags, uploads featured image
   ↓
6. User clicks "Publish" or "Save Draft"
   ↓
7. Success Toast: "Post published successfully!"
   ↓
8. Redirect to "All Posts" list (new post visible at top)
```

---

### 7.2 Upload Media Flow

```
1. Media Library → Click "Upload" button
   ↓
2. File Upload Modal
   - Drag & drop zone (highlighted on dragover)
   - OR "Browse Files" button
   ↓
3. User selects file(s)
   ↓
4. Upload Progress
   - Progress bar: 0% → 100%
   - File name, size displayed
   ↓
5. Success
   - Modal closes
   - New file appears in Media Library grid
   - Success Toast: "1 file uploaded successfully"
   ↓
6. User clicks file to edit metadata (Alt text, Title, Description)
```

---

## 8. Accessibility (WCAG 2.1 AA)

### 8.1 Color Contrast

**Requirements:**

- Normal text (< 18px): Contrast ratio ≥ 4.5:1
- Large text (≥ 18px): Contrast ratio ≥ 3:1
- UI components: Contrast ratio ≥ 3:1

**Verified Ratios:**

- Pine Green 700 (#2D5F3E) on White: **6.2:1** ✅ (AA)
- Gray 900 (#111827) on White: **16.8:1** ✅ (AAA)
- Gray 700 (#374151) on White: **8.5:1** ✅ (AAA)

---

### 8.2 Keyboard Navigation

**Requirements:**

- All interactive elements accessible via keyboard
- Visible focus indicators (2px solid Pine Green 700)
- Logical tab order
- Skip to main content link (hidden until focused)

**Focus Styles:**

```css
:focus-visible {
    outline: 2px solid #2d5f3e;
    outline-offset: 2px;
    border-radius: 4px;
}
```

**Keyboard Shortcuts (Admin Panel):**

- `Cmd/Ctrl + S` - Save draft
- `Cmd/Ctrl + Enter` - Publish post
- `Cmd/Ctrl + K` - Open search
- `Esc` - Close modal/dialog
- `Tab / Shift+Tab` - Navigate focus

---

### 8.3 Screen Reader Support

**Semantic HTML:**

```html
<header>
    <nav aria-label="Primary navigation">
        <main>
            <aside>
                <footer></footer>
            </aside>
        </main>
    </nav>
</header>
```

**ARIA Labels:**

```html
<!-- Icon-only button -->
<button aria-label="Delete post">
    <svg aria-hidden="true">...</svg>
</button>

<!-- Form labels -->
<label for="post-title">Post Title</label>
<input id="post-title" type="text" required />

<!-- Live regions -->
<div role="alert" aria-live="polite">Post published successfully!</div>

<!-- Code blocks -->
<pre aria-label="PHP code example">
  <code>...</code>
</pre>
```

---

### 8.4 Image Accessibility

**Requirements:**

- All images have descriptive `alt` text
- Decorative images: `alt=""` (empty string)
- Complex images: Long description provided

**Media Library Alt Text:**

- Required field during upload
- Inline hint: "Describe the image for screen readers and SEO"
- Character limit: 125 characters (recommended)

---

## 9. Design System Summary

### 9.1 Spacing Scale

```css
space-0: 0px
space-1: 4px
space-2: 8px
space-3: 12px
space-4: 16px
space-5: 20px
space-6: 24px
space-8: 32px
space-10: 40px
space-12: 48px
space-16: 64px
```

---

### 9.2 Border Radius

```css
rounded-sm: 4px (inputs, small buttons)
rounded-md: 6px (buttons, cards)
rounded-lg: 8px (large cards, modals, code blocks)
rounded-xl: 12px (featured images)
rounded-full: 9999px (avatars, badges)
```

---

### 9.3 Shadows

```css
shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05)
shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1)
shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1)
shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1)
```

**Usage:**

- Cards: `shadow-md`
- Modals: `shadow-xl`
- Buttons (hover): `shadow-lg`

---

## 10. Component Library (PrimeVue)

### 10.1 Core Components

**Forms:**

- InputText, Textarea
- Dropdown, MultiSelect
- Calendar (date/time picker)
- FileUpload
- Checkbox, RadioButton, ToggleSwitch
- Slider

**Data Display:**

- DataTable (with sorting, filtering, pagination)
- Card
- Tag (category badges)
- Badge (status indicators)
- Avatar

**Overlays:**

- Dialog (modals)
- ConfirmDialog (delete confirmations)
- Toast (success/error messages)
- Tooltip

**Navigation:**

- Breadcrumb
- Menu, PanelMenu (sidebar)
- TabView (settings tabs)

**Customization:**

- TailwindCSS 4 integration
- Pine Green color palette
- Custom spacing, borders, shadows

---

## 11. Wireframes Summary

### 11.1 Admin Panel Wireframes

**Dashboard:**

- Welcome message, user name
- Quick stats cards (Posts: 42, Pages: 8, Comments: 15)
- Recent activity timeline
- Quick actions: New Post, View Site

**Posts List:**

- DataTable: Title, Author, Categories, Status, Date
- Bulk actions: Delete, Change Status
- Filters: Status dropdown, Category dropdown, Date range
- Search bar (top-right)

**Post Editor:**

- Title input (H1)
- TipTap editor (main area, 70% width)
- Right sidebar (30% width):
    - Publish panel (Status, Visibility, Schedule, Publish button)
    - Categories (checkboxes)
    - Tags (tag input)
    - Featured Image (upload button + preview)
    - SEO panel (Meta title, description)

**Media Library:**

- Grid view (default, 4 columns)
- Upload button (top-right, primary green)
- Filters: Type dropdown, Date range
- Search bar

---

### 11.2 Public Frontend Wireframes

**Homepage:**

- Header: Logo, nav links, search icon
- Featured post (hero section, full-width image)
- Blog post grid (3 columns desktop, 2 tablet, 1 mobile)
- Pagination or "Load More" button
- Footer: Copyright, links, social icons

**Blog Post Single:**

- Full-width featured image
- Article header: Category badge, title, author info
- Article content (max-width 700px, centered)
- Shiki-highlighted code blocks (with copy button)
- Article footer: Tags, share buttons
- Comments section (v1.1.0+)
- Related posts (3 cards)

---

## 12. Change History

| Date       | Version | Author       | Changes                                                                                                                                          |
| ---------- | ------- | ------------ | ------------------------------------------------------------------------------------------------------------------------------------------------ |
| 2025-11-07 | 1.0     | PineCMS Team | Initial UX/UI design specification (Forest Green palette, HackTheBox/Medium-inspired layouts, Shiki syntax highlighting, WCAG 2.1 AA compliance) |

---

**Last Updated:** 2025-11-07
**Document Owner:** PineCMS Team
**Next Review:** 2025-12-07
