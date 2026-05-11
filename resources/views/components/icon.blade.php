@props([
    'name',                // ex: "pencil-simple", "trophy"
    'weight'  => 'regular', // thin | light | regular | bold | fill | duotone
])
@php
    $weightClass = match ($weight) {
        'thin'    => 'ph-thin',
        'light'   => 'ph-light',
        'bold'    => 'ph-bold',
        'fill'    => 'ph-fill',
        'duotone' => 'ph-duotone',
        default   => 'ph',
    };
@endphp
<i {{ $attributes->merge(['class' => $weightClass.' ph-'.$name]) }} aria-hidden="true"></i>
