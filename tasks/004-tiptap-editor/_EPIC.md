---
epic_id: 004
title: TipTap Editor Integration
status: pending
priority: critical
estimated_duration: 1.5 weeks
actual_duration: null
week: Week 6-7
version: v1.0.0
tasks_total: 8
tasks_completed: 0
---

# Epic 004: TipTap Editor Integration

## 🎯 Epic Goals

- Integrate TipTap WYSIWYG editor for post and page content
- Build comprehensive formatting toolbar (bold, italic, headings, lists)
- Support media insertion (images, links)
- Add code blocks with syntax highlighting
- Implement markdown shortcuts and slash commands
- Create WYSIWYG ↔ Markdown toggle
- Build live preview with device switcher
- Integrate auto-save functionality

## 📦 Features Delivered

- TipTap Vue component with Composition API
- Basic formatting toolbar (bold, italic, underline, strikethrough)
- Heading levels (H1-H6)
- Lists (ordered, unordered, task lists)
- Blockquote and horizontal rule
- Link insertion and editing
- Image insertion from media library
- Code block with syntax highlighting (Prism.js)
- Inline code formatting
- Markdown shortcuts (typing `#` for heading, `*` for list, etc.)
- Slash commands (`/heading`, `/list`, `/code`, etc.)
- WYSIWYG ↔ Markdown mode switcher
- Live preview (split view: editor | preview)
- Device switcher (mobile, tablet, desktop)
- Auto-save integration with debounce
- Character and word count
- Fullscreen mode

## 📋 Tasks (8 total)

- [ ] 027 - TipTap Vue Component Setup (6h)
- [ ] 028 - Basic Formatting Toolbar (6h)
- [ ] 029 - Link and Image Insertion (7h)
- [ ] 030 - Code Block with Syntax Highlighting (5h)
- [ ] 031 - Markdown Shortcuts and Slash Commands (6h)
- [ ] 032 - WYSIWYG ↔ Markdown Toggle (5h)
- [ ] 033 - Live Preview and Device Switcher (7h)
- [ ] 034 - Editor Auto-Save Integration (4h)

## 🔗 Dependencies

**Blocks:**

- Epic 009 (Admin Panel) - Editor used in admin UI

**Requires:**

- Epic 003 (Content Management Backend) - Content CRUD and auto-save service

## ✅ Epic Success Criteria

- [ ] TipTap editor renders in post/page create/edit forms
- [ ] Basic formatting (bold, italic, headings, lists) works
- [ ] Links and images can be inserted and edited
- [ ] Code blocks render with syntax highlighting
- [ ] Markdown shortcuts work (type `#` for heading, etc.)
- [ ] Slash commands menu appears on typing `/`
- [ ] WYSIWYG ↔ Markdown toggle preserves content
- [ ] Live preview shows rendered HTML
- [ ] Device switcher changes preview width
- [ ] Auto-save triggers every 60 seconds (configurable)
- [ ] Character and word count displays accurately
- [ ] Fullscreen mode expands editor
- [ ] All 8 tasks completed with tests passing
- [ ] Quality gates passing (ESLint, TypeScript, Vitest)

## 📚 References

**PRD Specifications:**

- Feature: `docs/prd/05-CORE-FEATURES.md` Section 3.1 (TipTap Editor)
- Timeline: Week 6-7 (v1.0.0)

**Architecture:**

- Component: Vue 3.5 Composition API
- Library: TipTap Editor (v2.x)
- Syntax: Prism.js for code highlighting
- Markdown: TipTap Markdown extension

**Quality Requirements:**

- Performance: Editor load < 500ms
- Responsiveness: Works on mobile/tablet/desktop
- Accessibility: Keyboard shortcuts, ARIA labels
- Testing: > 80% coverage for composables and components
- UX: Intuitive toolbar, clear icons, helpful tooltips

**Acceptance Criteria:**

- No content loss on mode switching
- Paste from Word/Google Docs sanitized
- Image upload integrated with media library
- Markdown shortcuts discoverable via tooltip
- Slash command menu keyboard navigable
