@extends('layouts.app')
@section('title', 'Rapport — '.$report['program']->title)
@section('content')
    <a href="{{ route('admin.reports.index') }}" class="text-sm text-slate-500 hover:underline inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Retour au reporting
    </a>
    <h1 class="text-2xl font-bold mt-2 mb-6">Rapport : {{ $report['program']->title }}</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-stat-card label="Total candidatures" :value="$report['total']" />
        <x-stat-card label="Soumises" :value="$report['submitted']" tone="blue" />
        <x-stat-card label="Acceptées" :value="$report['accepted']" tone="emerald" />
        <x-stat-card label="Taux de sélection" :value="$report['selection_rate'].'%'" tone="amber" />
    </div>

    <div class="card mt-6">
        <div class="card-header"><h2 class="font-semibold">Répartition des candidatures</h2></div>
        <div class="card-body">
            <canvas id="chart"></canvas>
        </div>
    </div>

    <script type="module">
        import Chart from 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/+esm';
        new Chart(document.getElementById('chart'), {
            type: 'bar',
            data: {
                labels: @json($chart['labels']),
                datasets: [{ data: @json($chart['data']), backgroundColor: '#ec4899' }],
            },
            options: { plugins: { legend: { display: false } } },
        });
    </script>
@endsection
