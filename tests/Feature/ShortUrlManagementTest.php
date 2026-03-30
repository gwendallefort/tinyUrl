<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ShortUrlManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_short_url(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/b/short-urls', [
                'title' => 'Docs',
                'original_url' => 'example.com/docs',
                'short_code' => 'docs-link',
            ])
            ->assertRedirect(route('dashboard', absolute: false))
            ->assertSessionHas('status', 'url-created');

        $this->assertDatabaseHas('short_urls', [
            'user_id' => $user->id,
            'title' => 'Docs',
            'short_code' => 'docs-link',
            'original_url' => 'https://example.com/docs',
        ]);
    }

    public function test_reserved_short_code_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/dashboard')
            ->post('/b/short-urls', [
                'original_url' => 'example.com',
                'short_code' => 'login',
            ])
            ->assertRedirect('/dashboard')
            ->assertSessionHasErrors(['short_code'], null, 'createUrl');
    }

    public function test_self_referencing_destination_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/dashboard')
            ->post('/b/short-urls', [
                'original_url' => url('/loopx'),
                'short_code' => 'loopx',
            ])
            ->assertRedirect('/dashboard')
            ->assertSessionHasErrors(['original_url'], null, 'createUrl');
    }

    public function test_owner_can_update_short_url_and_old_cache_key_is_invalidated(): void
    {
        $user = User::factory()->create();
        $shortUrl = ShortUrl::factory()->for($user)->create([
            'short_code' => 'old-code',
            'original_url' => 'https://old.example.com',
        ]);
        $oldCacheKey = 'short-url:redirect:old-code';
        Cache::put($oldCacheKey, ['id' => $shortUrl->id], 300);

        $this->actingAs($user)
            ->put('/b/short-urls/'.$shortUrl->uuid, [
                'title' => 'Updated',
                'original_url' => 'new.example.com/path',
                'short_code' => 'new-code',
            ])
            ->assertRedirect(route('dashboard', absolute: false))
            ->assertSessionHas('status', 'url-updated');

        $this->assertNull(Cache::get($oldCacheKey));
        $this->assertDatabaseHas('short_urls', [
            'id' => $shortUrl->id,
            'title' => 'Updated',
            'short_code' => 'new-code',
            'original_url' => 'https://new.example.com/path',
        ]);
    }

    public function test_non_owner_cannot_update_short_url(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $shortUrl = ShortUrl::factory()->for($owner)->create();

        $this->actingAs($other)
            ->put('/b/short-urls/'.$shortUrl->uuid, [
                'original_url' => 'https://example.com/new',
                'short_code' => $shortUrl->short_code,
            ])
            ->assertForbidden();
    }

    public function test_owner_can_delete_short_url_and_cache_is_invalidated(): void
    {
        $user = User::factory()->create();
        $shortUrl = ShortUrl::factory()->for($user)->create(['short_code' => 'to-delete']);
        $cacheKey = 'short-url:redirect:to-delete';
        Cache::put($cacheKey, ['id' => $shortUrl->id], 300);

        $this->actingAs($user)
            ->delete('/b/short-urls/'.$shortUrl->uuid)
            ->assertRedirect(route('dashboard', absolute: false))
            ->assertSessionHas('status', 'url-deleted');

        $this->assertNull(Cache::get($cacheKey));
        $this->assertDatabaseMissing('short_urls', ['id' => $shortUrl->id]);
    }
}
