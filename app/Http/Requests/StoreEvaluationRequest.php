<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('evaluation')) ?? false;
    }

    public function rules(): array
    {
        $evaluation = $this->route('evaluation');
        $rules = [
            'comment'           => ['nullable', 'string', 'max:5000'],
            'scores'            => ['required', 'array', 'min:1'],
            'scores.*.criterion_id' => ['required', 'integer', 'exists:evaluation_criteria,id'],
            'scores.*.score'    => ['required', 'numeric', 'min:0'],
            'scores.*.comment'  => ['nullable', 'string', 'max:2000'],
        ];

        if ($evaluation) {
            // Score max dynamique selon le critère
            foreach ($evaluation->application->program->evaluationCriteria as $crit) {
                $rules['scores.*.score'][] = 'max:'.$crit->max_score;
            }
        }

        return $rules;
    }
}
