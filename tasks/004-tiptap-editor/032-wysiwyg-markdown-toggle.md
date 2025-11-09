---
task_id: 032
epic: 004-tiptap-editor
title: WYSIWYG ↔ Markdown Toggle
status: pending
priority: medium
estimated_effort: 5 hours
actual_effort: null
assignee: frontend-developer
dependencies: [027, 028, 031]
tags: [frontend, tiptap, markdown, week-7]
---

# Task 032: WYSIWYG ↔ Markdown Toggle

## 📋 Overview

Build toggle switch to convert between WYSIWYG and Markdown editing modes without content loss. Uses TipTap Markdown extension for bidirectional conversion.

## 🎯 Acceptance Criteria

- [ ] Toggle switch in toolbar
- [ ] WYSIWYG → Markdown conversion
- [ ] Markdown → WYSIWYG conversion
- [ ] No content loss on toggle
- [ ] Preserve formatting
- [ ] Comprehensive tests

## 🏗️ Implementation Steps

**Install**: `npm install @tiptap/extension-markdown tiptap-markdown`

**Component**: `resources/js/Components/Editor/ModeToggle.vue`

**Conversion**:

- WYSIWYG → Markdown: `editor.storage.markdown.getMarkdown()`
- Markdown → WYSIWYG: `editor.commands.setContent(markdown)`

**State Management**: Store current mode in component state

## 🧪 Testing

- Test toggle preserves content
- Test formatting conversion
- Test edge cases (tables, nested lists)

## 📚 Documentation

**PRD**: Section 3.1.6
**Related**: 031-markdown-shortcuts → 033-live-preview

## ✅ Verification

```bash
npm run dev
# Write formatted content
# Toggle to Markdown → View markdown
# Toggle back → Formatting preserved
npm run quality
```
