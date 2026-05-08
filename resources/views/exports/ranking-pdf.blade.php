<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Classement — {{ $program->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        h1 { color: #be185d; margin: 0 0 4px; }
        .meta { color: #6b7280; font-size: 10px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
        th { background: #f9fafb; text-transform: uppercase; font-size: 9px; color: #6b7280; }
        .rank { font-weight: bold; }
        .score { font-weight: bold; color: #be185d; }
    </style>
</head>
<body>
    <h1>Classement — {{ $program->title }}</h1>
    <p class="meta">Édité le {{ now()->format('d/m/Y H:i') }} · {{ config('programflow.foundation_name') }}</p>
    <table>
        <thead>
            <tr><th>Rang</th><th>Référence</th><th>Candidate</th><th>Score</th><th>Évaluations</th><th>Statut</th></tr>
        </thead>
        <tbody>
        @foreach($ranking as $i => $app)
            <tr>
                <td class="rank">#{{ $i + 1 }}</td>
                <td>{{ $app->reference }}</td>
                <td>{{ $app->candidate?->full_name }}</td>
                <td class="score">{{ $app->average_score }}</td>
                <td>{{ $app->evaluations_count }}</td>
                <td>{{ $app->status->label() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
