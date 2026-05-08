@props(['label', 'color' => 'gray'])
@php
    $palette = [
        'gray'    => 'bg-slate-100 text-slate-700',
        'blue'    => 'bg-blue-100 text-blue-700',
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'amber'   => 'bg-amber-100 text-amber-700',
        'red'     => 'bg-red-100 text-red-700',
        'purple'  => 'bg-purple-100 text-purple-700',
        'orange'  => 'bg-orange-100 text-orange-700',
        'teal'    => 'bg-teal-100 text-teal-700',
        'slate'   => 'bg-slate-200 text-slate-800',
        'brand'   => 'bg-brand-100 text-brand-700',
    ];
    $cls = $palette[$color] ?? $palette['gray'];
@endphp
<span class="badge {{ $cls }}">{{ $label }}</span>
