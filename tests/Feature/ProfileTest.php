<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use App\Models\User;
use App\Support\SoftDeleteTombstone;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_open_profile_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk();
    }

    public function test_verified_user_email_change_sets_pending_email_and_sends_verification(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->from('/profile')
            ->put('/profile/information', ['email' => 'new@example.com'])
            ->assertRedirect('/profile')
            ->assertSessionHas('status', 'email-change-pending');

        $this->assertSame('old@example.com', $user->fresh()->email);
        $this->assertSame('new@example.com', $user->fresh()->pending_email);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_user_can_update_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('Current1!'),
        ]);

        $this->actingAs($user)
            ->from('/profile')
            ->put('/profile/password', [
                'current_password' => 'Current1!',
                'password' => 'Updated1!',
                'password_confirmation' => 'Updated1!',
            ])
            ->assertRedirect('/profile')
            ->assertSessionHas('status', 'password-updated');

        $this->assertTrue(Hash::check('Updated1!', $user->fresh()->password));
    }

    public function test_user_can_delete_account_with_valid_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('DeleteMe1!'),
        ]);

        $this->actingAs($user)
            ->delete('/profile', ['password' => 'DeleteMe1!'])
            ->assertRedirect('/');

        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => SoftDeleteTombstone::value($user->id),
            'name' => null,
            'pending_email' => null,
            'email_verified_at' => null,
            'remember_token' => null,
        ]);
        $this->assertFalse(Hash::check('DeleteMe1!', User::withTrashed()->find($user->id)->password));
        $this->assertGuest();
    }

    public function test_deleting_user_removes_password_reset_tokens_for_account_emails(): void
    {
        $user = User::factory()->create([
            'email' => 'clear-me@example.com',
            'pending_email' => 'pending@example.com',
            'password' => Hash::make('DeleteMe1!'),
        ]);

        DB::table('password_reset_tokens')->insert([
            ['email' => 'clear-me@example.com', 'token' => 'reset-token', 'created_at' => now()],
            ['email' => 'pending@example.com', 'token' => 'pending-reset-token', 'created_at' => now()],
        ]);

        $this->actingAs($user)
            ->delete('/profile', ['password' => 'DeleteMe1!'])
            ->assertRedirect('/');

        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'clear-me@example.com']);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'pending@example.com']);
    }

    public function test_deleted_user_can_register_again_with_same_email(): void
    {
        $user = User::factory()->create([
            'email' => 'returning@example.com',
            'password' => Hash::make('DeleteMe1!'),
        ]);

        $this->actingAs($user)
            ->delete('/profile', ['password' => 'DeleteMe1!'])
            ->assertRedirect('/');

        $this->post('/register', [
            'email' => 'returning@example.com',
            'password' => 'NewUser1!',
            'password_confirmation' => 'NewUser1!',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'returning@example.com',
            'deleted_at' => null,
        ]);
    }

    public function test_deleting_user_soft_deletes_their_short_urls(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('DeleteMe1!'),
        ]);
        $shortUrl = ShortUrl::factory()->for($user)->create(['short_code' => 'user-link']);

        $this->actingAs($user)
            ->delete('/profile', ['password' => 'DeleteMe1!'])
            ->assertRedirect('/');

        $this->assertSoftDeleted('short_urls', ['id' => $shortUrl->id]);
        $this->assertDatabaseHas('short_urls', [
            'id' => $shortUrl->id,
            'short_code' => SoftDeleteTombstone::value($shortUrl->id, 'user-link'),
        ]);
        $this->get('/user-link')->assertNotFound();
    }
}
