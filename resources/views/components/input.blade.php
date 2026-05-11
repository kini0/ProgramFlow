@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'required' => false, 'help' => null])
@php
    // Convertit "responses[123]" en "responses.123" pour qu'old() fonctionne
    // correctement après une erreur de validation Laravel.
    $oldKey = preg_replace('/\[(.+?)\]/', '.$1', $name);
    $renderedValue = old($oldKey, $value);
@endphp
<div>
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $renderedValue }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-input']) }}
    />
    @if($help)<p class="mt-1 text-xs text-slate-500">{{ $help }}</p>@endif
    @error($oldKey)<p class="form-error">{{ $message }}</p>@enderror
</div>
