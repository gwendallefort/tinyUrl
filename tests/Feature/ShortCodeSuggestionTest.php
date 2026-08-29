<?php

namespace Tests\Feature;

use App\Ai\Agents\ShortCodeCreator;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortCodeSuggestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_request_short_code_suggestions(): void
    {
        $this->postJson('/b/short-urls/suggest', [
            'original_url' => 'example.com/docs',
        ])->assertUnauthorized();
    }

    public function test_authenticated_user_can_receive_short_code_suggestions(): void
    {
        ShortCodeCreator::fake([
            ['suggestions' => ['docs-guide', 'laravel-docs', 'api-docs', 'read-docs', 'docs-home']],
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/b/short-urls/suggest', [
                'title' => 'Laravel Docs',
                'original_url' => 'laravel.com/docs',
            ])
            ->assertOk()
            ->assertJson([
                'suggestions' => ['docs-guide', 'laravel-docs', 'api-docs', 'read-docs', 'docs-home'],
            ]);

        ShortCodeCreator::assertPrompted(fn ($prompt) => str_contains($prompt->prompt, 'Laravel Docs'));
    }

    public function test_taken_and_reserved_suggestions_are_filtered_out(): void
    {
        ShortCodeCreator::fake([
            ['suggestions' => ['login', 'taken-code', 'good-alias', 'ab', 'also-good']],
        ]);

        $user = User::factory()->create();
        ShortUrl::factory()->for($user)->create(['short_code' => 'taken-code']);

        $this->actingAs($user)
            ->postJson('/b/short-urls/suggest', [
                'original_url' => 'example.com/page',
            ])
            ->assertOk()
            ->assertJson([
                'suggestions' => ['good-alias', 'also-good'],
            ]);
    }

    public function test_suggestion_requires_title_or_url(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/b/short-urls/suggest', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['original_url']);
    }
}
