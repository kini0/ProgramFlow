<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('program')) ?? false;
    }

    public function rules(): array
    {
        return [
            'label'        => ['required', 'string', 'max:255'],
            'key'          => ['nullable', 'string', 'alpha_dash', 'max:80'],
            'type'         => ['required', Rule::in([
                'text', 'textarea', 'email', 'tel', 'url', 'date', 'number',
                'select', 'multiselect', 'checkbox', 'radio', 'file', 'video',
            ])],
            'options'      => ['nullable', 'array'],
            'options.*.label' => ['required_with:options', 'string', 'max:120'],
            'options.*.value' => ['required_with:options', 'string', 'max:120'],
            'is_required'  => ['nullable', 'boolean'],
            'help_text'    => ['nullable', 'string', 'max:500'],
        ];
    }
}
