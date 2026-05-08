@extends('layouts.app')
@section('title', 'Modifier un utilisateur')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Modifier : {{ $user->full_name }}</h1>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4 max-w-2xl">
        @csrf @method('PATCH')
        @include('admin.users._form', ['user' => $user])
        <button class="btn-primary">Enregistrer</button>
    </form>

    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mt-6"
          onsubmit="return confirm('Supprimer cet utilisateur ?')">
        @csrf @method('DELETE')
        <button class="btn-danger text-sm">Supprimer</button>
    </form>
@endsection
