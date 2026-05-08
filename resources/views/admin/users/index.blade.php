@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Utilisateurs</h1>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">+ Nouvel utilisateur</a>
    </div>
    <form method="GET" class="card card-body grid md:grid-cols-3 gap-3 mb-6">
        <x-input name="search" label="Rechercher" :value="request('search')" />
        <x-select name="role" label="Rôle" :options="collect($roles)->mapWithKeys(fn($r) => [$r->value => $r->label()])->all()" :selected="request('role')" placeholder="Tous" />
        <div class="flex items-end"><button class="btn-secondary">Filtrer</button></div>
    </form>
    <div class="card overflow-hidden">
        <table class="table-app">
            <thead class="bg-slate-50"><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Statut</th><th></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($users as $user)
                <tr>
                    <td class="font-medium">{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-emerald-100 text-emerald-700">Actif</span>
                        @else
                            <span class="badge bg-slate-100 text-slate-700">Inactif</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-brand-600 hover:underline text-sm">Modifier</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-8 text-slate-400">Aucun utilisateur.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
@endsection
