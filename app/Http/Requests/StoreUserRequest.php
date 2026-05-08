<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::Admin->value) ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('user')?->id;

        return [
            'first_name'  => ['required', 'string', 'max:120'],
            'last_name'   => ['required', 'string', 'max:120'],
            'email'       => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone'       => ['nullable', 'string', 'max:30'],
            'role'        => ['required', Rule::in(UserRole::values())],
            'is_active'   => ['nullable', 'boolean'],
            'password'    => $id ? ['nullable', Password::defaults()] : ['required', 'confirmed', Password::defaults()],
        ];
    }
}
