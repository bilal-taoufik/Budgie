<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_change_password_with_current_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('CurrentPassword1!'),
        ]);

        $this->actingAs($admin)->put(route('admin.profile.password'), [
            'current_password' => 'CurrentPassword1!',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.profile.index'));

        $this->assertTrue(Hash::check('NewPassword123!', $admin->fresh()->password));
    }

    public function test_weak_new_password_is_rejected(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'password' => bcrypt('CurrentPassword1!'),
        ]);

        $this->actingAs($user)->put(route('customer.profile.password'), [
            'current_password' => 'CurrentPassword1!',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ])->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('CurrentPassword1!', $user->fresh()->password));
    }
}
