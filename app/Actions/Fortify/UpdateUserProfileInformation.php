<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('users', 'pending_email')->ignore($user->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        $email = Str::lower($input['email']);

        if ($email !== Str::lower($user->email) && $user instanceof MustVerifyEmail) {
            if ($user->hasVerifiedEmail()) {
                $this->queueVerifiedUserEmailChange($user, $email);

                return;
            }

            $this->updateUnverifiedUserEmail($user, $email);

            return;
        }

        if ($user->pending_email !== null) {
            $user->forceFill(['pending_email' => null])->save();
        }
    }

    /**
     * Store the new address as pending; current email stays verified until the link is used.
     */
    protected function queueVerifiedUserEmailChange(User $user, string $email): void
    {
        $user->forceFill(['pending_email' => $email])->save();
        $user->sendEmailVerificationNotification();
    }

    /**
     * Unverified accounts keep the previous Fortify behaviour: update email and re-send verification.
     */
    protected function updateUnverifiedUserEmail(User $user, string $email): void
    {
        $user->forceFill([
            'email' => $email,
            'pending_email' => null,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
