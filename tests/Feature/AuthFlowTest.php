<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_and_gets_verification_email(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'email' => 'new-user@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $user = User::where('email', 'new-user@example.com')->first();

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('Password1!'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password1!',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_forgot_password_always_returns_success_status_for_valid_email_format(): void
    {
        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => 'unknown@example.com',
        ]);

        $response
            ->assertRedirect('/forgot-password')
            ->assertSessionHas('status');
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword1!',
            'password_confirmation' => 'NewPassword1!',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('NewPassword1!', $user->fresh()->password));
    }
}
