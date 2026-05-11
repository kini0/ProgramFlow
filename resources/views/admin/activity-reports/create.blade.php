@extends('layouts.app')
@section('title', 'Nouveau rapport')
@section('content')
    <a href="{{ route('admin.programs.activityReports.index', $program) }}" class="text-sm text-slate-500 hover:underline">← Rapports</a>
    <h1 class="text-2xl font-bold mt-2 mb-6">Nouveau rapport d'activité</h1>

    <form method="POST" action="{{ route('admin.programs.activityReports.store', $program) }}" enctype="multipart/form-data" class="space-y-6 max-w-3xl">
        @csrf
        @include('admin.activity-reports._form', ['report' => null])
        <div class="flex gap-3">
            <button name="status" value="draft" class="btn-secondary">💾 Enregistrer en brouillon</button>
            <button name="status" value="published" class="btn-primary">📢 Publier</button>
        </div>
    </form>
@endsection
