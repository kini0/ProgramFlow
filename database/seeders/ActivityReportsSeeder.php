<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ActivityReportStatus;
use App\Models\ActivityReport;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityReportsSeeder extends Seeder
{
    public function run(): void
    {
        $program = Program::where('slug', 'leadership-feminin-2026')->first();
        if (! $program) {
            return;
        }
        $admin = User::where('email', 'admin@programflow.test')->first();

        $samples = [
            [
                'title'         => 'Atelier inaugural — Lancement du programme',
                'description'   => 'Première rencontre des participantes avec les mentors et les partenaires.',
                'activity_date' => now()->subDays(20)->toDateString(),
                'content'       => "Le programme a été officiellement lancé en présence des 30 participantes sélectionnées. La journée a été rythmée par des présentations, des échanges et un moment de réseautage.",
                'status'        => ActivityReportStatus::Published->value,
            ],
            [
                'title'         => 'Formation — Leadership transformationnel',
                'description'   => 'Module animé par la coach internationale Awa Diallo.',
                'activity_date' => now()->subDays(10)->toDateString(),
                'content'       => "Une journée intense consacrée aux fondamentaux du leadership transformationnel. Les participantes ont travaillé en binômes sur des cas concrets.",
                'status'        => ActivityReportStatus::Draft->value,
            ],
        ];

        foreach ($samples as $s) {
            ActivityReport::firstOrCreate(
                ['program_id' => $program->id, 'title' => $s['title']],
                array_merge($s, [
                    'created_by'   => $admin?->id,
                    'published_at' => $s['status'] === ActivityReportStatus::Published->value ? now() : null,
                ]),
            );
        }
    }
}
