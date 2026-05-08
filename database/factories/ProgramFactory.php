<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProgramStatus;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        return [
            'title'      => 'Programme '.$this->faker->words(3, true),
            'short_description' => $this->faker->sentence(),
            'description'       => $this->faker->paragraphs(3, true),
            'seats'      => $this->faker->numberBetween(10, 50),
            'application_opens_at'  => now()->subWeeks(2)->toDateString(),
            'application_closes_at' => now()->addWeeks(4)->toDateString(),
            'starts_at'  => now()->addMonths(2)->toDateString(),
            'ends_at'    => now()->addMonths(8)->toDateString(),
            'status'     => ProgramStatus::Draft->value,
        ];
    }

    public function open(): static
    {
        return $this->state(['status' => ProgramStatus::Open->value]);
    }
}
