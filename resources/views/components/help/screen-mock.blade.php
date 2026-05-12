@props([
    'title' => 'Aperçu',
    'url'   => null,
    'image' => null, // chemin relatif sous public/help-screenshots/ (ex: "admin-users.png")
    'alt'   => null,
])
@php
    $imagePath = $image
        ? (str_starts_with($image, 'http') || str_starts_with($image, '/')
            ? $image
            : asset('help-screenshots/'.$image))
        : null;
    $imageExists = $image && (
        str_starts_with($image, 'http') ||
        file_exists(public_path('help-screenshots/'.ltrim($image, '/')))
    );
@endphp
<figure class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden my-6 not-prose">
    <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 border-b border-slate-200">
        <span class="w-3 h-3 rounded-full bg-red-400"></span>
        <span class="w-3 h-3 rounded-full bg-amber-400"></span>
        <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
        @if($url)
            <span class="ml-4 text-xs font-mono text-slate-500 bg-white rounded px-2 py-0.5 border border-slate-200">
                {{ $url }}
            </span>
        @endif
        <span class="ml-auto text-xs text-slate-400">{{ $title }}</span>
    </div>

    {{-- Si une vraie capture est fournie ET que le fichier existe, on l'affiche.
         Sinon on retombe sur le mockup HTML passé via slot. --}}
    @if($imageExists)
        <img src="{{ $imagePath }}"
             alt="{{ $alt ?? $title }}"
             class="block w-full h-auto bg-slate-50">
    @else
        <div class="p-6 bg-slate-50">
            {{ $slot }}
            @if($image && ! $imageExists)
                <p class="mt-3 text-xs text-amber-600 italic">
                    Capture "{{ $image }}" attendue dans <code>public/help-screenshots/</code>
                    — l'aperçu HTML est affiché à la place.
                </p>
            @endif
        </div>
    @endif
</figure>
