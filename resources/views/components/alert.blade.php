@props(['type' => 'info', 'message' => null, 'title' => null])
@php
    $colors = [
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
        'error'   => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        'info'    => 'bg-blue-50 border-blue-200 text-blue-800',
    ];
    $cls = $colors[$type] ?? $colors['info'];
@endphp
<div {{ $attributes->merge(['class' => 'mb-4 rounded-lg border p-4 '.$cls]) }} role="alert">
    @if($title)<p class="font-semibold mb-1">{{ $title }}</p>@endif
    <div class="text-sm">{{ $message ?? $slot }}</div>
</div>
