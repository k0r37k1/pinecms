<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\AdminUserController;
use App\Http\Controllers\Installer\CleanupController;
use App\Http\Controllers\Installer\CronController;
use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\RequirementsController;
use App\Http\Controllers\Installer\UnlockController;
use App\Http\Controllers\Installer\WebServerController;
use App\Http\Middleware\PreventInstalledAccess;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Installer Routes
|--------------------------------------------------------------------------
|
| Routes for the PineCMS installation wizard. These routes are accessible
| before the application is fully configured and do not require authentication.
|
*/

// Web routes (for Inertia pages)
Route::middleware(['web'])->group(function (): void {
    Route::get('/wizard', [AdminUserController::class, 'show'])->name('wizard.show');
});

// API/Web hybrid routes (handle both JSON API and Inertia form submissions)
// Uses 'web' middleware for CSRF + session but controller detects request type
Route::middleware(['web', PreventInstalledAccess::class])->group(function (): void {
    Route::post('/wizard', [AdminUserController::class, 'create'])->name('wizard.create');
    Route::post('/wizard/check-password', [AdminUserController::class, 'checkPasswordStrength'])->name('wizard.check-password');
});

// API routes (for form submissions and AJAX requests)
Route::middleware(['api', PreventInstalledAccess::class])->group(function (): void {
    Route::get('/requirements', [RequirementsController::class, 'check'])->name('requirements.check');
    Route::post('/environment', [EnvironmentController::class, 'generate'])->name('environment.generate');
    Route::post('/database/initialize', [DatabaseController::class, 'initialize'])->name('database.initialize');
    Route::post('/database/migrate', [DatabaseController::class, 'migrate'])->name('database.migrate');
    Route::get('/database/info', [DatabaseController::class, 'info'])->name('database.info');

    // Web server configuration
    Route::post('/webserver/apache', [WebServerController::class, 'generateApacheConfig'])->name('webserver.apache');
    Route::post('/webserver/nginx', [WebServerController::class, 'generateNginxExample'])->name('webserver.nginx');
    Route::get('/webserver/detect', [WebServerController::class, 'detectServer'])->name('webserver.detect');

    // Cron detection and configuration
    Route::get('/cron/detect', [CronController::class, 'detect'])->name('cron.detect');
    Route::post('/cron/set-mode', [CronController::class, 'setMode'])->name('cron.set-mode');
    Route::get('/cron/test', [CronController::class, 'test'])->name('cron.test');

    // Post-install cleanup (final step)
    Route::post('/cleanup', [CleanupController::class, 'cleanup'])->name('cleanup');
    Route::get('/status', [CleanupController::class, 'status'])->name('status');
});

// Testing-only unlock endpoint for E2E test isolation (no PreventInstalledAccess middleware)
// Rate limited for defense-in-depth security (max 5 requests per minute, except during tests)
$unlockMiddleware = ['api'];
if (! (bool) config('app.testing')) {
    $unlockMiddleware[] = 'throttle:5,1';
}
Route::post('/unlock', [UnlockController::class, 'unlock'])
    ->middleware($unlockMiddleware)
    ->name('unlock');
