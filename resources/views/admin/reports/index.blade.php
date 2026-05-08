@extends('layouts.app')
@section('title', 'Reporting')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Reporting & statistiques</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <x-stat-card label="Programmes" :value="$global['total']" tone="brand" />
        <x-stat-card label="Candidatures" :value="$global['total_applications']" tone="blue" />
        <x-stat-card label="Soumises" :value="$global['submitted_applications']" tone="amber" />
        <x-stat-card label="Acceptées" :value="$global['accepted_applications']" tone="emerald" />
    </div>

    <div class="card overflow-hidden">
        <div class="card-header"><h2 class="font-semibold">Programmes récents</h2></div>
        <table class="table-app">
            <thead class="bg-slate-50"><tr><th>Titre</th><th>Statut</th><th>Candidatures</th><th></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @foreach($programs as $p)
                <tr>
                    <td class="font-medium">{{ $p->title }}</td>
                    <td><x-status-badge :label="$p->status->label()" :color="$p->status->color()" /></td>
                    <td>{{ $p->applications->count() }}</td>
                    <td class="text-right"><a href="{{ route('admin.reports.program', $p) }}" class="text-brand-600 hover:underline text-sm">Voir le rapport</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
