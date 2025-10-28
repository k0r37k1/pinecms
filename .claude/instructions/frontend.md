# Frontend Development Instructions

## Vue 3.5 Composition API

- Use Composition API (`<script setup>`) for all Vue components
- Single root element per component required
- Use `router.visit()` or `<Link>` for navigation instead of traditional links

### Inertia.js 2.x Integration

**Navigation Example:**
```vue
import { Link } from '@inertiajs/vue3'
<Link href="/">Home</Link>
```

**Forms with `<Form>` Component:**
```vue
<Form
    action="/users"
    method="post"
    #default="{
        errors,
        hasErrors,
        processing,
        progress,
        wasSuccessful,
        recentlySuccessful,
        setError,
        clearErrors,
        resetAndClearErrors,
        defaults,
        isDirty,
        reset,
        submit,
    }"
>
    <input type="text" name="name" />

    <div v-if="errors.name">
        {{ errors.name }}
    </div>

    <button type="submit" :disabled="processing">
        {{ processing ? 'Creating...' : 'Create User' }}
    </button>

    <div v-if="wasSuccessful">User created successfully!</div>
</Form>
```

### Inertia v2 Features

**Use these modern features:**
- Polling
- Prefetching
- Deferred props
- Infinite scrolling using merging props and `WhenVisible`
- Lazy loading data on scroll

**Deferred Props & Empty States:**
When using deferred props, add animated skeleton/pulsing empty states.

### Alpine.js Integration

**Public Pages:**
- Use Alpine.js for interactive components on public-facing pages
- Keep Alpine components simple and focused
- Prefer Blade + Alpine for public pages over Vue

## TailwindCSS 4.1

### Core Principles

- Use TailwindCSS utility classes exclusively (NO inline styles)
- Check existing components for patterns before creating new ones
- Use `search-docs` tool for official Tailwind documentation

### Tailwind v4 Specific Rules

**Import Statement:**
```css
/* ✅ Correct - Tailwind v4 */
@import "tailwindcss";

/* ❌ Wrong - Tailwind v3 deprecated */
@tailwind base;
@tailwind components;
@tailwind utilities;
```

**Replaced Utilities:**
| Deprecated | Replacement |
|------------|-------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |

**Note:** Opacity values remain numeric.

### Spacing Best Practices

**Use gap utilities for spacing, NOT margins:**
```html
<!-- ✅ Good - Flex Gap Spacing -->
<div class="flex gap-8">
    <div>Superior</div>
    <div>Michigan</div>
    <div>Erie</div>
</div>

<!-- ❌ Bad - Margin spacing -->
<div class="flex">
    <div class="mr-8">Item 1</div>
    <div class="mr-8">Item 2</div>
</div>
```

### Dark Mode

- If existing pages/components support dark mode, new ones MUST too
- Use `dark:` prefix for dark mode variants
- Check sibling components for dark mode patterns

### Component Extraction

- Extract repeated patterns into reusable components
- Match project conventions (Blade, Vue, etc.)
- Think through class placement, order, priority
- Remove redundant classes
- Group elements logically

## PrimeVue Components

**Admin Panel Only:**
- Use PrimeVue components for admin panel UI
- Use `context7` MCP tool for PrimeVue documentation
- Follow PrimeVue theming and customization guidelines

## Code Standards

- ES modules (import/export) - NO CommonJS `require()`
- Remove `console.log()` before committing
- Use TypeScript types for props and emits
- Follow existing naming conventions in sibling components
