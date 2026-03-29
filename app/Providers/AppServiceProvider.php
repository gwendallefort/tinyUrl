<?php

namespace App\Providers;

use App\Http\Controllers\EmailVerificationNotificationController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Requests\VerifyEmailRequest;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Laravel\Fortify\Http\Controllers\VerifyEmailController::class,
            VerifyEmailController::class
        );

        $this->app->bind(
            \Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController::class,
            EmailVerificationNotificationController::class
        );

        $this->app->bind(
            \Laravel\Fortify\Http\Requests\VerifyEmailRequest::class,
            VerifyEmailRequest::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject(config('app.name').' - Verify your email')
                ->view('emails.verify-email', [
                    'url' => $url,
                    'expiresMinutes' => (int) config('auth.verification.expire', 60),
                ]);
        });

        $testingRecipient = config('mail.to_testing');

        if (! app()->environment('production')) {
            if (empty($testingRecipient)) {
                abort(500, 'Test email not set.');
            }
            Mail::alwaysTo($testingRecipient);
        }
    }
}
