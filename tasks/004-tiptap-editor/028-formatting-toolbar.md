---
task_id: 028
epic: 004-tiptap-editor
title: Basic Formatting Toolbar
status: pending
priority: critical
estimated_effort: 6 hours
actual_effort: null
assignee: frontend-developer
dependencies: [027]
tags: [frontend, tiptap, toolbar, week-6]
---

# Task 028: Basic Formatting Toolbar

## 📋 Overview

Build comprehensive formatting toolbar for TipTap editor with buttons for bold, italic, underline, strikethrough, headings (H1-H6), lists (ordered, unordered, task lists), blockquote, horizontal rule, and undo/redo.

## 🎯 Acceptance Criteria

- [ ] EditorToolbar component created
- [ ] Bold, italic, underline, strikethrough buttons
- [ ] Heading dropdown (H1-H6)
- [ ] List buttons (ordered, unordered, task)
- [ ] Blockquote and horizontal rule buttons
- [ ] Undo/redo buttons with disabled state
- [ ] Active state highlighting for current formatting
- [ ] Keyboard shortcuts (Cmd+B, Cmd+I, etc.)
- [ ] Tooltips on hover
- [ ] Comprehensive unit tests

## 🏗️ Implementation Steps

### Step 1: Create EditorToolbar Component

**File**: `resources/js/Components/Editor/EditorToolbar.vue`

**Toolbar Buttons**:
- Bold (Cmd+B)
- Italic (Cmd+I)
- Underline (Cmd+U)
- Strikethrough (Cmd+Shift+X)
- Heading (dropdown: H1, H2, H3, H4, H5, H6)
- Ordered List (Cmd+Shift+7)
- Unordered List (Cmd+Shift+8)
- Task List
- Blockquote (Cmd+Shift+B)
- Horizontal Rule
- Undo (Cmd+Z)
- Redo (Cmd+Shift+Z)

**Implementation**:
```vue
<script setup lang="ts">
import { Editor } from '@tiptap/core';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';

interface Props {
  editor: Editor | null;
}

const props = defineProps<Props>();

const headingLevels = [
  { label: 'Paragraph', value: 0 },
  { label: 'Heading 1', value: 1 },
  { label: 'Heading 2', value: 2 },
  { label: 'Heading 3', value: 3 },
];

const setHeading = (level: number) => {
  if (level === 0) {
    props.editor?.chain().focus().setParagraph().run();
  } else {
    props.editor?.chain().focus().toggleHeading({ level }).run();
  }
};
</script>

<template>
  <div class="editor-toolbar flex items-center gap-2 p-2 border-b border-gray-300 dark:border-gray-700">
    <Button
      icon="pi pi-bold"
      @click="editor?.chain().focus().toggleBold().run()"
      :class="{ 'p-button-primary': editor?.isActive('bold') }"
      v-tooltip="'Bold (Cmd+B)'"
    />
    <Button
      icon="pi pi-italic"
      @click="editor?.chain().focus().toggleItalic().run()"
      :class="{ 'p-button-primary': editor?.isActive('italic') }"
      v-tooltip="'Italic (Cmd+I)'"
    />
    <!-- More buttons... -->
  </div>
</template>
```

### Step 2: Install Required Extensions

**Extensions**:
- @tiptap/extension-underline
- @tiptap/extension-task-list
- @tiptap/extension-task-item

### Step 3: Active State Highlighting

Use `editor.isActive('bold')` to highlight active formatting buttons.

### Step 4: Keyboard Shortcuts

Configured in extension options (StarterKit handles most shortcuts).

### Step 5: Integration

Add toolbar to TipTapEditor component above EditorContent.

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/vitest/components/EditorToolbar.spec.ts`
  - Test bold button toggles bold
  - Test italic button toggles italic
  - Test heading dropdown changes heading level
  - Test list buttons create lists
  - Test active state highlights current format
  - Test undo/redo buttons disabled when unavailable

## 📚 Related Documentation

**PRD**: `docs/prd/05-CORE-FEATURES.md` Section 3.1.2
**Timeline**: Week 6 (v1.0.0)

**Related Tasks**:
- **Previous**: 027-tiptap-setup
- **Next**: 029-link-image-insertion

## ✅ Quality Gates

### Code Quality
- [ ] ESLint passes
- [ ] TypeScript types defined

### Testing
- [ ] Unit tests passing (8+ cases)

### UX
- [ ] Tooltips clear
- [ ] Active states visible
- [ ] Keyboard shortcuts work

## ✅ Verification

```bash
npm run dev
# Click bold button → text becomes bold
# Click italic → text becomes italic
# Test keyboard shortcuts (Cmd+B, Cmd+I)
npm run quality
```
