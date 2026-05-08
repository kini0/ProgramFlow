<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\ProgramStatus;
use App\Models\Program;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SelectionService
{
    public function __construct(private ApplicationRepositoryInterface $applications)
    {
    }

    /**
     * Renvoie le classement automatique pour la sélection finale.
     */
    public function ranking(Program $program, int $limit = 100): Collection
    {
        return $this->applications->topRanked($program, $limit);
    }

    /**
     * Pré-sélectionne automatiquement les N meilleurs candidats.
     */
    public function autoShortlist(Program $program, int $count): int
    {
        $top = $this->ranking($program, $count);
        $ids = $top->pluck('id');

        return $program->applications()
            ->whereIn('id', $ids)
            ->update([
                'status'      => ApplicationStatus::Shortlisted->value,
                'reviewed_at' => now(),
            ]);
    }

    /**
     * Bascule un programme en phase active après la sélection.
     */
    public function lockSelection(Program $program): Program
    {
        $program->update(['status' => ProgramStatus::Active->value]);
        return $program;
    }
}
