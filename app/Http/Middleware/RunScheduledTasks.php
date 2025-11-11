<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RunScheduledTasks
{
    /**
     * Cache lock key
     */
    private const LOCK_KEY = 'schedule:run:lock';

    /**
     * Lock duration in seconds (1 minute)
     */
    private const LOCK_DURATION = 60;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run scheduler in visit-triggered mode
        if (config('app.scheduler_mode') === 'visit-triggered') {
            $this->runScheduler();
        }

        return $next($request);
    }

    /**
     * Run the scheduler if not already running
     */
    private function runScheduler(): void
    {
        // Use cache lock to prevent concurrent executions
        if (Cache::has(self::LOCK_KEY)) {
            return;
        }

        // Acquire lock
        Cache::put(self::LOCK_KEY, true, self::LOCK_DURATION);

        try {
            // Run scheduler in background
            Artisan::call('schedule:run');
        } catch (\Exception $e) {
            // Silently fail - don't disrupt user request
            // Log error if in debug mode
            if (config('app.debug') === true) {
                \Log::error('Failed to run scheduled tasks', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
