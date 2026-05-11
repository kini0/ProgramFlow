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

    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('organizer.programs.applications.index', $program) }}" class="btn-secondary text-sm"><x-icon name="tray" /> Candidatures</a>
        <a href="{{ route('organizer.programs.selection.show', $program) }}" class="btn-secondary text-sm"><x-icon name="trophy" /> Sélection</a>
        <a href="{{ route('organizer.programs.sessions.index', $program) }}" class="btn-secondary text-sm"><x-icon name="calendar" /> Sessions</a>
        <a href="{{ route('admin.programs.activityReports.index', $program) }}" class="btn-secondary text-sm"><x-icon name="newspaper" /> Rapports d'activité</a>
    </div>

    @if(in_array($program->status->value, ['open', 'published']) && $stats['submitted'] > 0)
        @php $closesAt = $program->application_closes_at; @endphp
        <div class="card card-body mb-6 bg-amber-50 border-amber-200 flex items-center justify-between">
            <div>
                <p class="font-semibold text-amber-800 flex items-center gap-2">
                    <x-icon name="lock" /> Clôturer les candidatures et démarrer l'évaluation
                </p>
                <p class="text-sm text-amber-700 mt-1">
                    @if($closesAt && $closesAt->isFuture())
                        Date prévue de clôture : <b>{{ $closesAt->format('d/m/Y') }}</b>.
                    @else
                        La période de candidature est passée. Lancez l'évaluation pour activer les jurys.
                    @endif
                    @if($program->juries->isEmpty())
                        <span class="text-red-600 font-semibold inline-flex items-center gap-1">
                            <x-icon name="warning" weight="fill" /> Aucun jury associé — l'évaluation sera impossible.
                        </span>
                    @else
                        Les <b>{{ $program->juries->count() }} jury(s)</b> du programme évalueront automatiquement les {{ $stats['submitted'] }} candidature(s) soumise(s).
                    @endif
                </p>
            </div>
            @if($program->juries->isNotEmpty())
                <form method="POST" action="{{ route('organizer.programs.startEvaluation', $program) }}"
                      onsubmit="return confirm('Clôturer les candidatures et démarrer l\'évaluation ?');">
                    @csrf
                    <button class="btn-primary">Démarrer l'évaluation</button>
                </form>
            @endif
        </div>
    @endif

    <form method="GET" class="card card-body grid md:grid-cols-3 gap-3 mb-6">
        <x-input name="search" label="Rechercher" :value="request('search')" />
        <x-select name="status" label="Statut" :options="collect($statuses)->mapWithKeys(fn($s)=>[$s->value=>$s->label()])->all()" :selected="request('status')" placeholder="Tous" />
        <div class="flex items-end gap-2">
            <button class="btn-secondary">Filtrer</button>
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
