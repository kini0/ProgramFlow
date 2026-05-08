<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\ProgramStatus;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Partner;
use App\Models\Program;
use App\Models\User;
use App\Services\ProgramService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class ProgramsSeeder extends Seeder
{
    public function run(): void
    {
        $admin     = User::where('email', 'admin@programflow.test')->first();
        $organizer = User::where('email', 'organizer@programflow.test')->first();
        $juries    = User::role(UserRole::Jury->value)->get();
        $candidates = User::role(UserRole::Candidate->value)->get();

        /** @var ProgramService $service */
        $service = App::make(ProgramService::class);

        $program = Program::where('slug', 'leadership-feminin-2026')->first();
        if (! $program) {
            $program = $service->create([
                'title'             => 'Leadership Féminin 2026',
                'short_description' => 'Programme phare de la Fondation Bénianh dédié au leadership des femmes en Afrique de l\'Ouest.',
                'description'       => "Un parcours intensif de 6 mois mêlant formations, mentoring et financement pour des entrepreneures à fort potentiel.",
                'objectives'        => "Renforcer les compétences en leadership, accélérer la croissance d'un projet à impact, créer un réseau d'entraide.",
                'eligibility'       => "Femmes âgées de 22 à 40 ans, porteuses d'un projet à impact social ou économique.",
                'seats'             => 30,
                'application_opens_at'  => now()->subWeeks(2)->toDateString(),
                'application_closes_at' => now()->addWeeks(4)->toDateString(),
                'starts_at'         => now()->addMonths(2)->toDateString(),
                'ends_at'           => now()->addMonths(8)->toDateString(),
                'status'            => ProgramStatus::Open->value,
                'is_featured'       => true,
            ], $admin);
        }

        // Organisateur + jurys
        if ($organizer) {
            $program->members()->syncWithoutDetaching([$organizer->id => ['role' => 'organizer']]);
        }
        foreach ($juries as $j) {
            $program->members()->syncWithoutDetaching([$j->id => ['role' => 'jury']]);
        }

        // Partenaires
        $program->partners()->syncWithoutDetaching(Partner::pluck('id'));

        // Candidatures démo
        foreach ($candidates->take(8) as $i => $c) {
            Application::firstOrCreate(
                ['program_id' => $program->id, 'user_id' => $c->id],
                [
                    'status'         => $i % 2 === 0 ? ApplicationStatus::Submitted->value : ApplicationStatus::UnderReview->value,
                    'submitted_at'   => now()->subDays(rand(1, 14)),
                    'motivation'     => 'Je suis passionnée par l\'entrepreneuriat féminin et souhaite passer un cap décisif.',
                    'project_summary' => 'Création d\'une plateforme e-commerce dédiée aux artisanes locales.',
                ],
            );
        }
    }
}
