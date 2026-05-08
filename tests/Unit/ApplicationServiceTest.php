<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\ApplicationStatus;
use App\Enums\ProgramStatus;
use App\Models\Application;
use App\Models\Program;
use App\Models\User;
use App\Services\ApplicationService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_starts_or_resumes_a_draft(): void
    {
        $service = app(ApplicationService::class);
        $user    = User::factory()->create();
        $program = Program::factory()->open()->create();

        $a = $service->startOrResumeDraft($program, $user);
        $b = $service->startOrResumeDraft($program, $user);

        $this->assertEquals($a->id, $b->id, 'Le brouillon existant doit être réutilisé.');
        $this->assertEquals(ApplicationStatus::Draft, $a->status);
    }

    public function test_submission_changes_status_and_sets_timestamp(): void
    {
        $service = app(ApplicationService::class);
        $program = Program::factory()->open()->create();
        $user    = User::factory()->create();
        // Application::user() n'existe pas (la relation s'appelle candidate())
        // → on passe explicitement les FK pour éviter l'auto-detect de ->for().
        $app = Application::factory()->create([
            'program_id' => $program->id,
            'user_id'    => $user->id,
        ]);

        $service->submit($app);

        $app->refresh();
        $this->assertEquals(ApplicationStatus::Submitted, $app->status);
        $this->assertNotNull($app->submitted_at);
    }

    public function test_cannot_apply_to_a_closed_program(): void
    {
        $service = app(ApplicationService::class);
        $user    = User::factory()->create();
        $program = Program::factory()->create(['status' => ProgramStatus::Draft->value]);

        $this->expectException(\RuntimeException::class);
        $service->startOrResumeDraft($program, $user);
    }
}
