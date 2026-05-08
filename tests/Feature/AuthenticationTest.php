<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_users_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password']);
        $this->assertGuest();
    }

    public function test_register_assigns_candidate_role(): void
    {
        $this->post('/register', [
            'first_name' => 'Awa',
            'last_name'  => 'Touré',
            'email'      => 'awa@test.test',
            'password'   => 'Password!1234',
            'password_confirmation' => 'Password!1234',
        ]);

        $user = User::where('email', 'awa@test.test')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('candidate'));
    }
}
