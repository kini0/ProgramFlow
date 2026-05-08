@extends('layouts.app')
@section('title', 'Modifier le programme')
@section('content')
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Modifier : {{ $program->title }}</h1>
    <form method="POST" action="{{ route('admin.programs.update', $program) }}" enctype="multipart/form-data" class="space-y-6 max-w-3xl">
        @csrf @method('PATCH')
        @include('admin.programs._form', ['program' => $program])
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Enregistrer</button>
            <a href="{{ route('admin.programs.show', $program) }}" class="btn-ghost">Annuler</a>
        </div>
    </form>
@endsection
