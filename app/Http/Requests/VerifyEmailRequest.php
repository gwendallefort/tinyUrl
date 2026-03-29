<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Laravel\Fortify\Http\Requests\VerifyEmailRequest as FortifyVerifyEmailRequest;

class VerifyEmailRequest extends FortifyVerifyEmailRequest
{
    /**
     * Redirect when id/hash no longer match the user (e.g. another email change).
     * Expired or tampered signed URLs fail in `signed` middleware; those are mapped
     * to the same redirect in `bootstrap/app.php` (InvalidSignatureException).
     */
    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            redirect()
                ->route('dashboard')
                ->with('status', 'verification-link-expired')
        );
    }
}
