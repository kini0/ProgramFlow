@extends('layouts.app')
@section('title', 'Nouveau partenaire')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Créer un partenaire</h1>
    <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data" class="space-y-4 max-w-2xl">
        @csrf
        @include('admin.partners._form', ['partner' => null])
        <button class="btn-primary">Créer</button>
    </form>
@endsection
