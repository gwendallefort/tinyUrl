<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertGuest();
    }
}
