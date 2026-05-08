@extends('layouts.app')
@section('title', 'Nouvel utilisateur')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Créer un utilisateur</h1>
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 max-w-2xl">
        @csrf
        @include('admin.users._form', ['user' => null])
        <button class="btn-primary">Créer</button>
    </form>
@endsection
