<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Welcome');
});

// Locale Switcher
Route::post('/locale', function () {
    $locale = request('locale');
    $supportedLocales = config('app.supported_locales', ['en', 'de']);

    if (in_array($locale, $supportedLocales, true)) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    return back();
})->name('locale.switch');
