<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — @yield('title')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1" defer></script>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
    <header class="bg-white border-b border-slate-200">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-brand-700">
                <span class="bg-brand-600 text-white rounded-lg w-9 h-9 inline-flex items-center justify-center">PF</span>
                ProgramFlow
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('help.index') }}" class="text-sm text-slate-600 hover:text-brand-700">Aide</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-secondary">Mon espace</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary">Postuler</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-300 text-center text-sm py-6">
        © {{ now()->year }} {{ config('programflow.foundation_name') }} — Propulsé par ProgramFlow
    </footer>
</body>
</html>
