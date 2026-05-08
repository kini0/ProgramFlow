@props(['user'])
@php
    use App\Enums\UserRole;
    $items = collect();

    if ($user?->hasRole(UserRole::Admin->value)) {
        $items = collect([
            ['route' => 'admin.dashboard', 'label' => 'Tableau de bord', 'icon' => 'home'],
            ['route' => 'admin.programs.index', 'label' => 'Programmes', 'icon' => 'briefcase'],
            ['route' => 'admin.partners.index', 'label' => 'Partenaires', 'icon' => 'handshake'],
            ['route' => 'admin.users.index',    'label' => 'Utilisateurs', 'icon' => 'users'],
            ['route' => 'admin.reports.index',  'label' => 'Reporting', 'icon' => 'chart'],
        ]);
    } elseif ($user?->hasRole(UserRole::Organizer->value)) {
        $items = collect([
            ['route' => 'organizer.dashboard', 'label' => 'Mes programmes', 'icon' => 'home'],
        ]);
    } elseif ($user?->hasRole(UserRole::Jury->value)) {
        $items = collect([
            ['route' => 'jury.dashboard', 'label' => 'À évaluer', 'icon' => 'check'],
        ]);
    } elseif ($user?->hasRole(UserRole::Candidate->value)) {
        $items = collect([
            ['route' => 'candidate.dashboard',          'label' => 'Mon tableau de bord', 'icon' => 'home'],
            ['route' => 'candidate.applications.index','label' => 'Mes candidatures', 'icon' => 'doc'],
        ]);
    } elseif ($user?->hasRole(UserRole::Partner->value)) {
        $items = collect([
            ['route' => 'partner.dashboard', 'label' => 'Espace partenaire', 'icon' => 'handshake'],
        ]);
    }
@endphp
<aside class="hidden lg:flex w-64 flex-col bg-white border-r border-slate-200">
    <div class="px-6 py-5 border-b border-slate-100">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-brand-700">
            <span class="bg-brand-600 text-white rounded-lg w-9 h-9 inline-flex items-center justify-center">PF</span>
            ProgramFlow
        </a>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-1">
        @foreach($items as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ $active ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-100' }}">
                <span class="w-5 h-5 inline-flex items-center justify-center text-slate-400">●</span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
    <div class="px-4 py-4 border-t border-slate-100 text-xs text-slate-400">
        v1.0 — {{ config('programflow.foundation_name') }}
    </div>
</aside>
