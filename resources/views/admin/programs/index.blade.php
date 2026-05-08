@extends('layouts.app')
@section('title', 'Programmes')
@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Programmes</h1>
        <a href="{{ route('admin.programs.create') }}" class="btn-primary">+ Nouveau programme</a>
    </div>

    <form method="GET" class="card card-body grid md:grid-cols-3 gap-3 mb-6">
        <x-input name="search" label="Rechercher" :value="request('search')" />
        <x-select name="status" label="Statut" :options="collect($statuses)->mapWithKeys(fn($s) => [$s->value => $s->label()])->all()" :selected="request('status')" placeholder="Tous" />
        <div class="flex items-end"><button class="btn-secondary">Filtrer</button></div>
    </form>

    <div class="card overflow-hidden">
        <table class="table-app">
            <thead class="bg-slate-50">
                <tr><th>Titre</th><th>Statut</th><th>Période</th><th>Places</th><th></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($programs as $program)
                <tr>
                    <td>
                        <a href="{{ route('admin.programs.show', $program) }}" class="font-medium text-slate-800 hover:text-brand-700">{{ $program->title }}</a>
                        <p class="text-xs text-slate-400">{{ $program->slug }}</p>
                    </td>
                    <td><x-status-badge :label="$program->status->label()" :color="$program->status->color()" /></td>
                    <td>{{ $program->starts_at?->format('d/m/Y') ?? '—' }} → {{ $program->ends_at?->format('d/m/Y') ?? '—' }}</td>
                    <td>{{ $program->seats }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.programs.edit', $program) }}" class="text-brand-600 hover:underline text-sm">Modifier</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-8 text-slate-400">Aucun programme.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $programs->links() }}</div>
@endsection
