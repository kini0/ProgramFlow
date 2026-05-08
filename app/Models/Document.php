<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'documentable_type', 'documentable_id', 'label',
        'original_name', 'disk', 'path', 'mime_type',
        'size', 'category', 'uploaded_by',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * URL d'accès. Pour les disques privés (cas par défaut sur "documents"),
     * pointe vers la route protégée DocumentController@download. Pour le
     * disque "public" (logos, avatars…), retourne l'URL publique directe.
     */
    public function url(): string
    {
        if ($this->disk === 'public') {
            return Storage::disk('public')->url($this->path);
        }
        return route('documents.download', $this);
    }

    public function humanSize(): string
    {
        $bytes = (int) $this->size;
        $units = ['B', 'Ko', 'Mo', 'Go'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return number_format($bytes, $i ? 1 : 0, ',', ' ').' '.$units[$i];
    }
}
