@props(['label', 'value', 'icon' => null, 'tone' => 'brand'])
@php
    $tones = ['brand' => 'text-brand-600 bg-brand-50', 'emerald' => 'text-emerald-600 bg-emerald-50', 'amber' => 'text-amber-600 bg-amber-50', 'blue' => 'text-blue-600 bg-blue-50', 'slate' => 'text-slate-600 bg-slate-100'];
@endphp
<div class="stat-card">
    <div class="flex items-start justify-between">
        <div>
            <div class="label">{{ $label }}</div>
            <div class="value">{{ $value }}</div>
        </div>
        @if($icon)
            <div class="rounded-lg w-10 h-10 inline-flex items-center justify-center {{ $tones[$tone] ?? $tones['brand'] }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
