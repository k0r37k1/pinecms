---
task_id: 025
epic: 003-content-management-backend
title: Post/Page Controllers
status: pending
priority: critical
estimated_effort: 8 hours
actual_effort: null
assignee: fullstack-developer
dependencies: [015, 016, 017, 018, 019, 020, 021, 022, 023, 024]
tags: [backend, frontend, controllers, inertia, week-5]
---

# Task 025: Post/Page Controllers

## 📋 Overview

Build Inertia.js-powered controllers for posts and pages admin CRUD with complete integration of all content management services (CRUD, revisions, scheduling, auto-save, slug management, custom fields, bulk actions, search).

## 🎯 Acceptance Criteria

- [ ] PostController with all CRUD methods
- [ ] PageController with all CRUD methods
- [ ] Inertia::render() for SSR pages
- [ ] API Resources for consistent data transformation
- [ ] Authorization via Policies
- [ ] Error handling and flash messages
- [ ] Integration with all content services
- [ ] Comprehensive feature tests

## 🏗️ Implementation Steps

### Step 1: Create PostController

**File**: `app/Http/Controllers/Admin/PostController.php`

**Methods**:
- index(): Response (list posts with filters)
- create(): Response (show create form)
- store(StorePostRequest $request): RedirectResponse
- show(Post $post): Response (view single post)
- edit(Post $post): Response (show edit form)
- update(UpdatePostRequest $request, Post $post): RedirectResponse
- destroy(Post $post): RedirectResponse

**Inertia Pages**:
- `Posts/Index.vue` - List with search, filters, bulk actions
- `Posts/Create.vue` - Create form with TipTap editor
- `Posts/Edit.vue` - Edit form with revisions, auto-save, concurrent editing
- `Posts/Show.vue` - Preview post

### Step 2: Index Method

**Implementation**:
```php
public function index(Request $request): Response
{
    $this->authorize('viewAny', Post::class);

    $posts = $this->searchService->search(
        $request->input('q', ''),
        $request->only(['status', 'author_id', 'category_id', 'tags', 'date_from', 'date_to', 'sort']),
        'post'
    );

    return Inertia::render('Posts/Index', [
        'posts' => PostResource::collection($posts),
        'filters' => $request->only(['q', 'status', 'author_id', 'category_id', 'tags', 'sort']),
        'authors' => UserResource::collection(User::all()),
        'categories' => CategoryResource::collection(Category::all()),
        'tags' => TagResource::collection(Tag::all()),
    ]);
}
```

### Step 3: Create Method

**Implementation**:
```php
public function create(): Response
{
    $this->authorize('create', Post::class);

    return Inertia::render('Posts/Create', [
        'categories' => CategoryResource::collection(Category::all()),
        'tags' => TagResource::collection(Tag::all()),
        'customFields' => $this->customFieldService->getFields('post'),
    ]);
}
```

### Step 4: Store Method

**Implementation**:
```php
public function store(StorePostRequest $request): RedirectResponse
{
    $this->authorize('create', Post::class);

    $post = $this->postService->createPost(
        $request->validated(),
        $request->input('content')
    );

    return redirect()
        ->route('admin.posts.edit', $post)
        ->with('success', 'Post created successfully');
}
```

### Step 5: Edit Method

**Implementation**:
```php
public function edit(Post $post): Response
{
    $this->authorize('update', $post);

    return Inertia::render('Posts/Edit', [
        'post' => new PostResource($post->load(['category', 'tags', 'author'])),
        'revisions' => RevisionResource::collection($this->revisionService->getRevisions($post)),
        'categories' => CategoryResource::collection(Category::all()),
        'tags' => TagResource::collection(Tag::all()),
        'customFields' => $this->customFieldService->getFields('post'),
        'lockInfo' => $this->concurrentEditingService->getLockInfo($post),
    ]);
}
```

### Step 6: Update Method

**Implementation**:
```php
public function update(UpdatePostRequest $request, Post $post): RedirectResponse
{
    $this->authorize('update', $post);

    try {
        $post = $this->postService->updatePost(
            $post,
            $request->validated(),
            $request->input('content')
        );

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', 'Post updated successfully');
    } catch (ConcurrentEditingException $e) {
        return redirect()
            ->back()
            ->withErrors(['concurrent_editing' => $e->getMessage()])
            ->with('conflict', $e->getData());
    }
}
```

### Step 7: Create PageController

**File**: `app/Http/Controllers/Admin/PageController.php`

**Similar Structure**: Same methods as PostController but with:
- Parent-child relationship management
- Template selection
- Hierarchical listing

### Step 8: Create API Resources

**Files**:
- `app/Http/Resources/PostResource.php`
- `app/Http/Resources/PageResource.php`
- `app/Http/Resources/RevisionResource.php`

**PostResource**:
```php
public function toArray($request): array
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'slug' => $this->slug,
        'excerpt' => $this->excerpt,
        'status' => $this->status,
        'published_at' => $this->published_at?->toISOString(),
        'author' => new UserResource($this->whenLoaded('author')),
        'category' => new CategoryResource($this->whenLoaded('category')),
        'tags' => TagResource::collection($this->whenLoaded('tags')),
        'featured_image' => $this->featured_image,
        'reading_time' => $this->reading_time,
        'lock_version' => $this->lock_version,
        'metadata' => $this->metadata,
        'created_at' => $this->created_at->toISOString(),
        'updated_at' => $this->updated_at->toISOString(),
    ];
}
```

## 🧪 Testing Requirements

**Feature Tests**:
- `tests/Feature/Http/Controllers/PostControllerTest.php`
  - Test index shows post list
  - Test create shows form
  - Test store creates post
  - Test edit shows form with revisions
  - Test update saves changes
  - Test destroy soft deletes post
  - Test authorization enforced
  - Test concurrent editing exception handled

- `tests/Feature/Http/Controllers/PageControllerTest.php`
  - Test hierarchical page listing
  - Test parent-child relationships
  - Test template selection

## 📚 Related Documentation

**PRD Specifications:**
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.11 (Controllers)
- **Timeline**: Week 5 (v1.0.0)

**Architecture:**
- **Pattern**: Inertia.js SSR + API Resources
- **Authorization**: Policies (PostPolicy, PagePolicy)
- **Validation**: Form Requests (StorePostRequest, UpdatePostRequest)

**Quality Requirements:**
- **Performance**: Page load < 2 seconds
- **Security**: Authorization on all methods
- **Testing**: > 80% coverage

**Related Tasks:**
- **Previous**: 015-024 (all content services)
- **Next**: 026-form-validation
- **Blocks**: Epic 004 (TipTap Editor)
- **Depends On**: All Epic 003 tasks

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing
- [ ] Feature tests passing (15+ test cases for PostController)
- [ ] Feature tests passing (10+ test cases for PageController)
- [ ] Authorization tested

### Security
- [ ] All methods authorized
- [ ] Input validated via Form Requests
- [ ] CSRF protection enabled

### Documentation
- [ ] Controller methods documented
- [ ] API Resources documented
- [ ] Routes documented

## ✅ Verification Steps

```bash
# Test post listing
php artisan serve
# Visit http://localhost:8000/admin/posts

# Test post creation
# Click "Create Post"
# Fill form and submit
# Verify redirect and success message

# Test post editing
# Click "Edit" on a post
# Modify content
# Save
# Verify changes persisted

# Quality check
composer quality
npm run quality
```
