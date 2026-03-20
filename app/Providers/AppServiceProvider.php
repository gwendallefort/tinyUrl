<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $testingRecipient = config('mail.to_testing');

        if (! app()->environment('production') && ! empty($testingRecipient)) {
            Mail::alwaysTo($testingRecipient);
        }
    }
}
