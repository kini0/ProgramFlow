@extends('layouts.app')
@section('title', 'Candidatures — '.$program->title)
@section('content')
    <h1 class="text-2xl font-bold mb-2">{{ $program->title }}</h1>
    <p class="text-slate-500 text-sm mb-6">Gestion des candidatures</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Total" :value="$stats['total']" />
        <x-stat-card label="Soumises" :value="$stats['submitted']" tone="blue" />
        <x-stat-card label="Présélection" :value="$stats['shortlisted']" tone="amber" />
        <x-stat-card label="Acceptées" :value="$stats['accepted']" tone="emerald" />
    </div>

    <form method="GET" class="card card-body grid md:grid-cols-3 gap-3 mb-6">
        <x-input name="search" label="Rechercher" :value="request('search')" />
        <x-select name="status" label="Statut" :options="collect($statuses)->mapWithKeys(fn($s)=>[$s->value=>$s->label()])->all()" :selected="request('status')" placeholder="Tous" />
        <div class="flex items-end gap-2">
            <button class="btn-secondary">Filtrer</button>
            <a href="{{ route('organizer.programs.selection.show', $program) }}" class="btn-primary">Sélection</a>
        </div>
    </form>

    <div class="card overflow-hidden">
        <table class="table-app">
            <thead class="bg-slate-50"><tr><th>Réf</th><th>Candidate</th><th>Statut</th><th>Score</th><th>Soumise le</th><th></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($applications as $app)
                <tr>
                    <td class="font-mono text-xs">{{ $app->reference }}</td>
                    <td class="font-medium">{{ $app->candidate?->full_name }}<br><span class="text-xs text-slate-400">{{ $app->candidate?->email }}</span></td>
                    <td><x-status-badge :label="$app->status->label()" :color="$app->status->color()" /></td>
                    <td>{{ $app->average_score ?? '—' }}</td>
                    <td>{{ $app->submitted_at?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td class="text-right"><a href="{{ route('organizer.programs.applications.show', [$program, $app]) }}" class="text-brand-600 text-sm hover:underline">Ouvrir</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-8 text-slate-400">Aucune candidature.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $applications->links() }}</div>
@endsection
