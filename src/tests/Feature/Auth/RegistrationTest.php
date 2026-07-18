<?php

namespace Tests\Feature\Auth;

use App\Mail\VerifyMail;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->get(route('register'))->assertOk();
    }

    public function test_new_customer_can_register_and_receives_emails(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'firstname' => '  bilal ',
            'lastname' => ' taoufik ',
            'email' => ' TEST@EXAMPLE.COM ',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('login'));
        $this->assertGuest();

        $user = User::where('email', 'test@example.com')->sole();
        $this->assertSame('Bilal', $user->firstname);
        $this->assertSame('TAOUFIK', $user->lastname);
        $this->assertSame('customer', $user->role);
        $this->assertFalse((bool) $user->email_verified);
        $this->assertNotNull($user->email_verification_token);
        $this->assertTrue(Hash::check('Password123!', $user->password));

        Mail::assertSent(WelcomeMail::class, fn ($mail) => $mail->hasTo($user->email));
        Mail::assertSent(VerifyMail::class, fn ($mail) => $mail->hasTo($user->email));
    }

    public function test_registration_rejects_duplicate_email_and_weak_password(): void
    {
        User::factory()->create(['email' => 'used@example.com']);

        $response = $this->post(route('register'), [
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'USED@example.com',
            'password' => 'faible',
            'password_confirmation' => 'faible',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertDatabaseCount('users', 1);
    }
}
