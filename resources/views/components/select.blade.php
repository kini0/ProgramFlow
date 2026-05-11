@props(['name', 'label' => null, 'options' => [], 'selected' => null, 'placeholder' => null, 'required' => false])
@php
    $oldKey = preg_replace('/\[(.+?)\]/', '.$1', $name);
    $current = old($oldKey, $selected);
@endphp
<div>
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}" {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => 'form-input']) }}>
        @if($placeholder)<option value="">{{ $placeholder }}</option>@endif
        @foreach($options as $value => $label)
            <option value="{{ $value }}" @selected((string) $current === (string) $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error($oldKey)<p class="form-error">{{ $message }}</p>@enderror
</div>
