<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Program;
use Illuminate\Database\Eloquent\Collection;

interface ProgramRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Program;

    public function findPublic(string $slug): ?Program;

    public function listOpen(): Collection;

    public function listArchived(): Collection;

    public function statsForDashboard(): array;
}
