---
task_id: 030
epic: 004-tiptap-editor
title: Code Block with Syntax Highlighting
status: pending
priority: medium
estimated_effort: 5 hours
actual_effort: null
assignee: frontend-developer
dependencies: [027, 028]
tags: [frontend, tiptap, syntax-highlighting, week-6]
---

# Task 030: Code Block with Syntax Highlighting

## 📋 Overview

Add code block support with Prism.js syntax highlighting for 20+ languages. Includes language selector, line numbers, and copy-to-clipboard button.

## 🎯 Acceptance Criteria

- [ ] Code block extension configured
- [ ] Prism.js syntax highlighting (20+ languages)
- [ ] Language selector dropdown
- [ ] Line numbers optional
- [ ] Copy to clipboard button
- [ ] Inline code support
- [ ] Comprehensive tests

## 🏗️ Implementation Steps

**Install**: `npm install @tiptap/extension-code-block-lowlight lowlight`

**Supported Languages**: JavaScript, TypeScript, Python, PHP, Ruby, Go, Rust, Java, C++, SQL, Bash, HTML, CSS, JSON, YAML, Markdown

**Component**: `resources/js/Components/Editor/CodeBlockNode.vue`

**Toolbar**: Add code block button with language selector

## 🧪 Testing

- Test code block insertion
- Test syntax highlighting
- Test language switcher
- Test copy to clipboard

## 📚 Documentation

**PRD**: Section 3.1.4
**Related**: 028-formatting-toolbar → 031-markdown-shortcuts

## ✅ Verification

```bash
npm run dev
# Insert code block
# Select language (JavaScript)
# Verify syntax highlighting
npm run quality
```
