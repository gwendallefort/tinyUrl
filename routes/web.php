<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Override Fortify's forgot-password route to prevent email enumeration
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        $shortUrls = auth()->user()->shortUrls()->latest()->get();
        return view('home', compact('shortUrls'));
    })->name('home');

    Route::get('/dashboard', function () {
        return redirect()->route('home');
    });

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile/information', [ProfileController::class, 'updateInformation'])->name('profile.update-information');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('b')->group(function () {
        Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('short-urls.store');
        Route::put('/short-urls/{shortUrl}', [ShortUrlController::class, 'update'])->name('short-urls.update');
        Route::delete('/short-urls/{shortUrl}', [ShortUrlController::class, 'destroy'])->name('short-urls.destroy');
    });
});

// Public short URL redirect - must be last to avoid shadowing other routes
Route::get('/{code}', [ShortUrlController::class, 'redirect'])
    ->name('short-url.redirect')
    ->where('code', '[A-Za-z0-9_-]+');
