<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — @yield('title', 'Connexion')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-50 via-white to-slate-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 text-2xl font-bold text-brand-700">
                <span class="bg-brand-600 text-white rounded-lg w-9 h-9 inline-flex items-center justify-center">PF</span>
                ProgramFlow
            </a>
            <p class="mt-2 text-sm text-slate-500">{{ config('programflow.foundation_name') }}</p>
        </div>
        <div class="card p-6">
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </div>
</body>
</html>
