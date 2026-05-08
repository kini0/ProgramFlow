<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationResponse extends Model
{
    protected $fillable = [
        'application_id', 'application_field_id',
        'value', 'value_json',
    ];

    protected function casts(): array
    {
        return ['value_json' => 'array'];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(ApplicationField::class, 'application_field_id');
    }
}
