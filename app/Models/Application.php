<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property string $reference
 * @property ApplicationStatus $status
 */
class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference', 'program_id', 'user_id', 'status',
        'motivation', 'project_summary',
        'average_score', 'evaluations_count',
        'submitted_at', 'reviewed_at', 'decided_at',
        'decided_by', 'decision_reason', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at'  => 'datetime',
            'decided_at'   => 'datetime',
            'meta'         => 'array',
            'status'       => ApplicationStatus::class,
            'average_score' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Application $a) {
            if (empty($a->reference)) {
                $a->reference = static::generateReference();
            }
        });

        static::observe(\App\Observers\ApplicationObserver::class);
    }

    public static function generateReference(): string
    {
        do {
            $ref = sprintf('PF-%s-%s', now()->format('Y'), strtoupper(Str::random(6)));
        } while (static::where('reference', $ref)->exists());

        return $ref;
    }

    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    /* ---------------------------------------------------------------- */
    /* Relations                                                         */
    /* ---------------------------------------------------------------- */

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /* ---------------------------------------------------------------- */
    /* Scopes                                                            */
    /* ---------------------------------------------------------------- */

    public function scopeSubmitted(Builder $q): Builder
    {
        return $q->whereNotIn('status', [
            ApplicationStatus::Draft->value,
            ApplicationStatus::Withdrawn->value,
        ]);
    }

    public function scopeForProgram(Builder $q, int $programId): Builder
    {
        return $q->where('program_id', $programId);
    }

    public function isDraft(): bool
    {
        return $this->status === ApplicationStatus::Draft;
    }

    /**
     * Une candidature est éditable si :
     *   - elle est en brouillon (toujours), ou
     *   - elle est soumise / en revue ET le programme accepte
     *     encore les candidatures (avant la clôture).
     *
     * Cela permet à la candidate de corriger / compléter son dossier
     * tant que la période de candidature n'est pas terminée, même après
     * une première soumission.
     */
    public function isEditable(): bool
    {
        if ($this->status === ApplicationStatus::Draft) {
            return true;
        }

        if (in_array($this->status, [
            ApplicationStatus::Submitted,
            ApplicationStatus::UnderReview,
        ], true)) {
            return $this->program?->isAcceptingApplications() ?? false;
        }

        return false;
    }
}
