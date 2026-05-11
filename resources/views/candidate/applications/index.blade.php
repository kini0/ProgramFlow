@extends('layouts.app')
@section('title', 'Mes candidatures')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Mes candidatures</h1>
    <div class="card overflow-hidden">
        <table class="table-app">
            <thead class="bg-slate-50"><tr><th>Réf</th><th>Programme</th><th>Statut</th><th>Mise à jour</th><th></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($applications as $app)
                <tr>
                    <td class="font-mono text-xs">{{ $app->reference }}</td>
                    <td>{{ $app->program?->title }}</td>
                    <td><x-status-badge :label="$app->status->label()" :color="$app->status->color()" /></td>
                    <td>{{ $app->updated_at->format('d/m/Y') }}</td>
                    <td class="text-right space-x-2">
                        @if($app->isEditable())
                            <a href="{{ route('candidate.applications.edit', $app) }}" class="text-brand-600 text-sm hover:underline">
                                {{ $app->isDraft() ? 'Continuer' : 'Modifier' }}
                            </a>
                            <a href="{{ route('candidate.applications.show', $app) }}" class="text-slate-500 text-sm hover:underline">Voir</a>
                        @else
                            <a href="{{ route('candidate.applications.show', $app) }}" class="text-brand-600 text-sm hover:underline">Voir</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-8 text-slate-400">Aucune candidature.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $applications->links() }}</div>
@endsection
