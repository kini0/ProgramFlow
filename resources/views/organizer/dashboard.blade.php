@extends('layouts.app')
@section('title', 'Mes programmes')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Mes programmes</h1>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($programs as $program)
            <div class="card card-body">
                <h3 class="font-semibold text-slate-800">{{ $program->title }}</h3>
                <p class="text-xs text-slate-400">{{ $program->slug }}</p>
                <div class="mt-2"><x-status-badge :label="$program->status->label()" :color="$program->status->color()" /></div>
                <p class="mt-3 text-sm text-slate-600">📥 {{ $program->applications_count }} candidatures</p>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('organizer.programs.applications.index', $program) }}" class="btn-secondary text-sm">Candidatures</a>
                    <a href="{{ route('organizer.programs.selection.show', $program) }}" class="btn-secondary text-sm">Sélection</a>
                </div>
            </div>
        @empty
            <p class="text-slate-500">Aucun programme à afficher.</p>
        @endforelse
    </div>
@endsection
