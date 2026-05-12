<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Centre d'aide — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1" defer></script>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    {{-- Top bar --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <nav class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-brand-700">
                <span class="bg-brand-600 text-white rounded-lg w-9 h-9 inline-flex items-center justify-center">PF</span>
                <span>ProgramFlow</span>
                <span class="text-slate-400 font-normal text-sm hidden sm:inline">· Centre d'aide</span>
            </a>
            <div class="flex items-center gap-3 text-sm">
                <a href="{{ route('help.index') }}" class="hover:text-brand-700 hidden md:inline">Accueil aide</a>
                <a href="{{ route('help.faq') }}" class="hover:text-brand-700 hidden md:inline">FAQ</a>
                <a href="{{ route('help.glossary') }}" class="hover:text-brand-700 hidden md:inline">Glossaire</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary text-sm">Mon espace</a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary text-sm">Connexion</a>
                @endauth
            </div>
        </nav>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-10 grid lg:grid-cols-12 gap-8">

        {{-- Sidebar des rôles --}}
        <aside class="lg:col-span-3">
            <div class="lg:sticky lg:top-24">
                <p class="text-xs uppercase text-slate-400 mb-3 font-semibold tracking-wider">Guides par rôle</p>
                <ul class="space-y-1 list-none p-0">
                    @php
                        $roles = [
                            'admin'      => ['Administrateur', 'shield-star'],
                            'organizer'  => ['Organisateur',   'briefcase'],
                            'jury'       => ['Membre du jury', 'scales'],
                            'candidate'  => ['Candidate',      'student'],
                            'partner'    => ['Partenaire',     'handshake'],
                        ];
                    @endphp
                    @foreach($roles as $key => [$label, $icon])
                        @php $active = request()->routeIs('help.role') && request()->route('role') === $key; @endphp
                        <li>
                            <a href="{{ route('help.role', $key) }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                                      {{ $active ? 'bg-brand-50 text-brand-700' : 'hover:bg-slate-100 text-slate-700' }}">
                                <x-icon :name="$icon" class="text-lg" />
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <p class="text-xs uppercase text-slate-400 mt-8 mb-3 font-semibold tracking-wider">Références</p>
                <ul class="space-y-1 list-none p-0">
                    <li><a href="{{ route('help.faq') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-slate-100"><x-icon name="question" /> FAQ</a></li>
                    <li><a href="{{ route('help.glossary') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-slate-100"><x-icon name="book-open" /> Glossaire</a></li>
                </ul>
            </div>
        </aside>

        {{-- Main content --}}
        <main class="lg:col-span-9 space-y-4 min-w-0">
            <div class="prose-help">
                @yield('content')
            </div>
        </main>
    </div>

    <footer class="bg-white border-t border-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-6 py-6 text-sm text-slate-500 flex items-center justify-between">
            <span>© {{ now()->year }} {{ config('programflow.foundation_name') }} — ProgramFlow</span>
            <a href="{{ route('home') }}" class="hover:text-brand-700">Retour à l'accueil</a>
        </div>
    </footer>
</body>
</html>
