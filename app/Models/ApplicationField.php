<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Champ dynamique d'un formulaire de candidature pour un programme donné.
 */
class ApplicationField extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id', 'section', 'label', 'key', 'type',
        'options', 'is_required', 'help_text',
        'validation_rules', 'order_column',
    ];

    protected function casts(): array
    {
        return [
            'options'          => 'array',
            'validation_rules' => 'array',
            'is_required'      => 'boolean',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class);
    }

    /**
     * Construit dynamiquement les règles de validation à partir des
     * paramètres du champ (type + custom rules).
     *
     * @return array<int, string>
     */
    public function buildValidationRules(): array
    {
        $rules = [];
        if ($this->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        $rules[] = match ($this->type) {
            'email'        => 'email',
            'tel'          => 'string',
            'url'          => 'url',
            'date'         => 'date',
            'number'       => 'numeric',
            'multiselect',
            'checkbox'     => 'array',
            'file', 'video' => 'file',
            default        => 'string',
        };

        if (in_array($this->type, ['select', 'radio'], true) && is_array($this->options)) {
            $rules[] = 'in:'.implode(',', array_column($this->options, 'value'));
        }

        if (is_array($this->validation_rules)) {
            $rules = array_merge($rules, $this->validation_rules);
        }

        return $rules;
    }
}
