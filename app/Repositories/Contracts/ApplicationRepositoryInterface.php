<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Application;
use App\Models\Program;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ApplicationRepositoryInterface extends BaseRepositoryInterface
{
    public function findDraftFor(Program $program, User $user): ?Application;

    public function paginateForProgram(Program $program, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function paginateForCandidate(User $user, int $perPage = 15): LengthAwarePaginator;

    public function topRanked(Program $program, int $limit = 50): \Illuminate\Database\Eloquent\Collection;

    public function statsForProgram(Program $program): array;
}
