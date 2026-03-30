<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use App\Models\ShortUrlClick;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_short_url_redirects_to_destination(): void
    {
        $shortUrl = ShortUrl::factory()->create([
            'original_url' => 'https://example.org/page',
            'short_code' => 'public1',
        ]);

        $this->get('/'.$shortUrl->short_code)
            ->assertRedirect('https://example.org/page');
    }

    public function test_unknown_short_code_returns_404(): void
    {
        $this->get('/does-not-exist')->assertNotFound();
    }

    public function test_redirect_logs_click_and_increments_counter(): void
    {
        $shortUrl = ShortUrl::factory()->create([
            'short_code' => 'trackit',
            'clicks' => 0,
        ]);

        $this->get('/'.$shortUrl->short_code)->assertRedirect($shortUrl->original_url);

        $this->assertDatabaseHas('short_url_clicks', [
            'short_url_id' => $shortUrl->id,
            'request_method' => 'GET',
            'path' => $shortUrl->short_code,
        ]);

        $this->assertSame(1, $shortUrl->fresh()->clicks);
        $this->assertGreaterThan(0, ShortUrlClick::count());
    }

    public function test_owner_can_fetch_qr_code_png(): void
    {
        $user = User::factory()->create();
        $shortUrl = ShortUrl::factory()->for($user)->create();

        $this->actingAs($user)
            ->get('/b/short-urls/'.$shortUrl->uuid.'/qr')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_non_owner_cannot_fetch_qr_code(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $shortUrl = ShortUrl::factory()->for($owner)->create();

        $this->actingAs($other)
            ->get('/b/short-urls/'.$shortUrl->uuid.'/qr')
            ->assertForbidden();
    }
}
