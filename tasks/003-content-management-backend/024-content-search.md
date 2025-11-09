---
task_id: 024
epic: 003-content-management-backend
title: Content Search & Filtering
status: pending
priority: medium
estimated_effort: 6 hours
actual_effort: null
assignee: backend-architect
dependencies: [015, 016]
tags: [backend, search, filtering, week-5]
---

# Task 024: Content Search & Filtering

## 📋 Overview

Build comprehensive search and filtering system for posts and pages with support for full-text search (title, content, excerpt), filtering by status, author, category, tags, and date range. Uses SQLite full-text search capabilities.

## 🎯 Acceptance Criteria

- [ ] SearchService with query builder
- [ ] Full-text search (title, content, excerpt)
- [ ] Filter by status (draft, published, archived)
- [ ] Filter by author
- [ ] Filter by category
- [ ] Filter by tags (multi-select)
- [ ] Filter by date range
- [ ] Sorting (newest, oldest, title, updated)
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create SearchService

**File**: `app/Services/Content/SearchService.php`

**Methods**:

- search(string $query, array $filters, string $contentType): LengthAwarePaginator
- buildQuery(string $query, array $filters): Builder
- applyFilters(Builder $query, array $filters): Builder
- applySorting(Builder $query, string $sort): Builder

**Business Logic**:

- Use Laravel Scout with TNTSearch driver for full-text search
- Combine search with filters using Eloquent query builder
- Support pagination (configurable per page)
- Return relevant results first (search score)

### Step 2: Full-Text Search

**Implementation**:

```php
public function search(string $query, array $filters, string $contentType): LengthAwarePaginator
{
    $model = $this->getModel($contentType);

    if (empty($query)) {
        $builder = $model::query();
    } else {
        $builder = $model::search($query);
    }

    $builder = $this->applyFilters($builder, $filters);
    $builder = $this->applySorting($builder, $filters['sort'] ?? 'newest');

    return $builder->paginate($filters['per_page'] ?? 20);
}
```

### Step 3: Filter Implementation

**Supported Filters**:

```php
public function applyFilters(Builder $query, array $filters): Builder
{
    if (isset($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    if (isset($filters['author_id'])) {
        $query->where('author_id', $filters['author_id']);
    }

    if (isset($filters['category_id'])) {
        $query->where('category_id', $filters['category_id']);
    }

    if (isset($filters['tags'])) {
        $query->whereHas('tags', fn($q) => $q->whereIn('id', $filters['tags']));
    }

    if (isset($filters['date_from'])) {
        $query->where('created_at', '>=', $filters['date_from']);
    }

    if (isset($filters['date_to'])) {
        $query->where('created_at', '<=', $filters['date_to']);
    }

    return $query;
}
```

### Step 4: Sorting

**Supported Sorts**:

- `newest` - created_at DESC
- `oldest` - created_at ASC
- `title` - title ASC
- `updated` - updated_at DESC

### Step 5: Create API Endpoint

**Route**: `GET /api/admin/content/search`

**Controller**: `app/Http/Controllers/Admin/SearchController.php`

**Query Parameters**:

```
?q=search+query
&type=post
&status=published
&author_id=1
&category_id=5
&tags[]=1&tags[]=2
&date_from=2025-01-01
&date_to=2025-12-31
&sort=newest
&per_page=20
&page=1
```

**Response**:

```json
{
  "data": [...],
  "meta": {
    "total": 150,
    "per_page": 20,
    "current_page": 1,
    "last_page": 8
  },
  "filters": {
    "q": "search query",
    "status": "published",
    ...
  }
}
```

### Step 6: Frontend Search UI

**Component**: `resources/js/Components/ContentSearch.vue`

**Features**:

- Search input with debounce
- Filter sidebar (collapsible)
- Sort dropdown
- Clear filters button
- Results count indicator

## 🧪 Testing Requirements

**Unit Tests**:

- `tests/Unit/Services/SearchServiceTest.php`
    - Test search with query
    - Test applyFilters for each filter
    - Test applySorting for each sort
    - Test empty query returns all
    - Test combined filters and search

**Feature Tests**:

- `tests/Feature/Content/SearchTest.php`
    - Test search API endpoint
    - Test filter by status
    - Test filter by author
    - Test filter by category
    - Test filter by tags
    - Test filter by date range
    - Test sorting
    - Test pagination

**Frontend Tests**:

- `tests/vitest/components/ContentSearch.spec.ts`
    - Test search input debounce
    - Test filters update results
    - Test clear filters

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.10 (Search & Filtering)
- **Timeline**: Week 5 (v1.0.0)

**Architecture:**

- **Search Engine**: Laravel Scout with TNTSearch
- **Filters**: Eloquent query builder
- **Performance**: Indexed columns (status, author_id, category_id)

**Quality Requirements:**

- **Performance**: Search < 200ms for 10,000 posts
- **Relevance**: Full-text search prioritizes exact matches
- **Testing**: > 80% coverage

**Related Tasks:**

- **Previous**: 015-post-crud-service, 016-page-crud-service
- **Next**: 025-post-page-controllers
- **Depends On**: 010-content-schema

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing

- [ ] Unit tests passing (10+ test cases)
- [ ] Feature tests passing (8+ scenarios)
- [ ] Frontend tests passing (3+ scenarios)

### Performance

- [ ] Search < 200ms
- [ ] Filters efficient (indexed columns)
- [ ] Pagination working

### Documentation

- [ ] Service methods documented
- [ ] API endpoint documented
- [ ] Supported filters documented

## ✅ Verification Steps

```bash
# Test search
php artisan tinker
>>> $service = app(SearchService::class);
>>> $results = $service->search('laravel', ['status' => 'published'], 'post');
>>> $results->total();

# Test via API
curl "http://localhost:8000/api/admin/content/search?q=laravel&type=post&status=published"

# Quality check
composer quality
npm run quality
```
