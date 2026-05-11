@extends('layouts.app')
@section('title', 'Rapports — '.$program->title)
@section('content')
    <a href="{{ route('admin.programs.show', $program) }}" class="text-sm text-slate-500 hover:underline">← Retour au programme</a>
    <div class="flex items-center justify-between mt-2 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Rapports d'activité</h1>
            <p class="text-slate-500">{{ $program->title }}</p>
        </div>
        <a href="{{ route('admin.programs.activityReports.create', $program) }}" class="btn-primary">+ Nouveau rapport</a>
    </div>

    @if($program->activityReports->isEmpty())
        <div class="card card-body text-center text-slate-500">
            Aucun rapport pour ce programme. Créez le premier rapport pour documenter une activité.
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($program->activityReports as $report)
                @php $cover = $report->galleryImages->first(); @endphp
                <div class="card overflow-hidden">
                    @if($cover)
                        <img src="{{ \Storage::url($cover->path) }}" class="w-full h-40 object-cover">
                    @else
                        <div class="h-40 bg-gradient-to-br from-brand-200 to-brand-400"></div>
                    @endif
                    <div class="card-body">
                        <div class="flex items-center gap-2 mb-2">
                            <x-status-badge :label="$report->status->label()" :color="$report->status->color()" />
                            <span class="text-xs text-slate-400">{{ $report->activity_date->format('d/m/Y') }}</span>
                        </div>
                        <h3 class="font-semibold">{{ $report->title }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mt-1">{{ $report->description }}</p>
                        <p class="mt-3 text-xs text-slate-400">Par {{ $report->creator?->full_name ?? '—' }}</p>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.programs.activityReports.show', [$program, $report]) }}" class="btn-secondary text-sm flex-1">Voir</a>
                            <a href="{{ route('admin.programs.activityReports.edit', [$program, $report]) }}" class="btn-ghost text-sm">Éditer</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
