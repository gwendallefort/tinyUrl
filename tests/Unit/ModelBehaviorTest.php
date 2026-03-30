<?php

namespace Tests\Unit;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_protocol_if_not_set_adds_https(): void
    {
        $this->assertSame('https://example.com', ShortUrl::setProtocolIfNotSet('example.com'));
        $this->assertSame('http://example.com', ShortUrl::setProtocolIfNotSet('http://example.com'));
    }

    public function test_short_link_without_protocol_strips_scheme(): void
    {
        $shortUrl = ShortUrl::factory()->create(['short_code' => 'abc123']);

        $this->assertStringContainsString('/abc123', $shortUrl->shortLink());
        $this->assertStringStartsWith('http', $shortUrl->shortLink(true));
    }

    public function test_user_verification_helpers_use_pending_email_when_present(): void
    {
        $user = User::factory()->create([
            'email' => 'current@example.com',
            'pending_email' => 'pending@example.com',
            'email_verified_at' => now(),
        ]);

        $this->assertSame('pending@example.com', $user->getEmailForVerification());
        $this->assertSame('pending@example.com', $user->routeNotificationForMail(new VerifyEmail));
    }

    public function test_mark_email_as_verified_applies_pending_email(): void
    {
        $user = User::factory()->create([
            'email' => 'current@example.com',
            'pending_email' => 'pending@example.com',
            'email_verified_at' => now(),
        ]);

        $result = $user->markEmailAsVerified();

        $this->assertTrue($result);
        $this->assertSame('pending@example.com', $user->fresh()->email);
        $this->assertNull($user->fresh()->pending_email);
    }
}
