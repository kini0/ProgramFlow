<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('program')) ?? false;
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'type'         => ['required', Rule::in(['formation', 'atelier', 'mentoring', 'evenement', 'autre'])],
            'description'  => ['nullable', 'string'],
            'location'     => ['nullable', 'string', 'max:255'],
            'is_online'    => ['nullable', 'boolean'],
            'online_link'  => ['nullable', 'url', 'max:500'],
            'starts_at'    => ['required', 'date'],
            'ends_at'      => ['nullable', 'date', 'after:starts_at'],
            'facilitator_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
