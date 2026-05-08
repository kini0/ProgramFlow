<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Program;
use App\Models\User;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ApplicationRepository extends BaseRepository implements ApplicationRepositoryInterface
{
    public function __construct(Application $model)
    {
        parent::__construct($model);
    }

    public function findDraftFor(Program $program, User $user): ?Application
    {
        return $this->model->newQuery()
            ->where('program_id', $program->id)
            ->where('user_id', $user->id)
            ->where('status', ApplicationStatus::Draft->value)
            ->first();
    }

    public function paginateForProgram(Program $program, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyFilters(
            $this->model->newQuery()
                ->where('program_id', $program->id)
                ->with(['candidate', 'evaluations']),
            $filters
        )->paginate($perPage)->withQueryString();
    }

    public function paginateForCandidate(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->where('user_id', $user->id)
            ->with('program')
            ->orderByDesc('updated_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function topRanked(Program $program, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->newQuery()
            ->where('program_id', $program->id)
            ->whereNotNull('average_score')
            ->orderByDesc('average_score')
            ->orderByDesc('evaluations_count')
            ->with('candidate')
            ->limit($limit)
            ->get();
    }

    public function statsForProgram(Program $program): array
    {
        $base = $this->model->newQuery()->where('program_id', $program->id);

        return [
            'total'        => (clone $base)->count(),
            'submitted'    => (clone $base)->whereNotIn('status', [
                ApplicationStatus::Draft->value, ApplicationStatus::Withdrawn->value,
            ])->count(),
            'accepted'     => (clone $base)->where('status', ApplicationStatus::Accepted->value)->count(),
            'rejected'     => (clone $base)->where('status', ApplicationStatus::Rejected->value)->count(),
            'in_review'    => (clone $base)->where('status', ApplicationStatus::UnderReview->value)->count(),
            'shortlisted'  => (clone $base)->where('status', ApplicationStatus::Shortlisted->value)->count(),
            'avg_score'    => (clone $base)->whereNotNull('average_score')->avg('average_score'),
        ];
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $term = '%'.$filters['search'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('reference', 'like', $term)
                  ->orWhereHas('candidate', fn ($qq) => $qq
                      ->where('first_name', 'like', $term)
                      ->orWhere('last_name', 'like', $term)
                      ->orWhere('email', 'like', $term));
            });
        }
        if (!empty($filters['sort'])) {
            $direction = $filters['direction'] ?? 'desc';
            $query->orderBy($filters['sort'], $direction);
        } else {
            $query->orderByDesc('submitted_at');
        }
        return $query;
    }
}
