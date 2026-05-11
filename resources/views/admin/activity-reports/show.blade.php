@extends('layouts.app')
@section('title', $report->title)
@section('content')
    <a href="{{ route('admin.programs.activityReports.index', $program) }}" class="text-sm text-slate-500 hover:underline">← Rapports</a>

    <div class="flex items-start justify-between mt-2 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <x-status-badge :label="$report->status->label()" :color="$report->status->color()" />
                <span class="text-xs text-slate-400">📅 {{ $report->activity_date->format('d/m/Y') }}</span>
                <span class="text-xs text-slate-400">· par {{ $report->creator?->full_name ?? '—' }}</span>
                @if($report->published_at)<span class="text-xs text-slate-400">· publié le {{ $report->published_at->format('d/m/Y') }}</span>@endif
            </div>
            <h1 class="text-3xl font-bold mt-2">{{ $report->title }}</h1>
            @if($report->description)<p class="text-slate-600 mt-1">{{ $report->description }}</p>@endif
        </div>
        <div class="flex gap-2">
            @if(! $report->isPublished())
                <form method="POST" action="{{ route('admin.programs.activityReports.publish', [$program, $report]) }}">
                    @csrf
                    <button class="btn-primary">📢 Publier</button>
                </form>
            @endif
            <a href="{{ route('admin.programs.activityReports.edit', [$program, $report]) }}" class="btn-secondary">Modifier</a>
        </div>
    </div>

    {{-- Cover image --}}
    @if($report->galleryImages->isNotEmpty())
        @php $cover = $report->galleryImages->first(); @endphp
        <img src="{{ \Storage::url($cover->path) }}" class="w-full max-h-96 object-cover rounded-xl mb-6">
    @endif

    {{-- Contenu --}}
    @if($report->content)
        <div class="card mb-6">
            <div class="card-body prose max-w-none whitespace-pre-line">{!! e($report->content) !!}</div>
        </div>
    @endif

    {{-- Fichier principal --}}
    @if($report->reportFile->isNotEmpty())
        <div class="card mb-6">
            <div class="card-header"><h2 class="font-semibold">📄 Document</h2></div>
            <div class="card-body">
                @foreach($report->reportFile as $file)
                    <a href="{{ $file->url() }}" target="_blank" class="btn-secondary">
                        📥 Télécharger {{ $file->original_name }} ({{ $file->humanSize() }})
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Galerie images --}}
    @if($report->galleryImages->count() > 0)
        <div class="card mb-6">
            <div class="card-header"><h2 class="font-semibold">🖼 Galerie ({{ $report->galleryImages->count() }})</h2></div>
            <div class="card-body grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($report->galleryImages as $img)
                    <a href="{{ \Storage::url($img->path) }}" target="_blank">
                        <img src="{{ \Storage::url($img->path) }}" class="w-full h-32 object-cover rounded hover:opacity-90 transition">
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Vidéos --}}
    @if($report->galleryVideos->count() > 0)
        <div class="card mb-6">
            <div class="card-header"><h2 class="font-semibold">🎥 Vidéos</h2></div>
            <div class="card-body grid md:grid-cols-2 gap-4">
                @foreach($report->galleryVideos as $video)
                    <video controls class="w-full rounded">
                        <source src="{{ \Storage::url($video->path) }}" type="{{ $video->mime_type }}">
                    </video>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.programs.activityReports.destroy', [$program, $report]) }}"
          onsubmit="return confirm('Supprimer définitivement ce rapport ?')">
        @csrf @method('DELETE')
        <button class="btn-danger text-sm">Supprimer le rapport</button>
    </form>
@endsection
