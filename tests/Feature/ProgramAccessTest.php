<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ProgramAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        // Spatie Permission met en cache les permissions ; on purge entre tests.
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_candidate_cannot_access_admin(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::Candidate->value);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::Admin->value);

        $this->actingAs($user)->get('/admin')->assertOk();
    }
}
