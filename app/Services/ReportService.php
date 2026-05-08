<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Program;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use App\Repositories\Contracts\ProgramRepositoryInterface;

class ReportService
{
    public function __construct(
        private ProgramRepositoryInterface $programs,
        private ApplicationRepositoryInterface $applications,
    ) {
    }

    public function globalDashboard(): array
    {
        $stats = $this->programs->statsForDashboard();
        $stats['total_applications'] = \App\Models\Application::count();
        $stats['submitted_applications'] = \App\Models\Application::submitted()->count();
        $stats['accepted_applications'] = \App\Models\Application::where('status', \App\Enums\ApplicationStatus::Accepted->value)->count();
        $stats['users_total'] = \App\Models\User::count();
        return $stats;
    }

    public function programReport(Program $program): array
    {
        $stats = $this->applications->statsForProgram($program);
        $stats['program'] = $program;
        $stats['selection_rate'] = $stats['submitted'] > 0
            ? round(($stats['accepted'] / $stats['submitted']) * 100, 1)
            : 0.0;
        return $stats;
    }

    public function applicationsByStatusForChart(Program $program): array
    {
        $stats = $this->applications->statsForProgram($program);
        return [
            'labels' => ['Soumises', 'En revue', 'Présélectionnées', 'Acceptées', 'Refusées'],
            'data'   => [
                $stats['submitted'],
                $stats['in_review'],
                $stats['shortlisted'],
                $stats['accepted'],
                $stats['rejected'],
            ],
        ];
    }
}
