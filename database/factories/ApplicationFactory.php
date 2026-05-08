<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'program_id'      => Program::factory(),
            'user_id'         => User::factory(),
            'status'          => ApplicationStatus::Draft->value,
            'motivation'      => $this->faker->paragraph(),
            'project_summary' => $this->faker->paragraph(),
        ];
    }

    public function submitted(): static
    {
        return $this->state([
            'status'       => ApplicationStatus::Submitted->value,
            'submitted_at' => now(),
        ]);
    }
}
