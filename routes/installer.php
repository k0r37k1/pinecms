<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\AdminUserController;
use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\RequirementsController;
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
    Route::get('/admin-user', [AdminUserController::class, 'show'])->name('admin-user.show');
});

// API routes (for form submissions and AJAX requests)
Route::middleware(['api'])->group(function (): void {
    Route::get('/requirements', [RequirementsController::class, 'check'])->name('requirements.check');
    Route::post('/environment', [EnvironmentController::class, 'generate'])->name('environment.generate');
    Route::post('/database/initialize', [DatabaseController::class, 'initialize'])->name('database.initialize');
    Route::post('/database/migrate', [DatabaseController::class, 'migrate'])->name('database.migrate');
    Route::get('/database/info', [DatabaseController::class, 'info'])->name('database.info');

    // Admin user creation
    Route::post('/admin-user', [AdminUserController::class, 'create'])->name('admin-user.create');
    Route::post('/admin-user/check-password', [AdminUserController::class, 'checkPasswordStrength'])->name('admin-user.check-password');
});
