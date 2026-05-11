@extends('layouts.app')
@section('title', 'Sessions')
@section('content')
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold">Sessions du programme : {{ $program->title }}</h1>
        <a href="{{ route('organizer.programs.sessions.create', $program) }}" class="btn-primary">+ Planifier une session</a>
    </div>
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('organizer.programs.applications.index', $program) }}" class="btn-secondary text-sm">📥 Candidatures</a>
        <a href="{{ route('organizer.programs.selection.show', $program) }}" class="btn-secondary text-sm">🏆 Sélection</a>
        <a href="{{ route('admin.programs.activityReports.index', $program) }}" class="btn-secondary text-sm">📰 Rapports d'activité</a>
    </div>
    <div class="space-y-3">
        @forelse($program->sessions as $s)
            <div class="card card-body flex justify-between items-center">
                <div>
                    <p class="text-xs text-slate-400 uppercase">{{ $s->type }}</p>
                    <h3 class="font-semibold">{{ $s->title }}</h3>
                    <p class="text-sm text-slate-500">📅 {{ $s->starts_at->format('d/m/Y H:i') }} · {{ $s->facilitator?->full_name ?? 'Facilitateur ?' }}</p>
                </div>
                <a href="{{ route('organizer.programs.sessions.show', [$program, $s]) }}" class="btn-secondary">Ouvrir</a>
            </div>
        @empty
            <p class="text-slate-500">Aucune session planifiée.</p>
        @endforelse
    </div>
@endsection
