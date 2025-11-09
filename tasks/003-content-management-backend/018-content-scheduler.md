---
task_id: 018
epic: 003-content-management-backend
title: Content Scheduler Service
status: pending
priority: high
estimated_effort: 6 hours
actual_effort: null
assignee: backend-architect
dependencies: [015, 016]
tags: [backend, scheduler, cron, week-4]
---

# Task 018: Content Scheduler Service

## 📋 Overview

Implement timezone-aware content scheduling for automatic publishing and unpublishing of posts and pages. Supports both visit-triggered and cron-based execution with configurable schedule checking.

## 🎯 Acceptance Criteria

- [ ] SchedulerService with publish/unpublish logic
- [ ] Timezone-aware date handling
- [ ] Cron command for scheduled publishing
- [ ] Visit-triggered publishing (fallback)
- [ ] Scheduled unpublishing support
- [ ] Notification on publish/unpublish
- [ ] Queue job for background execution
- [ ] Comprehensive unit and feature tests

## 🏗️ Implementation Steps

### Step 1: Create SchedulerService

**File**: `app/Services/Content/SchedulerService.php`

**Methods**:

- schedulePublish(Model $content, Carbon $publishAt): bool
- scheduleUnpublish(Model $content, Carbon $unpublishAt): bool
- executeScheduled(): int (returns count of published items)
- getScheduledContent(string $type = 'all'): Collection
- cancelSchedule(Model $content): bool

**Business Logic**:

- Convert all dates to UTC for storage
- Convert to site timezone for display
- Check publish_at <= now() for publishing
- Check unpublish_at <= now() for unpublishing
- Update status to 'published' or 'draft'
- Dispatch ContentPublished / ContentUnpublished events

### Step 2: Create Artisan Command

**File**: `app/Console/Commands/PublishScheduledContent.php`

**Command**: `php artisan content:publish-scheduled`

**Implementation**:

- Call SchedulerService::executeScheduled()
- Log count of published/unpublished items
- Send admin notification if configured
- Run every minute via cron

**Kernel Schedule**:

```php
$schedule->command('content:publish-scheduled')
    ->everyMinute()
    ->withoutOverlapping();
```

### Step 3: Create Queue Job

**File**: `app/Jobs/PublishScheduledContent.php`

**Job Logic**:

- Execute in background queue
- Retry 3 times on failure
- Timeout after 60 seconds
- Dispatch from visit middleware or cron

### Step 4: Visit-Triggered Publishing

**File**: `app/Http/Middleware/CheckScheduledContent.php`

**Middleware Logic**:

- Check if cron is disabled in settings
- Dispatch PublishScheduledContent job
- Throttle to once per minute (cache)
- Only run on frontend visits (not admin)

### Step 5: Timezone Handling

**Helper**: `app/Helpers/TimezoneHelper.php`

**Methods**:

- toUTC(Carbon $date): Carbon
- toSiteTimezone(Carbon $date): Carbon
- getSiteTimezone(): string (from settings)

**Usage**:

- Always store in UTC
- Convert to site timezone for display
- Use Carbon::parse($date)->timezone($tz)

## 🧪 Testing Requirements

**Unit Tests**:

- `tests/Unit/Services/SchedulerServiceTest.php`
    - Test schedulePublish updates publish_at
    - Test executeScheduled publishes ready content
    - Test timezone conversion (UTC ↔ site timezone)
    - Test scheduleUnpublish logic
    - Test cancelSchedule

**Feature Tests**:

- `tests/Feature/Content/SchedulerTest.php`
    - Test scheduled post publishes automatically
    - Test scheduled unpublishing
    - Test cron command execution
    - Test visit-triggered publishing
    - Test notification dispatched

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.4 (Scheduling)
- **Timeline**: Week 4 (v1.0.0)

**Architecture:**

- **Pattern**: Service Layer + Queue Jobs
- **Execution**: Cron (primary) + Visit-triggered (fallback)
- **Events**: ContentPublished, ContentUnpublished

**Quality Requirements:**

- **Accuracy**: 95%+ on-time publishing (within 1 minute)
- **Performance**: Execute < 500ms for 100 scheduled items
- **Testing**: > 80% coverage

**Related Tasks:**

- **Previous**: 015-post-crud-service, 016-page-crud-service
- **Next**: 019-auto-save
- **Depends On**: 010-content-schema

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all files
- [ ] PHPDoc with return types

### Testing

- [ ] Unit tests passing (10+ test cases)
- [ ] Feature tests passing (5+ scenarios)
- [ ] Edge cases covered (timezone boundaries, DST)

### Performance

- [ ] Scheduler execution < 500ms
- [ ] Queue job timeout configured
- [ ] Cron without overlapping

### Documentation

- [ ] Service methods documented
- [ ] Cron setup instructions
- [ ] Timezone handling explained

## ✅ Verification Steps

```bash
# Test scheduling
php artisan tinker
>>> $post = Post::first();
>>> $schedulerService = app(SchedulerService::class);
>>> $schedulerService->schedulePublish($post, now()->addHour());
>>> $post->publish_at; // Should be 1 hour from now (UTC)

# Test cron execution
php artisan content:publish-scheduled
>>> # Should publish ready content

# Test timezone conversion
>>> TimezoneHelper::toSiteTimezone($post->publish_at);
>>> # Should convert from UTC to site timezone

# Quality check
composer quality
```
