@extends('layouts.app')
@section('title', 'Nouveau programme')
@section('content')
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Créer un programme</h1>
    <form method="POST" action="{{ route('admin.programs.store') }}" enctype="multipart/form-data" class="space-y-6 max-w-3xl">
        @csrf
        @include('admin.programs._form')
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Créer le programme</button>
            <a href="{{ route('admin.programs.index') }}" class="btn-ghost">Annuler</a>
        </div>
    </form>
@endsection
