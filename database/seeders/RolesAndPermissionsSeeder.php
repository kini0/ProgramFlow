<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage users',
            'manage programs',
            'manage partners',
            'view reports',
            'evaluate applications',
            'submit applications',
            'view program results',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $admin     = Role::firstOrCreate(['name' => UserRole::Admin->value, 'guard_name' => 'web']);
        $organizer = Role::firstOrCreate(['name' => UserRole::Organizer->value, 'guard_name' => 'web']);
        $jury      = Role::firstOrCreate(['name' => UserRole::Jury->value, 'guard_name' => 'web']);
        $candidate = Role::firstOrCreate(['name' => UserRole::Candidate->value, 'guard_name' => 'web']);
        $partner   = Role::firstOrCreate(['name' => UserRole::Partner->value, 'guard_name' => 'web']);

        $admin->syncPermissions(Permission::all());
        $organizer->syncPermissions(['manage programs', 'manage partners', 'view reports']);
        $jury->syncPermissions(['evaluate applications']);
        $candidate->syncPermissions(['submit applications']);
        $partner->syncPermissions(['view program results']);
    }
}
