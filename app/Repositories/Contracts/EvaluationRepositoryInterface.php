<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Application;
use App\Models\Evaluation;
use App\Models\User;

interface EvaluationRepositoryInterface extends BaseRepositoryInterface
{
    public function pendingForJury(User $jury): \Illuminate\Database\Eloquent\Collection;

    public function findFor(Application $application, User $jury): ?Evaluation;

    public function assign(Application $application, User $jury): Evaluation;
}
