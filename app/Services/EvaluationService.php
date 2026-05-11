<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\EvaluationStatus;
use App\Models\Application;
use App\Models\Evaluation;
use App\Models\Program;
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

    /**
     * Attribution explicite (override admin) — envoie un email d'attribution.
     */
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
     * Crée silencieusement les évaluations manquantes pour le couple
     * (jury, candidatures soumises du programme).
     *
     * Règle métier : un jury membre du programme évalue automatiquement
     * TOUTES les candidatures soumises de ce programme — pas besoin
     * d'attribution manuelle. Cette méthode est idempotente : elle ne
     * crée que les évaluations manquantes.
     *
     * @return int  Nombre d'évaluations créées (utile pour le feedback).
     */
    public function ensureEvaluationsForJury(Program $program, User $jury): int
    {
        // Vérification : ce programme accepte-t-il l'évaluation ?
        if (! $this->isProgramOpenForEvaluation($program)) {
            return 0;
        }

        // Vérification : le jury est-il bien membre de ce programme ?
        $isJury = $jury->programsAsJury()->whereKey($program->id)->exists();
        if (! $isJury) {
            return 0;
        }

        $submittedAppIds = $program->applications()
            ->whereNotIn('status', [
                ApplicationStatus::Draft->value,
                ApplicationStatus::Withdrawn->value,
            ])
            ->whereDoesntHave('evaluations', fn ($q) => $q->where('jury_id', $jury->id))
            ->pluck('id');

        $now = now();
        $rows = $submittedAppIds->map(fn ($id) => [
            'application_id' => $id,
            'jury_id'        => $jury->id,
            'status'         => EvaluationStatus::Assigned->value,
            'created_at'     => $now,
            'updated_at'     => $now,
        ])->all();

        if (! empty($rows)) {
            Evaluation::insert($rows);
        }

        return count($rows);
    }

    /**
     * Variante : auto-création pour TOUS les jurys d'un programme.
     * Appelée typiquement quand l'organisateur clôture les candidatures.
     */
    public function ensureEvaluationsForProgram(Program $program): int
    {
        $created = 0;
        foreach ($program->juries as $jury) {
            $created += $this->ensureEvaluationsForJury($program, $jury);
        }
        return $created;
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

            // Si la candidature était encore "submitted", on la fait passer
            // en "under_review" maintenant qu'une évaluation a été soumise.
            if ($evaluation->application->status === ApplicationStatus::Submitted) {
                $evaluation->application->update([
                    'status' => ApplicationStatus::UnderReview->value,
                ]);
            }

            $this->applicationService->recomputeScores($evaluation->application);

            return $evaluation->refresh();
        });
    }

    /**
     * Liste les évaluations en attente pour un jury,
     * en s'assurant au passage que toutes ses attributions
     * automatiques sont bien en base.
     */
    public function pendingForJury(User $jury): Collection
    {
        // Auto-création paresseuse : pour chaque programme dont le jury
        // est membre, on crée les évaluations manquantes.
        foreach ($jury->programsAsJury as $program) {
            $this->ensureEvaluationsForJury($program, $jury);
        }

        return $this->evaluations->pendingForJury($jury);
    }

    /**
     * Détermine si un programme est ouvert à l'évaluation par le jury.
     * Critères :
     *   - Période de candidature terminée (application_closes_at < now), OU
     *   - Statut du programme déjà passé en review/selection/active/completed
     */
    public function isProgramOpenForEvaluation(Program $program): bool
    {
        // Si le statut est passé après "open", c'est ouvert
        if (! in_array($program->status->value, [
            \App\Enums\ProgramStatus::Draft->value,
            \App\Enums\ProgramStatus::Published->value,
            \App\Enums\ProgramStatus::Archived->value,
        ], true)) {
            // Mais on n'accepte pas si on est encore en phase "open" ET que la
            // date de clôture n'est pas passée
            if ($program->status->value === \App\Enums\ProgramStatus::Open->value
                && $program->application_closes_at
                && $program->application_closes_at->isFuture()) {
                return false;
            }
            return true;
        }
        return false;
    }
}
