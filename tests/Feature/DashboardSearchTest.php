<?php

namespace Tests\Feature;

use App\Livewire\DashboardUrls;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_search_filters_by_short_code(): void
    {
        $user = User::factory()->create();

        ShortUrl::factory()->for($user)->create([
            'short_code' => 'docs-link',
            'original_url' => 'https://example.com/docs',
        ]);
        ShortUrl::factory()->for($user)->create([
            'short_code' => 'other-link',
            'original_url' => 'https://other.example.com',
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardUrls::class)
            ->set('search', 'docs')
            ->assertSee('/docs-link')
            ->assertDontSee('/other-link');
    }

    public function test_dashboard_search_filters_by_original_url(): void
    {
        $user = User::factory()->create();

        ShortUrl::factory()->for($user)->create([
            'short_code' => 'alpha',
            'original_url' => 'https://laravel.com/docs',
        ]);
        ShortUrl::factory()->for($user)->create([
            'short_code' => 'beta',
            'original_url' => 'https://php.net/manual',
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardUrls::class)
            ->set('search', 'php.net')
            ->assertSee('/beta')
            ->assertDontSee('/alpha');
    }

    public function test_dashboard_search_returns_empty_state_when_nothing_matches(): void
    {
        $user = User::factory()->create();

        ShortUrl::factory()->for($user)->create([
            'short_code' => 'keep-me',
            'original_url' => 'https://example.com',
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardUrls::class)
            ->set('search', 'missing-term')
            ->assertSee('No matching short URLs')
            ->assertDontSee('/keep-me');
    }

    public function test_dashboard_search_only_searches_current_users_urls(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        ShortUrl::factory()->for($other)->create([
            'short_code' => 'secret',
            'original_url' => 'https://secret.example.com',
        ]);
        ShortUrl::factory()->for($user)->create([
            'short_code' => 'mine',
            'original_url' => 'https://mine.example.com',
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardUrls::class)
            ->set('search', 'secret')
            ->assertSee('No matching short URLs')
            ->assertDontSee('/secret');
    }

    public function test_dashboard_search_can_be_cleared(): void
    {
        $user = User::factory()->create();

        ShortUrl::factory()->for($user)->create([
            'short_code' => 'keep-me',
            'original_url' => 'https://example.com',
        ]);

        $this->actingAs($user);

        Livewire::test(DashboardUrls::class)
            ->set('search', 'missing-term')
            ->assertSee('No matching short URLs')
            ->call('clear')
            ->assertSet('search', '')
            ->assertSee('/keep-me');
    }

    public function test_guests_cannot_view_dashboard(): void
    {
        $this->get('/dashboard')
            ->assertRedirect();
    }
}
