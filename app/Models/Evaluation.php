<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EvaluationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Évaluation d'une candidature par un membre du jury.
 *
 * @property EvaluationStatus $status
 * @property float|null $total_score
 * @property float|null $weighted_score
 */
class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 'jury_id', 'status',
        'comment', 'total_score', 'weighted_score', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at'   => 'datetime',
            'status'         => EvaluationStatus::class,
            'total_score'    => 'decimal:2',
            'weighted_score' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function jury(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jury_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(EvaluationScore::class);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->whereIn('status', [
            EvaluationStatus::Assigned->value,
            EvaluationStatus::InProgress->value,
        ]);
    }
}
