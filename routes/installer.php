<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\RequirementsController;
use App\Http\Controllers\Installer\WebServerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Installer API Routes
|--------------------------------------------------------------------------
|
| Routes for the PineCMS installation wizard. These routes are accessible
| before the application is fully configured and do not require authentication.
|
*/

Route::middleware(['api'])->group(function (): void {
    Route::get('/requirements', [RequirementsController::class, 'check'])->name('requirements.check');
    Route::post('/environment', [EnvironmentController::class, 'generate'])->name('environment.generate');
    Route::post('/database/initialize', [DatabaseController::class, 'initialize'])->name('database.initialize');
    Route::post('/database/migrate', [DatabaseController::class, 'migrate'])->name('database.migrate');
    Route::get('/database/info', [DatabaseController::class, 'info'])->name('database.info');

    // Web server configuration
    Route::post('/webserver/apache', [WebServerController::class, 'generateApacheConfig'])->name('webserver.apache');
    Route::post('/webserver/nginx', [WebServerController::class, 'generateNginxExample'])->name('webserver.nginx');
    Route::get('/webserver/detect', [WebServerController::class, 'detectServer'])->name('webserver.detect');
});
