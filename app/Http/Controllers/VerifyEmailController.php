<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Laravel\Fortify\Http\Controllers\VerifyEmailController as FortifyVerifyEmailController;
use Laravel\Fortify\Http\Requests\VerifyEmailRequest;

class VerifyEmailController extends FortifyVerifyEmailController
{
    /**
     * Complete initial verification or apply a verified user's pending email change.
     */
    public function __invoke(VerifyEmailRequest $request): VerifyEmailResponse
    {
        $user = $request->user();

        if (filled($user->pending_email)) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            return app(VerifyEmailResponse::class);
        }

        return parent::__invoke($request);
    }
}
