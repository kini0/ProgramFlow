<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'organizer']) ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('partner')?->id;

        return [
            'name'          => ['required', 'string', 'max:255'],
            'type'          => ['required', Rule::in(['financier', 'technique', 'institutionnel', 'media', 'autre'])],
            'contact_name'  => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'website'       => ['nullable', 'url', 'max:255'],
            'description'   => ['nullable', 'string'],
            'is_active'     => ['nullable', 'boolean'],
            'logo'          => ['nullable', 'image', 'max:2048'],
            'user_id'       => ['nullable', Rule::exists('users', 'id')],
        ];
    }
}
