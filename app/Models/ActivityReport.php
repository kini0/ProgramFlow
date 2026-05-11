<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ActivityReportStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Rapport d'activité d'un programme.
 *
 * Les médias (PDF, images, vidéos) sont stockés via la relation polymorphique
 * Document, avec les catégories suivantes :
 *   - "report_file"    : fichier principal téléchargeable (PDF, DOCX)
 *   - "gallery_image"  : image de la galerie
 *   - "gallery_video"  : vidéo de la galerie
 */
class ActivityReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'program_id', 'program_session_id', 'title', 'slug', 'description',
        'content', 'activity_date', 'status', 'published_at',
        'created_by', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'published_at'  => 'datetime',
            'meta'          => 'array',
            'status'        => ActivityReportStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $r) {
            if (empty($r->slug) && !empty($r->title)) {
                $r->slug = static::generateUniqueSlug($r->title, $r->id);
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

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ProgramSession::class, 'program_session_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function reportFile(): MorphMany
    {
        return $this->documents()->where('category', 'report_file');
    }

    public function galleryImages(): MorphMany
    {
        return $this->documents()->where('category', 'gallery_image');
    }

    public function galleryVideos(): MorphMany
    {
        return $this->documents()->where('category', 'gallery_video');
    }

    /* ---------------------------------------------------------------- */
    /* Scopes                                                            */
    /* ---------------------------------------------------------------- */

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', ActivityReportStatus::Published->value);
    }

    public function scopeForProgram(Builder $q, int $programId): Builder
    {
        return $q->where('program_id', $programId);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isPublished(): bool
    {
        return $this->status === ActivityReportStatus::Published;
    }
}
