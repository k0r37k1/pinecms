<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\RequirementsController;
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
    Route::get('/requirements', [RequirementsController::class, 'check'])->name('installer.requirements.check');
    Route::post('/environment', [EnvironmentController::class, 'generate'])->name('installer.environment.generate');
});
