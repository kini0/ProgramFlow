@extends('layouts.app')
@section('title', 'Nouvelle session')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Planifier une session</h1>
    <form method="POST" action="{{ route('organizer.programs.sessions.store', $program) }}" class="space-y-4 max-w-2xl">
        @csrf
        <x-input name="title" label="Titre" required />
        <x-select name="type" label="Type" :options="['formation'=>'Formation','atelier'=>'Atelier','mentoring'=>'Mentoring','evenement'=>'Événement','autre'=>'Autre']" required />
        <x-textarea name="description" label="Description" />
        <div class="grid md:grid-cols-2 gap-3">
            <x-input type="datetime-local" name="starts_at" label="Début" required />
            <x-input type="datetime-local" name="ends_at" label="Fin" />
        </div>
        <x-input name="location" label="Lieu" />
        <x-select name="facilitator_id" label="Facilitateur" :options="$facilitators->pluck('full_name','id')->all()" placeholder="—" />
        <button class="btn-primary">Planifier</button>
    </form>
@endsection
