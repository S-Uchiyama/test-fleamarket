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

    /** @test */
    public function verification_email_is_sent_after_register()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'verify-test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'verify-test@example.com')->firstOrFail();

        $response->assertRedirect('/email/verify');
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function unverified_user_can_login_without_verification_redirect()
    {
        $user = User::factory()->create([
            'email' => 'unverified@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
    }

    /** @test */
    public function verified_user_is_redirected_to_profile_after_email_verification()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect('/mypage/profile?verified=1');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
