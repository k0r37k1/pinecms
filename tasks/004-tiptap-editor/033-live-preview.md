---
task_id: 033
epic: 004-tiptap-editor
title: Live Preview and Device Switcher
status: pending
priority: medium
estimated_effort: 7 hours
actual_effort: null
assignee: frontend-developer
dependencies: [027, 032]
tags: [frontend, tiptap, preview, week-7]
---

# Task 033: Live Preview and Device Switcher

## 📋 Overview

Create split view with editor on left and live HTML preview on right. Add device switcher (mobile, tablet, desktop) to test responsive layouts.

## 🎯 Acceptance Criteria

- [ ] Split view toggle (editor only | split | preview only)
- [ ] Live preview renders HTML
- [ ] Device switcher (mobile 375px, tablet 768px, desktop 1200px)
- [ ] Preview updates on content change
- [ ] Responsive iframe wrapper
- [ ] Comprehensive tests

## 🏗️ Implementation Steps

**Component**: `resources/js/Components/Editor/LivePreview.vue`

**Layout**:

- Editor: 50% width (split mode)
- Preview: 50% width (split mode)
- Resizable divider

**Device Widths**:

- Mobile: 375px
- Tablet: 768px
- Desktop: 1200px
- Full-width: 100%

**Preview Rendering**: Use iframe with sanitized HTML or Vue component

## 🧪 Testing

- Test split view layout
- Test device switcher
- Test preview updates
- Test responsive widths

## 📚 Documentation

**PRD**: Section 3.1.7
**Related**: 032-wysiwyg-markdown-toggle → 034-auto-save-integration

## ✅ Verification

```bash
npm run dev
# Enable split view
# Type content → Preview updates
# Switch device → Width changes
npm run quality
```
