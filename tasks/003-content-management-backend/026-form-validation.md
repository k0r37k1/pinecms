---
task_id: 026
epic: 003-content-management-backend
title: Form Request Validation
status: pending
priority: critical
estimated_effort: 5 hours
actual_effort: null
assignee: backend-architect
dependencies: [015, 016, 025]
tags: [backend, validation, security, week-5]
---

# Task 026: Form Request Validation

## 📋 Overview

Create comprehensive Form Request classes for post and page validation with rules, custom error messages, and authorization. Includes validation for all fields (title, slug, content, status, dates, custom fields) with XSS prevention and input sanitization.

## 🎯 Acceptance Criteria

- [ ] StorePostRequest with validation rules
- [ ] UpdatePostRequest with validation rules
- [ ] StorePageRequest with validation rules
- [ ] UpdatePageRequest with validation rules
- [ ] Custom error messages
- [ ] Authorization in authorize() method
- [ ] Input sanitization (strip tags from title)
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create StorePostRequest

**File**: `app/Http/Requests/StorePostRequest.php`

**Validation Rules**:
```php
public function rules(): array
{
    return [
        'title' => ['required', 'string', 'max:255'],
        'slug' => ['nullable', 'string', 'max:200', 'regex:/^[a-z0-9-]+$/', 'unique:posts,slug'],
        'excerpt' => ['nullable', 'string', 'max:500'],
        'content' => ['required', 'string'],
        'status' => ['required', 'in:draft,published,archived'],
        'category_id' => ['nullable', 'exists:categories,id'],
        'tags' => ['nullable', 'array'],
        'tags.*' => ['exists:tags,id'],
        'featured_image' => ['nullable', 'string'],
        'publish_at' => ['nullable', 'date', 'after:now'],
        'unpublish_at' => ['nullable', 'date', 'after:publish_at'],
        'metadata' => ['nullable', 'array'],
    ];
}
```

**Authorization**:
```php
public function authorize(): bool
{
    return $this->user()->can('create', Post::class);
}
```

**Custom Messages**:
```php
public function messages(): array
{
    return [
        'title.required' => 'Post title is required',
        'title.max' => 'Post title cannot exceed 255 characters',
        'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens',
        'slug.unique' => 'This slug is already in use',
        'content.required' => 'Post content is required',
        'status.in' => 'Status must be draft, published, or archived',
        'publish_at.after' => 'Publish date must be in the future',
        'unpublish_at.after' => 'Unpublish date must be after publish date',
    ];
}
```

### Step 2: Create UpdatePostRequest

**File**: `app/Http/Requests/UpdatePostRequest.php`

**Differences from Store**:
- Slug unique rule excludes current post: `unique:posts,slug,{$this->post->id}`
- lock_version required for concurrent editing: `required|integer`
- All fields nullable except lock_version

**Validation Rules**:
```php
public function rules(): array
{
    return [
        'title' => ['sometimes', 'required', 'string', 'max:255'],
        'slug' => ['sometimes', 'string', 'max:200', 'regex:/^[a-z0-9-]+$/', 'unique:posts,slug,' . $this->post->id],
        'excerpt' => ['nullable', 'string', 'max:500'],
        'content' => ['sometimes', 'required', 'string'],
        'status' => ['sometimes', 'required', 'in:draft,published,archived'],
        'category_id' => ['nullable', 'exists:categories,id'],
        'tags' => ['nullable', 'array'],
        'tags.*' => ['exists:tags,id'],
        'featured_image' => ['nullable', 'string'],
        'publish_at' => ['nullable', 'date'],
        'unpublish_at' => ['nullable', 'date', 'after:publish_at'],
        'metadata' => ['nullable', 'array'],
        'lock_version' => ['required', 'integer'],
    ];
}
```

### Step 3: Create StorePageRequest

**File**: `app/Http/Requests/StorePageRequest.php`

**Additional Fields**:
- parent_id (nullable, exists:pages,id)
- template (required, in:default,full-width,sidebar)
- order (nullable, integer)

