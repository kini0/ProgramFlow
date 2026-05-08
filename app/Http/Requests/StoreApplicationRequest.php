<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation des réponses d'une candidature, en s'appuyant sur
 * les champs dynamiques définis pour le programme cible.
 */
class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        $program = $this->route('program');
        if (! $program) {
            return [];
        }

        $rules = [
            'motivation'      => ['nullable', 'string', 'max:5000'],
            'project_summary' => ['nullable', 'string', 'max:5000'],
        ];

        foreach ($program->applicationFields as $field) {
            $rules['responses.'.$field->id] = $field->buildValidationRules();
        }

        return $rules;
    }
}
