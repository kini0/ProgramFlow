@extends('layouts.app')
@section('title', 'Modifier le rapport')
@section('content')
    <a href="{{ route('admin.programs.activityReports.show', [$program, $report]) }}" class="text-sm text-slate-500 hover:underline">← Voir le rapport</a>
    <h1 class="text-2xl font-bold mt-2 mb-6">Modifier : {{ $report->title }}</h1>

    <form method="POST" action="{{ route('admin.programs.activityReports.update', [$program, $report]) }}" enctype="multipart/form-data" class="space-y-6 max-w-3xl">
        @csrf @method('PATCH')
        @include('admin.activity-reports._form')
        <button class="btn-primary">Enregistrer</button>
    </form>

    <h2 class="text-lg font-semibold mt-8 mb-3">Médias actuels</h2>
    <div class="grid md:grid-cols-3 gap-4">
        @foreach($report->documents as $doc)
            <div class="card card-body">
                <p class="text-xs text-slate-400 uppercase">{{ $doc->category }}</p>
                @if($doc->category === 'gallery_image')
                    <img src="{{ \Storage::url($doc->path) }}" class="w-full h-32 object-cover rounded mt-2">
                @elseif($doc->category === 'gallery_video')
                    <video src="{{ \Storage::url($doc->path) }}" class="w-full mt-2" controls></video>
                @else
                    <p class="text-sm mt-2">📎 {{ $doc->original_name }} ({{ $doc->humanSize() }})</p>
                    <a href="{{ $doc->url() }}" class="text-brand-600 text-sm hover:underline">Télécharger</a>
                @endif
                <form method="POST" action="{{ route('admin.programs.activityReports.media.destroy', [$program, $report, $doc]) }}"
                      class="mt-2" onsubmit="return confirm('Supprimer ce média ?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-500 hover:underline">Supprimer</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
