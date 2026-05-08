<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ProgramStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('program')) ?? false;
    }

    public function rules(): array
    {
        return [
            'title'             => ['sometimes', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description'       => ['nullable', 'string'],
            'objectives'        => ['nullable', 'string'],
            'eligibility'       => ['nullable', 'string'],
            'seats'             => ['nullable', 'integer', 'min:0'],
            'application_opens_at'  => ['nullable', 'date'],
            'application_closes_at' => ['nullable', 'date', 'after_or_equal:application_opens_at'],
            'starts_at'         => ['nullable', 'date'],
            'ends_at'           => ['nullable', 'date', 'after_or_equal:starts_at'],
            'status'            => ['nullable', Rule::in(array_column(ProgramStatus::cases(), 'value'))],
            'is_featured'       => ['nullable', 'boolean'],
            'cover_image'       => ['nullable', 'image', 'max:5120'],
        ];
    }
}
