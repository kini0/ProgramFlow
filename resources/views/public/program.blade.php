@extends('layouts.public')
@section('title', $program->title)
@section('content')
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-6 py-12">
            <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-brand-700">← Retour</a>
            <h1 class="mt-2 text-3xl font-bold text-slate-800">{{ $program->title }}</h1>
            <p class="mt-3 text-slate-600 text-lg">{{ $program->short_description }}</p>

            <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-slate-600">
                <span>📅 Du {{ $program->starts_at?->format('d/m/Y') ?? '—' }} au {{ $program->ends_at?->format('d/m/Y') ?? '—' }}</span>
                <span>🪑 {{ $program->seats }} places</span>
                <span>⏰ Candidatures jusqu'au {{ $program->application_closes_at?->format('d/m/Y') ?? '—' }}</span>
            </div>

            <div class="mt-8 flex gap-3">
                @auth
                    @if($program->isAcceptingApplications())
                        <form method="POST" action="{{ route('candidate.applications.start', $program) }}">
                            @csrf
                            <button class="btn-primary">Postuler maintenant</button>
                        </form>
                    @else
                        <span class="badge bg-slate-100 text-slate-600">Candidatures fermées</span>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-primary">Créer un compte pour postuler</a>
                    <a href="{{ route('login') }}" class="btn-secondary">J'ai déjà un compte</a>
                @endauth
            </div>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-8">
        <div class="prose max-w-none">
            <h2>Description</h2>
            <div>{!! nl2br(e($program->description)) !!}</div>
        </div>
        <div class="space-y-6">
            <div class="card card-body">
                <h3 class="font-semibold mb-2">🎯 Objectifs</h3>
                <p class="text-sm text-slate-600 whitespace-pre-line">{{ $program->objectives }}</p>
            </div>
            <div class="card card-body">
                <h3 class="font-semibold mb-2">✅ Éligibilité</h3>
                <p class="text-sm text-slate-600 whitespace-pre-line">{{ $program->eligibility }}</p>
            </div>
        </div>
    </section>
@endsection
