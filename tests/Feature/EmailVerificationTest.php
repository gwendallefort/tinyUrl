<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_visiting_verification_notice_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/email/verify')
            ->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_unverified_user_can_verify_with_valid_signed_url(): void
    {
        $user = User::factory()->unverified()->create();
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect(route('dashboard', ['verified' => 1], absolute: false));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_verified_user_with_pending_email_applies_pending_email_on_verification(): void
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'pending_email' => 'pending@example.com',
            'email_verified_at' => now(),
        ]);
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            ['id' => $user->id, 'hash' => sha1('pending@example.com')]
        );

        $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect(route('dashboard', ['verified' => 1], absolute: false));

        $user->refresh();
        $this->assertSame('pending@example.com', $user->email);
        $this->assertNull($user->pending_email);
    }

    public function test_expired_verification_link_redirects_with_flash_status(): void
    {
        $user = User::factory()->unverified()->create();
        $expiredLink = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinute(),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)
            ->get($expiredLink)
            ->assertRedirect(route('dashboard', absolute: false))
            ->assertSessionHas('status', 'verification-link-expired');
    }

    public function test_verification_send_sends_email_for_pending_email_change(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'pending_email' => 'pending@example.com',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->post('/email/verification-notification')
            ->assertRedirect();

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
