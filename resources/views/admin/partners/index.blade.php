@extends('layouts.app')
@section('title', 'Partenaires')
@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Partenaires</h1>
        <a href="{{ route('admin.partners.create') }}" class="btn-primary">+ Nouveau partenaire</a>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($partners as $partner)
            <div class="card card-body">
                <div class="flex items-center gap-3">
                    @if($partner->logo_path)
                        <img src="{{ Storage::url($partner->logo_path) }}" class="w-12 h-12 rounded">
                    @else
                        <div class="w-12 h-12 rounded bg-brand-100 text-brand-700 flex items-center justify-center font-bold">
                            {{ mb_strtoupper(mb_substr($partner->name,0,2)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold">{{ $partner->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $partner->type }}</p>
                    </div>
                </div>
                <p class="mt-3 text-sm text-slate-600 line-clamp-3">{{ $partner->description }}</p>
                <a href="{{ route('admin.partners.edit', $partner) }}" class="mt-4 inline-block text-sm text-brand-600">Modifier</a>
            </div>
        @empty
            <p class="col-span-full text-slate-500">Aucun partenaire.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $partners->links() }}</div>
@endsection
