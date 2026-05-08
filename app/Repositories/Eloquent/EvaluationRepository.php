<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\EvaluationStatus;
use App\Models\Application;
use App\Models\Evaluation;
use App\Models\User;
use App\Repositories\Contracts\EvaluationRepositoryInterface;

class EvaluationRepository extends BaseRepository implements EvaluationRepositoryInterface
{
    public function __construct(Evaluation $model)
    {
        parent::__construct($model);
    }

    public function pendingForJury(User $jury): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->newQuery()
            ->where('jury_id', $jury->id)
            ->whereIn('status', [
                EvaluationStatus::Assigned->value,
                EvaluationStatus::InProgress->value,
            ])
            ->with(['application.candidate', 'application.program'])
            ->orderBy('created_at')
            ->get();
    }

    public function findFor(Application $application, User $jury): ?Evaluation
    {
        return $this->model->newQuery()
            ->where('application_id', $application->id)
            ->where('jury_id', $jury->id)
            ->first();
    }

    public function assign(Application $application, User $jury): Evaluation
    {
        return $this->model->newQuery()->firstOrCreate(
            ['application_id' => $application->id, 'jury_id' => $jury->id],
            ['status' => EvaluationStatus::Assigned->value],
        );
    }
}
