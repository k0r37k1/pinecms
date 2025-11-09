---
task_id: 031
epic: 004-tiptap-editor
title: Markdown Shortcuts and Slash Commands
status: pending
priority: medium
estimated_effort: 6 hours
actual_effort: null
assignee: frontend-developer
dependencies: [027, 028]
tags: [frontend, tiptap, markdown, week-6]
---

# Task 031: Markdown Shortcuts and Slash Commands

## 📋 Overview

Implement markdown shortcuts (typing `#` for heading, `*` for list) and slash command menu (`/heading`, `/list`, `/code`) for quick formatting.

## 🎯 Acceptance Criteria

- [ ] Markdown shortcuts (# for heading, * for list, ` for code)
- [ ] Slash command menu on typing /
- [ ] Keyboard navigation in menu
- [ ] Fuzzy search in slash menu
- [ ] Custom slash commands
- [ ] Comprehensive tests

## 🏗️ Implementation Steps

**Install**: `npm install @tiptap/extension-typography @tiptap/suggestion`

**Markdown Shortcuts**:
- `#` + space = H1
- `##` + space = H2
- `*` + space = Bullet list
- `1.` + space = Ordered list
- `>` + space = Blockquote
- ``` + language = Code block

**Slash Commands**: `/heading`, `/list`, `/code`, `/quote`, `/image`, `/link`

**Component**: `resources/js/Components/Editor/SlashMenu.vue`

## 🧪 Testing

- Test markdown shortcuts
- Test slash menu appears
- Test keyboard navigation
- Test command execution

## 📚 Documentation

**PRD**: Section 3.1.5
**Related**: 028-formatting-toolbar → 032-wysiwyg-markdown-toggle

## ✅ Verification

```bash
npm run dev
# Type `#` + space → Heading 1
# Type `/` → Slash menu appears
# Select command → Formatting applied
npm run quality
```
