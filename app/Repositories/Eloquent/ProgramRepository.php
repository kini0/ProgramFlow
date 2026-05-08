<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\ProgramStatus;
use App\Models\Program;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProgramRepository extends BaseRepository implements ProgramRepositoryInterface
{
    public function __construct(Program $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Program
    {
        return $this->model->newQuery()->where('slug', $slug)->first();
    }

    public function findPublic(string $slug): ?Program
    {
        return $this->model->newQuery()->public()->where('slug', $slug)->first();
    }

    public function listOpen(): Collection
    {
        return $this->model->newQuery()
            ->acceptingApplications()
            ->orderBy('application_closes_at')
            ->get();
    }

    public function listArchived(): Collection
    {
        return $this->model->newQuery()
            ->archived()
            ->orderByDesc('ends_at')
            ->get();
    }

    public function statsForDashboard(): array
    {
        return [
            'total'        => $this->model->newQuery()->count(),
            'open'         => $this->model->newQuery()->where('status', ProgramStatus::Open->value)->count(),
            'active'       => $this->model->newQuery()->where('status', ProgramStatus::Active->value)->count(),
            'completed'    => $this->model->newQuery()->where('status', ProgramStatus::Completed->value)->count(),
            'archived'     => $this->model->newQuery()->where('status', ProgramStatus::Archived->value)->count(),
        ];
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['search'])) {
            $term = '%'.$filters['search'].'%';
            $query->where(fn ($q) => $q->where('title', 'like', $term)
                ->orWhere('short_description', 'like', $term));
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        return $query->orderByDesc('created_at');
    }
}
