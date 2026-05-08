<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\EvaluationStatus;
use App\Models\Application;
use App\Models\Evaluation;
use App\Models\User;
use App\Notifications\EvaluationAssignedNotification;
use App\Repositories\Contracts\EvaluationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EvaluationService
{
    public function __construct(
        private EvaluationRepositoryInterface $evaluations,
        private ApplicationService $applicationService,
    ) {
    }

    public function assign(Application $application, User $jury): Evaluation
    {
        $evaluation = $this->evaluations->assign($application, $jury);
        $jury->notify(new EvaluationAssignedNotification($evaluation));

        if ($application->status === ApplicationStatus::Submitted) {
            $application->update(['status' => ApplicationStatus::UnderReview->value]);
        }

        return $evaluation;
    }

    /**
     * Soumet une évaluation finale avec les scores par critère.
     *
     * @param  array<int, array{criterion_id:int, score:float, comment?:string}>  $scores
     */
    public function submit(Evaluation $evaluation, array $scores, ?string $comment = null): Evaluation
    {
        return DB::transaction(function () use ($evaluation, $scores, $comment) {
            $totalRaw    = 0.0;
            $totalWeight = 0.0;
            $weighted    = 0.0;

            foreach ($scores as $row) {
                $criterion = $evaluation->application->program->evaluationCriteria
                    ->firstWhere('id', $row['criterion_id']);
                if (! $criterion) {
                    continue;
                }
                $score = (float) $row['score'];

                $evaluation->scores()->updateOrCreate(
                    ['evaluation_criterion_id' => $criterion->id],
                    ['score' => $score, 'comment' => $row['comment'] ?? null],
                );

                $totalRaw   += $score;
                $weighted   += $score * $criterion->weight;
                $totalWeight += $criterion->weight;
            }

            $evaluation->update([
                'status'         => EvaluationStatus::Submitted->value,
                'comment'        => $comment,
                'submitted_at'   => now(),
                'total_score'    => round($totalRaw, 2),
                'weighted_score' => $totalWeight > 0 ? round($weighted / $totalWeight, 2) : round($totalRaw, 2),
            ]);

            $this->applicationService->recomputeScores($evaluation->application);

            return $evaluation->refresh();
        });
    }

    public function pendingForJury(User $jury): Collection
    {
        return $this->evaluations->pendingForJury($jury);
    }
}
