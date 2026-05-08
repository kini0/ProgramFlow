@extends('layouts.app')
@section('title', 'Mon espace')
@section('content')
    <h1 class="text-2xl font-bold text-slate-800 mb-2">Bonjour, {{ auth()->user()->first_name }} 👋</h1>
    <p class="text-slate-500 mb-8">Suivez vos candidatures et découvrez de nouveaux programmes.</p>

    <h2 class="text-lg font-semibold text-slate-700 mb-3">Mes candidatures récentes</h2>
    @if($applications->isEmpty())
        <div class="card card-body text-center text-slate-500">
            Vous n'avez pas encore de candidature. Découvrez les programmes ouverts ci-dessous.
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($applications as $app)
                <div class="card card-body">
                    <p class="text-xs font-mono text-slate-400">{{ $app->reference }}</p>
                    <h3 class="font-semibold mt-1">{{ $app->program?->title }}</h3>
                    <div class="mt-2"><x-status-badge :label="$app->status->label()" :color="$app->status->color()" /></div>
                    <div class="mt-4 flex gap-2">
                        @if($app->isEditable())
                            <a href="{{ route('candidate.applications.edit', $app) }}" class="btn-primary text-sm">Continuer</a>
                        @else
                            <a href="{{ route('candidate.applications.show', $app) }}" class="btn-secondary text-sm">Détails</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $applications->links() }}</div>
    @endif

    <h2 class="text-lg font-semibold text-slate-700 mt-10 mb-3">Programmes ouverts</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($openPrograms as $program)
            <div class="card card-body">
                <h3 class="font-semibold">{{ $program->title }}</h3>
                <p class="mt-2 text-sm text-slate-600 line-clamp-3">{{ $program->short_description }}</p>
                <p class="mt-3 text-xs text-slate-400">Clôture : {{ $program->application_closes_at?->format('d/m/Y') ?? '—' }}</p>
                <form method="POST" action="{{ route('candidate.applications.start', $program) }}" class="mt-3">
                    @csrf
                    <button class="btn-primary w-full">Postuler</button>
                </form>
            </div>
        @empty
            <p class="text-slate-500 col-span-full">Aucun programme actuellement ouvert.</p>
        @endforelse
    </div>
@endsection
