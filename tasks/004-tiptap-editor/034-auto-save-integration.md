---
task_id: 034
epic: 004-tiptap-editor
title: Editor Auto-Save Integration
status: pending
priority: high
estimated_effort: 4 hours
actual_effort: null
assignee: fullstack-developer
dependencies: [019, 027]
tags: [frontend, backend, auto-save, week-7]
---

# Task 034: Editor Auto-Save Integration

## 📋 Overview

Integrate TipTap editor with auto-save service from Task 019. Debounce content changes, show save status, and restore unsaved content on page reload.

## 🎯 Acceptance Criteria

- [ ] Auto-save composable integrated
- [ ] Debounced saving (60s configurable)
- [ ] Save status indicator
- [ ] Restore unsaved content on reload
- [ ] Disable during manual save
- [ ] Comprehensive tests

## 🏗️ Implementation Steps

**Composable**: `resources/js/composables/useAutoSave.ts` (from Task 019)

**Integration**:
```vue
<script setup>
import { useAutoSave } from '@/composables/useAutoSave';

const { isSaving, lastSaved, error } = useAutoSave(
  props.post?.id,
  'post'
);

watch(() => editor.value?.getHTML(), (content) => {
  form.content = content;
});
</script>
```

**Indicator**: Show "Saving...", "Saved at HH:MM:SS", or "Error saving"

## 🧪 Testing

- Test auto-save triggers
- Test debounce works
- Test restore on reload
- Test error handling

## 📚 Documentation

**PRD**: Section 3.1.8
**Related**: 019-auto-save → Completes Epic 004

## ✅ Verification

```bash
npm run dev
# Type content
# Wait 60s
# Verify "Saved at HH:MM:SS"
# Reload page
# Verify content restored
npm run quality
```