**Validation Rules**:
```php
public function rules(): array
{
    return [
        'title' => ['required', 'string', 'max:255'],
        'slug' => ['nullable', 'string', 'max:200', 'regex:/^[a-z0-9-]+$/', Rule::unique('pages')->where(function ($query) {
            return $query->where('parent_id', $this->parent_id);
        })],
        'content' => ['required', 'string'],
        'status' => ['required', 'in:draft,published,archived'],
        'parent_id' => ['nullable', 'exists:pages,id', new NotSelfParent()],
        'template' => ['required', 'in:default,full-width,sidebar'],
        'order' => ['nullable', 'integer', 'min:0'],
        'metadata' => ['nullable', 'array'],
    ];
}
```

### Step 4: Create Custom Validation Rules

**File**: `app/Rules/NotSelfParent.php`

**Purpose**: Prevent page from being its own parent (circular reference)

**Implementation**:
```php
public function passes($attribute, $value)
{
    $pageId = request()->route('page')?->id;

    if (!$pageId) {
        return true; // Creating new page
    }

    return $value !== $pageId;
}
```

### Step 5: Input Sanitization

**Middleware**: `app/Http/Middleware/SanitizeInput.php`

**Sanitization**:
- Strip tags from title (allow only plain text)
- Allow HTML in content (TipTap editor handles this)
- Sanitize slug (already validated by regex)

**Implementation**:
```php
protected function sanitize($input)
{
    if (is_array($input)) {
        return array_map([$this, 'sanitize'], $input);
    }

    return is_string($input) ? strip_tags($input) : $input;
}
```

### Step 6: Create UpdatePageRequest

**File**: `app/Http/Requests/UpdatePageRequest.php`

**Similar to UpdatePostRequest** but includes:
- Parent-child validation
- Template validation
- Order validation

## 🧪 Testing Requirements

**Unit Tests**:
- `tests/Unit/Requests/StorePostRequestTest.php`
  - Test required fields
  - Test max lengths
  - Test slug format validation
  - Test unique slug
  - Test status enum
  - Test date validations

- `tests/Unit/Requests/UpdatePostRequestTest.php`
  - Test lock_version required
  - Test unique slug excludes current post

**Feature Tests**:
- `tests/Feature/Validation/PostValidationTest.php`
  - Test store validation errors returned
  - Test update validation errors returned
  - Test custom messages displayed
  - Test authorization checked

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.12 (Validation)
- **Timeline**: Week 5 (v1.0.0)

**Architecture:**
- **Pattern**: Laravel Form Requests
- **Security**: Input sanitization, XSS prevention
- **Authorization**: Policies integrated

**Quality Requirements:**
- **Security**: All inputs validated
- **UX**: Clear error messages
- **Testing**: > 80% coverage

**Related Tasks:**
- **Previous**: 025-post-page-controllers
- **Completes**: Epic 003 (Content Management Backend)
- **Blocks**: Epic 004 (TipTap Editor)

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing
- [ ] Unit tests passing (20+ test cases)
- [ ] Feature tests passing (8+ scenarios)
- [ ] All validation rules tested

### Security
- [ ] Input sanitization implemented
- [ ] XSS prevention (strip tags)
- [ ] SQL injection prevented (Laravel ORM)
- [ ] Authorization checked

### Documentation
- [ ] Validation rules documented
- [ ] Custom messages documented
- [ ] Error responses documented

## ✅ Verification Steps

```bash
# Test validation errors
php artisan tinker
>>> $request = new \App\Http\Requests\StorePostRequest();
>>> $validator = Validator::make([], $request->rules());
>>> $validator->fails(); // Should be true
>>> $validator->errors()->all();

# Test via API
curl -X POST http://localhost:8000/api/admin/posts \
  -H "Content-Type: application/json" \
  -d '{}'
# Should return 422 with validation errors

# Quality check
composer quality
```
