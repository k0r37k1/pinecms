---
task_id: 027
epic: 004-tiptap-editor
title: TipTap Vue Component Setup
status: pending
priority: critical
estimated_effort: 6 hours
actual_effort: null
assignee: frontend-developer
dependencies: [025, 026]
tags: [frontend, vue, tiptap, week-6]
---

# Task 027: TipTap Vue Component Setup

## 📋 Overview

Set up TipTap editor as a reusable Vue component with Composition API, PrimeVue integration, and proper TypeScript types. Includes base configuration, extensions loading, and composable for editor state management.

## 🎯 Acceptance Criteria

- [ ] TipTap Vue component created
- [ ] Composition API with `<script setup>`
- [ ] TypeScript prop definitions
- [ ] useEditor composable for state management
- [ ] Base extensions loaded (StarterKit)
- [ ] Props for v-model binding
- [ ] Emit events for content changes
- [ ] Comprehensive unit tests (Vitest)

## 🏗️ Implementation Steps

### Step 1: Install TipTap Dependencies

**Command**:
```bash
npm install @tiptap/vue-3 @tiptap/starter-kit @tiptap/pm
npm install --save-dev @tiptap/core
```

### Step 2: Create TipTapEditor Component

**File**: `resources/js/Components/Editor/TipTapEditor.vue`

**Implementation**:
```vue
<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import { watch } from 'vue';

interface Props {
  modelValue: string;
  placeholder?: string;
  autofocus?: boolean;
  editable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Start writing...',
  autofocus: false,
  editable: true,
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
  (e: 'change', value: string): void;
}>();

const editor = useEditor({
  extensions: [
    StarterKit.configure({
      history: false, // We'll add custom history
    }),
  ],
  content: props.modelValue,
  editable: props.editable,
  autofocus: props.autofocus,
  editorProps: {
    attributes: {
      class: 'prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none',
    },
  },
  onUpdate: ({ editor }) => {
    const html = editor.getHTML();
    emit('update:modelValue', html);
    emit('change', html);
  },
});

watch(() => props.modelValue, (value) => {
  if (editor.value && editor.value.getHTML() !== value) {
    editor.value.commands.setContent(value, false);
  }
});

watch(() => props.editable, (value) => {
  if (editor.value) {
    editor.value.setEditable(value);
  }
});

onBeforeUnmount(() => {
  editor.value?.destroy();
});
</script>

<template>
  <div class="tiptap-editor">
    <EditorContent :editor="editor" />
  </div>
</template>

<style scoped>
.tiptap-editor {
  @apply border border-gray-300 dark:border-gray-700 rounded-lg p-4 min-h-[300px];
}
</style>
```

### Step 3: Create useEditor Composable

**File**: `resources/js/composables/useEditor.ts`

**Implementation**:
```typescript
import { ref, computed, Ref } from 'vue';
import { Editor } from '@tiptap/vue-3';

export function useEditorState(editor: Ref<Editor | null>) {
  const wordCount = computed(() => {
    if (!editor.value) return 0;
    return editor.value.storage.characterCount?.words() ?? 0;
  });

  const characterCount = computed(() => {
    if (!editor.value) return 0;
    return editor.value.storage.characterCount?.characters() ?? 0;
  });

  const canUndo = computed(() => editor.value?.can().undo() ?? false);
  const canRedo = computed(() => editor.value?.can().redo() ?? false);

  const undo = () => editor.value?.chain().focus().undo().run();
  const redo = () => editor.value?.chain().focus().redo().run();

  return {
    wordCount,
    characterCount,
    canUndo,
    canRedo,
    undo,
    redo,
  };
}
```

### Step 4: Add to Post/Page Edit Forms

**File**: `resources/js/Pages/Admin/Posts/Create.vue`

**Usage**:
```vue
<script setup lang="ts">
import TipTapEditor from '@/Components/Editor/TipTapEditor.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  title: '',
  content: '',
  status: 'draft',
});
</script>

<template>
  <form @submit.prevent="form.post(route('admin.posts.store'))">
    <TipTapEditor
      v-model="form.content"
      placeholder="Start writing your post..."
      autofocus
    />
  </form>
</template>
```

### Step 5: Configure TailwindCSS Typography

**File**: `tailwind.config.js`

**Add Plugin**:
```javascript
export default {
  plugins: [
    require('@tailwindcss/typography'),
  ],
};
```

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/vitest/components/TipTapEditor.spec.ts`
  - Test component renders
  - Test v-model binding updates content
  - Test editable prop toggles editing
  - Test autofocus prop works
  - Test content change emits events
  - Test editor destroys on unmount

**Integration Tests**:
- `tests/vitest/composables/useEditor.spec.ts`
  - Test wordCount computed property
  - Test characterCount computed property
  - Test undo/redo availability

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 3.1.1 (Editor Setup)
- **Timeline**: Week 6 (v1.0.0)

**Architecture:**
- **Component**: Vue 3.5 Composition API
- **Library**: TipTap Editor v2.x
- **Styling**: TailwindCSS Typography

**Quality Requirements:**
- **Performance**: Editor load < 500ms
- **Accessibility**: ARIA labels, keyboard navigation
- **Testing**: > 80% coverage

**Related Tasks:**
- **Next**: 028-basic-formatting-toolbar
- **Blocks**: 033-live-preview
- **Depends On**: 025-post-page-controllers

## ✅ Quality Gates Checklist

### Code Quality
- [ ] ESLint passes
- [ ] TypeScript types defined
- [ ] Composition API best practices followed

### Testing
- [ ] Unit tests passing (6+ test cases)
- [ ] Edge cases covered (null editor, empty content)

### UX
- [ ] Editor renders correctly
- [ ] Focus management works
- [ ] Dark mode support

### Documentation
- [ ] Component props documented
- [ ] Composable usage documented
- [ ] Examples provided

## ✅ Verification Steps

```bash
# Install dependencies
npm install

# Test component
npm run dev
# Visit http://localhost:5173/admin/posts/create
# Verify editor renders
# Type content
# Verify v-model updates

# Run tests
npm run test

# Quality check
npm run quality
```
