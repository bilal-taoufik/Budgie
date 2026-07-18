<?php

namespace Tests\Feature\Auth;

use App\Mail\VerifyMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_token_verifies_email_and_invalidates_token(): void
    {
        $user = User::factory()->create([
            'email_verified' => false,
            'email_verification_token' => 'valid-token',
            'email_verification_expires_at' => now()->addHour(),
        ]);

        $this->get(route('verify.email', ['token' => 'valid-token']))
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue((bool) $user->email_verified);
        $this->assertNull($user->email_verification_token);
        $this->assertNull($user->email_verification_expires_at);
    }

    public function test_invalid_or_expired_token_does_not_verify_email(): void
    {
        $user = User::factory()->create([
            'email_verified' => false,
            'email_verification_token' => 'expired-token',
            'email_verification_expires_at' => now()->subMinute(),
        ]);

        $this->get(route('verify.email', ['token' => 'unknown']))
            ->assertRedirect(route('login'))->assertSessionHas('error');
        $this->get(route('verify.email', ['token' => 'expired-token']))
            ->assertRedirect(route('login'))->assertSessionHas('error');

        $this->assertFalse((bool) $user->fresh()->email_verified);
    }

    public function test_unverified_user_can_request_new_verification_email(): void
    {
        Mail::fake();
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'email_verified' => false,
            'email_verification_token' => 'old-token',
            'email_verification_expires_at' => now()->subHour(),
        ]);

        $this->post(route('resend.verification'), ['email' => ' USER@EXAMPLE.COM '])
            ->assertRedirect(route('login'))->assertSessionHas('success');

        $user->refresh();
        $this->assertNotSame('old-token', $user->email_verification_token);
        $this->assertTrue(Carbon::parse($user->email_verification_expires_at)->isFuture());
        Mail::assertSent(VerifyMail::class, fn ($mail) => $mail->hasTo($user->email));
    }

    public function test_resend_rejects_unknown_email_and_skips_verified_user(): void
    {
        Mail::fake();

        $this->post(route('resend.verification'), ['email' => 'unknown@example.com'])
            ->assertSessionHasErrors('email');

        $user = User::factory()->create(['email_verified' => true]);
        $this->post(route('resend.verification'), ['email' => $user->email])
            ->assertRedirect(route('login'))->assertSessionHas('info');

        Mail::assertNothingSent();
    }
}
