@extends('layouts.app')
@section('title', 'Mes programmes')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Mes programmes</h1>
    <div class="grid md:grid-cols-2 gap-4">
        @forelse($programs as $program)
            <div class="card card-body">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-800">{{ $program->title }}</h3>
                        <p class="text-xs text-slate-400">{{ $program->slug }}</p>
                    </div>
                    <x-status-badge :label="$program->status->label()" :color="$program->status->color()" />
                </div>

                <div class="grid grid-cols-3 gap-2 mt-4 text-center text-xs">
                    <div class="bg-slate-50 rounded p-2">
                        <p class="text-slate-400">Candidatures</p>
                        <p class="text-xl font-bold text-slate-800">{{ $program->applications_count }}</p>
                    </div>
                    <div class="bg-slate-50 rounded p-2">
                        <p class="text-slate-400">Sessions</p>
                        <p class="text-xl font-bold text-slate-800">{{ $program->sessions()->count() }}</p>
                    </div>
                    <div class="bg-slate-50 rounded p-2">
                        <p class="text-slate-400">Rapports</p>
                        <p class="text-xl font-bold text-slate-800">{{ $program->activityReports()->count() }}</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a href="{{ route('organizer.programs.applications.index', $program) }}" class="btn-secondary text-sm">📥 Candidatures</a>
                    <a href="{{ route('organizer.programs.selection.show', $program) }}" class="btn-secondary text-sm">🏆 Sélection</a>
                    <a href="{{ route('organizer.programs.sessions.index', $program) }}" class="btn-secondary text-sm">📅 Sessions</a>
                    <a href="{{ route('admin.programs.activityReports.index', $program) }}" class="btn-secondary text-sm">📰 Rapports</a>
                </div>
            </div>
        @empty
            <p class="text-slate-500 col-span-full">Aucun programme à afficher. Demandez à l'administrateur de vous associer à un programme.</p>
        @endforelse
    </div>
@endsection
