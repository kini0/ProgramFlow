<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Tableau de bord')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    {{-- Phosphor Icons (1300+ icônes SVG via web-font, plusieurs poids) --}}
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1" defer></script>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
    <div class="flex min-h-screen">
        <x-sidebar :user="auth()->user()" />

        <div class="flex-1 flex flex-col">
            <x-topbar />
            <main class="flex-1 p-6 lg:p-10 max-w-screen-2xl w-full mx-auto">
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif
                @if (session('info'))
                    <x-alert type="info" :message="session('info')" />
                @endif

                {{-- Affichage GLOBAL des erreurs de validation pour ne pas laisser
                     l'utilisateur dans le flou si l'erreur ne peut pas être liée
                     à un champ précis (typiquement les uploads et requêtes files). --}}
                @if ($errors->any())
                    <x-alert type="error" title="Le formulaire contient des erreurs">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                @yield('content')
                {{ $slot ?? '' }}
            </main>
            <footer class="text-center text-xs text-slate-400 py-6">
                © {{ now()->year }} {{ config('programflow.foundation_name') }} · ProgramFlow
            </footer>
        </div>
    </div>
</body>
</html>
