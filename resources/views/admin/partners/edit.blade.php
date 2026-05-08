@extends('layouts.app')
@section('title', 'Modifier le partenaire')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Modifier : {{ $partner->name }}</h1>
    <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data" class="space-y-4 max-w-2xl">
        @csrf @method('PATCH')
        @include('admin.partners._form')
        <button class="btn-primary">Enregistrer</button>
    </form>
@endsection
