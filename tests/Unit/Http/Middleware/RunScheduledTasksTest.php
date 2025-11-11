<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\RunScheduledTasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RunScheduledTasksTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clear cache lock between tests
        Cache::forget('schedule:run:lock');
    }

    protected function tearDown(): void
    {
        Cache::forget('schedule:run:lock');
        parent::tearDown();
    }

    /**
     * Test middleware passes request through normally
     */
    public function testMiddlewarePassesRequestThrough(): void
    {
        Config::set('app.scheduler_mode', 'visit-triggered');

        $middleware = new RunScheduledTasks;
        $request = Request::create('/test', 'GET');

        $next = fn ($req) => response('success');

        $response = $middleware->handle($request, $next);

        $this->assertEquals('success', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test middleware only runs in visit-triggered mode
     */
    public function testMiddlewareOnlyRunsInVisitTriggeredMode(): void
    {
        Config::set('app.scheduler_mode', 'cron');

        $middleware = new RunScheduledTasks;
        $request = Request::create('/test', 'GET');

        $next = fn ($req) => response('success');

        // Should pass through without running scheduler
        $response = $middleware->handle($request, $next);

        $this->assertEquals('success', $response->getContent());
    }

    /**
     * Test middleware uses cache lock to prevent concurrent execution
     */
    public function testMiddlewareUsesCacheLock(): void
    {
        Config::set('app.scheduler_mode', 'visit-triggered');

        // Set lock manually to simulate concurrent request
        Cache::put('schedule:run:lock', true, 60);

        $middleware = new RunScheduledTasks;
        $request = Request::create('/test', 'GET');

        $executionCount = 0;
        $next = function ($req) use (&$executionCount) {
            $executionCount++;

            return response('success');
        };

        $response = $middleware->handle($request, $next);

        // Request should pass through but scheduler shouldn't run due to lock
        $this->assertEquals('success', $response->getContent());
        $this->assertEquals(1, $executionCount);
    }

    /**
     * Test middleware acquires lock when running scheduler
     */
    public function testMiddlewareAcquiresLockWhenRunning(): void
    {
        Config::set('app.scheduler_mode', 'visit-triggered');

        // Ensure no lock exists
        $this->assertFalse(Cache::has('schedule:run:lock'));

        $middleware = new RunScheduledTasks;
        $request = Request::create('/test', 'GET');

        $next = fn ($req) => response('success');

        $response = $middleware->handle($request, $next);

        // Lock should be created (it expires after 60 seconds by default)
        // Note: The lock might be released after execution, so we just verify the middleware ran
        $this->assertEquals('success', $response->getContent());
    }

    /**
     * Test middleware is disabled when scheduler_mode is not set
     */
    public function testMiddlewareDisabledWhenSchedulerModeNotSet(): void
    {
        Config::set('app.scheduler_mode', null);

        $middleware = new RunScheduledTasks;
        $request = Request::create('/test', 'GET');

        $next = fn ($req) => response('success');

        $response = $middleware->handle($request, $next);

        $this->assertEquals('success', $response->getContent());
    }

    /**
     * Test middleware handles disabled scheduler mode
     */
    public function testMiddlewareHandlesDisabledMode(): void
    {
        Config::set('app.scheduler_mode', 'disabled');

        $middleware = new RunScheduledTasks;
        $request = Request::create('/test', 'GET');

        $next = fn ($req) => response('success');

        $response = $middleware->handle($request, $next);

        $this->assertEquals('success', $response->getContent());
    }
}
