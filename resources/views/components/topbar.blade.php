@php $user = auth()->user(); @endphp
<header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <h1 class="font-semibold text-slate-700">@yield('page-title', 'Tableau de bord')</h1>
    </div>
    <div class="flex items-center gap-4" x-data="{ open: false }">
        @if($user)
            <span class="text-sm text-slate-500 hidden sm:inline">
                {{ $user->full_name }}
                <span class="text-xs text-slate-400">· {{ $user->roles->first()?->name }}</span>
            </span>
            <div class="relative">
                <button @click="open = !open" class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-semibold">
                    {{ $user->initials }}
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg py-1 z-30">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">Mon profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block w-full text-left px-4 py-2 text-sm hover:bg-slate-50 text-red-600">Déconnexion</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</header>
