<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartnersSeeder extends Seeder
{
    public function run(): void
    {
        $partnerUser = User::where('email', 'partner@programflow.test')->first();

        $samples = [
            ['UNICEF',         'institutionnel'],
            ['Orange Foundation', 'financier'],
            ['Africa Tech Hub', 'technique'],
        ];

        foreach ($samples as [$name, $type]) {
            Partner::firstOrCreate(
                ['name' => $name],
                [
                    'type'        => $type,
                    'description' => 'Partenaire de la Fondation Bénianh.',
                    'is_active'   => true,
                    'user_id'     => $name === 'Orange Foundation' ? $partnerUser?->id : null,
                ],
            );
        }
    }
}
