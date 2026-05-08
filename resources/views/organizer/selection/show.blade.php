@extends('layouts.app')
@section('title', 'Sélection — '.$program->title)
@section('content')
    <h1 class="text-2xl font-bold mb-2">Sélection finale</h1>
    <p class="text-slate-500 mb-6">{{ $program->title }}</p>

    <div class="card mb-6">
        <div class="card-header flex justify-between"><h2 class="font-semibold">Actions</h2></div>
        <div class="card-body grid md:grid-cols-3 gap-3">
            <form method="POST" action="{{ route('organizer.programs.selection.shortlist', $program) }}" class="flex gap-2">
                @csrf
                <input type="number" name="count" min="1" value="{{ $program->seats }}" class="form-input w-24">
                <button class="btn-primary">Pré-sélectionner top N</button>
            </form>
            <a href="{{ route('organizer.programs.selection.export.excel', $program) }}" class="btn-secondary">Export Excel</a>
            <a href="{{ route('organizer.programs.selection.export.pdf', $program) }}" class="btn-secondary">Export PDF</a>
        </div>
    </div>

    <div class="card overflow-hidden">
        <table class="table-app">
            <thead class="bg-slate-50"><tr><th>Rang</th><th>Candidate</th><th>Score</th><th>Évaluations</th><th>Statut</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @foreach($ranking as $i => $app)
                <tr>
                    <td class="font-bold">#{{ $i + 1 }}</td>
                    <td>{{ $app->candidate?->full_name }}<br><span class="text-xs text-slate-400">{{ $app->candidate?->email }}</span></td>
                    <td class="font-bold text-brand-700">{{ $app->average_score }}</td>
                    <td>{{ $app->evaluations_count }}</td>
                    <td><x-status-badge :label="$app->status->label()" :color="$app->status->color()" /></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <form method="POST" action="{{ route('organizer.programs.selection.lock', $program) }}" class="mt-6">
        @csrf
        <button class="btn-primary">Verrouiller la sélection et activer le programme</button>
    </form>
@endsection
