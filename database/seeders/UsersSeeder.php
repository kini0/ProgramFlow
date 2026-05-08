<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['admin@programflow.test',     'Admin',      'Bénianh',  UserRole::Admin],
            ['organizer@programflow.test', 'Aïcha',      'Koné',     UserRole::Organizer],
            ['jury1@programflow.test',     'Mariam',     'Diabaté',  UserRole::Jury],
            ['jury2@programflow.test',     'Fatou',      'Sow',      UserRole::Jury],
            ['candidate@programflow.test', 'Awa',        'Touré',    UserRole::Candidate],
            ['partner@programflow.test',   'Partenaire', 'Demo',     UserRole::Partner],
        ];

        foreach ($accounts as [$email, $first, $last, $role]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $first,
                    'last_name'  => $last,
                    'password'   => Hash::make('password'),
                    'is_active'  => true,
                    'email_verified_at' => now(),
                ],
            );
            if (! $user->hasRole($role->value)) {
                $user->assignRole($role->value);
            }
        }

        // Quelques candidates supplémentaires pour le démo
        User::factory()->count(15)->create()->each(function (User $u) {
            $u->assignRole(UserRole::Candidate->value);
        });
    }
}
