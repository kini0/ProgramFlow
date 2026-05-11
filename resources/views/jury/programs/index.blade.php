@extends('layouts.app')
@section('title', 'Mes programmes')
@section('content')
    <h1 class="text-2xl font-bold mb-2">Mes programmes</h1>
    <p class="text-slate-500 mb-6">
        Programmes dont vous êtes membre du jury. Les candidatures vous sont
        attribuées automatiquement dès la clôture des candidatures.
    </p>

    @if($programs->isEmpty())
        <div class="card card-body text-center text-slate-500">
            Vous n'êtes associé à aucun programme. Demandez à l'administrateur de vous ajouter.
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($programs as $program)
                @php $isOpenForEval = $service->isProgramOpenForEvaluation($program); @endphp
                <div class="card card-body">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-slate-800">{{ $program->title }}</h3>
                            <p class="text-xs text-slate-400">{{ $program->slug }}</p>
                        </div>
                        <x-status-badge :label="$program->status->label()" :color="$program->status->color()" />
                    </div>

                    @if(! $isOpenForEval)
                        <p class="mt-3 text-xs text-amber-600 bg-amber-50 rounded px-2 py-1">
                            ⏳ Période de candidature en cours.
                            @if($program->application_closes_at)
                                Évaluation possible à partir du {{ $program->application_closes_at->copy()->addDay()->format('d/m/Y') }}.
                            @endif
                        </p>
                    @endif

                    <div class="grid grid-cols-3 gap-2 mt-4 text-center text-xs">
                        <div class="bg-slate-50 rounded p-2">
                            <p class="text-slate-400">Soumises</p>
                            <p class="text-xl font-bold text-slate-800">{{ $program->total_applications }}</p>
                        </div>
                        <div class="bg-amber-50 rounded p-2">
                            <p class="text-amber-700">À évaluer</p>
                            <p class="text-xl font-bold text-amber-700">{{ $program->my_pending_count }}</p>
                        </div>
                        <div class="bg-emerald-50 rounded p-2">
                            <p class="text-emerald-700">Faites</p>
                            <p class="text-xl font-bold text-emerald-700">{{ $program->my_done_count }}</p>
                        </div>
                    </div>

                    <a href="{{ route('jury.programs.show', $program) }}"
                       class="btn-primary mt-4 w-full text-sm @if(! $isOpenForEval && $program->my_pending_count === 0) opacity-50 @endif">
                        Voir les candidatures
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
