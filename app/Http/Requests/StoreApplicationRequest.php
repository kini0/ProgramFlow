<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation des réponses d'une candidature, en s'appuyant sur
 * les champs dynamiques définis pour le programme cible.
 *
 * Cette FormRequest est utilisée à la fois lors d'un POST (création)
 * et d'un PATCH (mise à jour). On résout donc le programme via la
 * candidature liée à la route.
 */
class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        // Le programme peut être passé soit directement (route programs.*)
        // soit indirectement via la candidature (route applications.update).
        $program = $this->route('program');
        if (! $program && ($application = $this->route('application'))) {
            $program = $application->program;
        }

        if (! $program) {
            return [];
        }

        $rules = [
            'motivation'      => ['nullable', 'string', 'max:5000'],
            'project_summary' => ['nullable', 'string', 'max:5000'],
        ];

        // Documents déjà uploadés par catégorie (clé du champ).
        $existingDocs = ($this->route('application') instanceof Application)
            ? $this->route('application')->documents->keyBy('category')
            : collect();

        foreach ($program->applicationFields as $field) {
            $isFile = in_array($field->type, ['file', 'video'], true);
            $hasExistingDoc = $isFile && $existingDocs->has($field->key);

            // Si un fichier est déjà présent en base, on assouplit la règle :
            // l'utilisateur n'a pas besoin de le re-uploader pour pouvoir
            // sauvegarder ses autres modifications. La règle "required" est
            // remplacée par "nullable".
            $rules['responses.'.$field->id] = $field->buildValidationRules($hasExistingDoc);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'responses.*.required' => 'Ce champ est obligatoire.',
            'responses.*.email'    => 'Adresse email invalide.',
            'responses.*.date'     => 'Date invalide.',
            'responses.*.numeric'  => 'Veuillez saisir un nombre.',
            'responses.*.url'      => 'URL invalide.',
            'responses.*.file'     => 'Fichier invalide.',
        ];
    }
}
