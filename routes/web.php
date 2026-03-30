<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/up', HealthCheckController::class);

// Override Fortify's forgot-password route to prevent email enumeration
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::middleware('auth')->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/email/verify', fn () => redirect()->route('dashboard'))->name('verification.notice');

    // profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile');
        Route::put('/information', [ProfileController::class, 'updateInformation'])->name('profile.update-information');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // back-end group
    Route::prefix('b')->group(function () {
        // short urls
        Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('short-urls.store');
        Route::get('/short-urls/{shortUrl}/qr', [ShortUrlController::class, 'qr'])->name('short-urls.qr');
        Route::put('/short-urls/{shortUrl}', [ShortUrlController::class, 'update'])->name('short-urls.update');
        Route::delete('/short-urls/{shortUrl}', [ShortUrlController::class, 'destroy'])->name('short-urls.destroy');
    });
});

// Public short URL redirect - must be last to avoid shadowing other routes
Route::get('/{code}', [ShortUrlController::class, 'redirect'])
    ->name('short-url.redirect')
    ->where('code', '[A-Za-z0-9_-]+');
