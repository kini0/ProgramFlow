<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProgramStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property ProgramStatus $status
 */
class Program extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'cover_image_path', 'short_description', 'description',
        'objectives', 'eligibility', 'seats',
        'application_opens_at', 'application_closes_at',
        'starts_at', 'ends_at',
        'status', 'is_featured', 'settings', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'application_opens_at'  => 'date',
            'application_closes_at' => 'date',
            'starts_at'             => 'date',
            'ends_at'               => 'date',
            'is_featured'           => 'boolean',
            'settings'              => 'array',
            'status'                => ProgramStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Program $program) {
            if (empty($program->slug) && !empty($program->title)) {
                $program->slug = static::generateUniqueSlug($program->title, $program->id);
            }
        });
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }

    /* ---------------------------------------------------------------- */
    /* Relations                                                         */
    /* ---------------------------------------------------------------- */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'partner_program')
            ->withPivot('role')->withTimestamps();
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'program_user')
            ->withPivot('role')->withTimestamps();
    }

    public function organizers(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'organizer');
    }

    public function juries(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'jury');
    }

    public function participants(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'participant');
    }

    public function applicationFields(): HasMany
    {
        return $this->hasMany(ApplicationField::class)->orderBy('order_column');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function evaluationCriteria(): HasMany
    {
        return $this->hasMany(EvaluationCriterion::class)->orderBy('order_column');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ProgramSession::class)->orderBy('starts_at');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activityReports(): HasMany
    {
        return $this->hasMany(ActivityReport::class)->orderByDesc('activity_date');
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

    public function scopePublic(Builder $q): Builder
    {
        return $q->whereIn('status', [
            ProgramStatus::Published->value,
            ProgramStatus::Open->value,
        ]);
    }

    public function scopeAcceptingApplications(Builder $q): Builder
    {
        return $q->where('status', ProgramStatus::Open->value)
            ->where(function ($q) {
                $q->whereNull('application_closes_at')
                  ->orWhereDate('application_closes_at', '>=', now());
            });
    }

    public function scopeArchived(Builder $q): Builder
    {
        return $q->where('status', ProgramStatus::Archived->value);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isAcceptingApplications(): bool
    {
        return $this->status === ProgramStatus::Open
            && (! $this->application_closes_at || $this->application_closes_at->isFuture());
    }
}
