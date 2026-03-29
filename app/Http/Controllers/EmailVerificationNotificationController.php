<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\EmailVerificationNotificationSentResponse;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController as FortifyEmailVerificationNotificationController;
use Laravel\Fortify\Http\Responses\RedirectAsIntended;

class EmailVerificationNotificationController extends FortifyEmailVerificationNotificationController
{
    /**
     * Send verification for unverified accounts, or for a pending email change on verified accounts.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail() && ! filled($user->pending_email)) {
            return $request->wantsJson()
                ? new JsonResponse('', 204)
                : app(RedirectAsIntended::class, ['name' => 'email-verification']);
        }

        $user->sendEmailVerificationNotification();

        return app(EmailVerificationNotificationSentResponse::class);
    }
}
