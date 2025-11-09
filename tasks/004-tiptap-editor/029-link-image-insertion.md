---
task_id: 029
epic: 004-tiptap-editor
title: Link and Image Insertion
status: pending
priority: critical
estimated_effort: 7 hours
actual_effort: null
assignee: fullstack-developer
dependencies: [027, 028]
tags: [frontend, backend, tiptap, media, week-6]
---

# Task 029: Link and Image Insertion

## 📋 Overview

Implement link insertion modal with URL validation and image insertion integrated with media library. Supports editing existing links/images, alt text, and responsive image sizes.

## 🎯 Acceptance Criteria

- [ ] Link insertion modal with URL and text inputs
- [ ] Link editing (click existing link)
- [ ] Link removal
- [ ] Image insertion from media library
- [ ] Image alt text editor
- [ ] Responsive image sizing
- [ ] Image alignment (left, center, right)
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Install TipTap Extensions

```bash
npm install @tiptap/extension-link @tiptap/extension-image
```

### Step 2: Create LinkModal Component

**File**: `resources/js/Components/Editor/LinkModal.vue`

**Fields**:
- URL (required, URL validation)
- Text (required)
- Open in new tab (checkbox)

**Buttons**:
- Insert/Update
- Cancel
- Remove (if editing existing link)

### Step 3: Create ImagePicker Component

**File**: `resources/js/Components/Editor/ImagePicker.vue`

**Features**:
- Media library integration
- Upload new image
- Alt text input
- Size selection (thumbnail, medium, large, original)
- Alignment (left, center, right, full-width)

### Step 4: Add to Toolbar

**Buttons**:
- Link button → Opens LinkModal
- Image button → Opens ImagePicker

### Step 5: Link Extension Configuration

```typescript
Link.configure({
  openOnClick: false,
  HTMLAttributes: {
    class: 'text-primary-600 hover:underline',
  },
})
```

### Step 6: Image Extension Configuration

```typescript
Image.configure({
  HTMLAttributes: {
    class: 'max-w-full h-auto',
  },
})
```

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/vitest/components/LinkModal.spec.ts`
  - Test URL validation
  - Test insert link
  - Test edit link
  - Test remove link

**Feature Tests**:
- `tests/Feature/Editor/MediaIntegrationTest.php`
  - Test image inserted from media library
  - Test image URL correct
  - Test alt text saved

## 📚 Related Documentation

**PRD**: `docs/prd/05-CORE-FEATURES.md` Section 3.1.3
**Timeline**: Week 6 (v1.0.0)

**Related Tasks**:
- **Previous**: 028-formatting-toolbar
- **Next**: 030-code-block
- **Depends On**: Epic 005 (Media Library)

## ✅ Quality Gates

### Code Quality
- [ ] ESLint passes
- [ ] TypeScript types defined

### Testing
- [ ] Unit tests passing (6+ cases)
- [ ] Feature tests passing (3+ cases)

### UX
- [ ] Link modal intuitive
- [ ] Image picker responsive
- [ ] Validation clear

## ✅ Verification

```bash
# Test link insertion
npm run dev
# Click link button
# Enter URL and text
# Click insert
# Verify link created

# Test image insertion
# Click image button
# Select image from media library
# Verify image inserted
npm run quality
```
