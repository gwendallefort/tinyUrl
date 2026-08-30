<?php

namespace App\Models;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Support\SoftDeleteTombstone;
use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'pending_email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected static function booted(): void
    {
        static::deleting(function (self $user) {
            if ($user->isForceDeleting()) {
                return;
            }

            // clear user data
            $emails = array_values(array_filter([$user->email, $user->pending_email]));
            if ($emails !== []) {
                DB::table(config('auth.passwords.users.table'))
                    ->whereIn('email', $emails)
                    ->delete();
            }

            $user->forceFill([
                'name' => null,
                'email' => SoftDeleteTombstone::value($user->id),
                'pending_email' => null,
                'email_verified_at' => null,
                'password' => Str::random(64),
                'remember_token' => null,
            ])->saveQuietly();

            // delete the associated short shortUrls
            $user->shortUrls()->each(fn (ShortUrl $shortUrl) => $shortUrl->delete());
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function shortUrls(): HasMany
    {
        return $this->hasMany(ShortUrl::class);
    }

    /**
     * Signed verification links must match the address being verified (pending change or current).
     */
    public function getEmailForVerification(): string
    {
        return (string) ($this->pending_email ?? $this->email);
    }

    /**
     * Send verification messages for a pending address to that inbox, not the current account email.
     *
     * @param  Notification  $notification
     */
    public function routeNotificationForMail($notification): ?string
    {
        if ($notification instanceof VerifyEmail && filled($this->pending_email)) {
            return $this->pending_email;
        }

        return $this->email;
    }

    /**
     * Apply pending email after verification, or set verified timestamp for first-time verification.
     */
    public function markEmailAsVerified(): bool
    {
        if (filled($this->pending_email)) {
            return $this->forceFill([
                'email' => $this->pending_email,
                'pending_email' => null,
                'email_verified_at' => $this->freshTimestamp(),
            ])->save();
        }

        if ($this->hasVerifiedEmail()) {
            return false;
        }

        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
