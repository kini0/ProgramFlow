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
        // L'utilisateur factory a email_verified_at = now() par défaut
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

    /* ============================================================ */
    /* Sécurité du flux d'inscription                                */
    /* ============================================================ */

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

    public function test_register_does_not_auto_login_the_user(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Awa',
            'last_name'  => 'Touré',
            'email'      => 'awa2@test.test',
            'password'   => 'Password!1234',
            'password_confirmation' => 'Password!1234',
        ]);

        // Sécurité : pas de connexion automatique après inscription
        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_register_does_not_verify_email_automatically(): void
    {
        $this->post('/register', [
            'first_name' => 'Awa',
            'last_name'  => 'Touré',
            'email'      => 'awa3@test.test',
            'password'   => 'Password!1234',
            'password_confirmation' => 'Password!1234',
        ]);

        $user = User::where('email', 'awa3@test.test')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at, 'L\'email ne doit pas être marqué vérifié à l\'inscription.');
    }

    public function test_unverified_user_cannot_login(): void
    {
        $user = User::factory()->unverified()->create();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        // Sécurité : la connexion est rejetée tant que l'email n'est pas vérifié
        $this->assertGuest();
    }

    public function test_signed_verification_link_marks_email_as_verified(): void
    {
        $user = User::factory()->unverified()->create();

        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->get($url)->assertRedirect(route('login'));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
