@extends('layouts.public')
@section('title', 'Accueil')
@section('content')
    <section class="bg-gradient-to-br from-brand-600 to-brand-800 text-white">
        <div class="max-w-7xl mx-auto px-6 py-20 lg:py-28 text-center">
            <h1 class="text-4xl lg:text-5xl font-bold mb-4">{{ config('programflow.foundation_name') }}</h1>
            <p class="text-lg lg:text-xl text-brand-100 max-w-2xl mx-auto">
                Découvrez nos programmes d'accompagnement et postulez en quelques minutes.
            </p>
            <a href="#programmes" class="mt-8 inline-flex btn bg-white text-brand-700 hover:bg-slate-100">Voir les programmes ouverts</a>
        </div>
    </section>

    <section id="programmes" class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-bold text-slate-800 mb-8">Programmes ouverts aux candidatures</h2>
        @if($openPrograms->isEmpty())
            <p class="text-slate-500">Aucun programme n'est actuellement ouvert. Revenez prochainement.</p>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($openPrograms as $program)
                    <article class="card overflow-hidden">
                        @if($program->cover_image_path)
                            <img src="{{ Storage::url($program->cover_image_path) }}" alt="" class="h-40 w-full object-cover">
                        @else
                            <div class="h-40 bg-gradient-to-br from-brand-400 to-brand-600"></div>
                        @endif
                        <div class="card-body">
                            <h3 class="font-semibold text-lg text-slate-800">{{ $program->title }}</h3>
                            <p class="mt-2 text-sm text-slate-600 line-clamp-3">{{ $program->short_description }}</p>
                            <p class="mt-4 text-xs text-slate-500">
                                Clôture : {{ $program->application_closes_at?->format('d/m/Y') ?? '—' }}
                            </p>
                            <a href="{{ route('public.program', $program->slug) }}" class="btn-secondary mt-4 w-full justify-center">En savoir plus</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
