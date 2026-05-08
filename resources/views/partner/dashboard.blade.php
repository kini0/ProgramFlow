@extends('layouts.app')
@section('title', 'Espace partenaire')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Espace partenaire</h1>

    @if(! $partner)
        <x-alert type="warning" message="Votre compte n'est associé à aucun partenaire. Contactez l'administrateur." />
    @else
        <div class="card mb-6">
            <div class="card-body">
                <h2 class="font-semibold">{{ $partner->name }}</h2>
                <p class="text-sm text-slate-600">{{ $partner->description }}</p>
                <p class="text-xs text-slate-400 mt-2">{{ $partner->type }} · {{ $partner->website }}</p>
            </div>
        </div>

        <h2 class="text-lg font-semibold mb-3">Programmes auxquels {{ $partner->name }} contribue</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($programs as $program)
                <div class="card card-body">
                    <h3 class="font-semibold">{{ $program->title }}</h3>
                    <div class="mt-2"><x-status-badge :label="$program->status->label()" :color="$program->status->color()" /></div>
                    <p class="mt-3 text-sm text-slate-600">📥 {{ $program->applications_count }} candidatures</p>
                </div>
            @empty
                <p class="text-slate-500 col-span-full">Aucun programme associé.</p>
            @endforelse
        </div>
    @endif
@endsection
