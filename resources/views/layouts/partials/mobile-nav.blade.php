@php
    $links = $role === 'super_admin'
        ? [
            ['label' => 'Beranda', 'route' => 'platform.dashboard', 'icon' => 'space_dashboard'],
            ['label' => 'Org', 'route' => 'platform.organizations', 'icon' => 'domain'],
        ]
        : ($role === 'admin'
        ? [
            ['label' => 'Beranda', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Panen', 'route' => 'admin.harvests', 'icon' => 'rebase_edit'],
            ['label' => 'Harga', 'route' => 'admin.prices', 'icon' => 'payments'],
            ['label' => 'Akses', 'route' => 'admin.access-requests', 'icon' => 'verified_user'],
            ['label' => 'Log', 'route' => 'admin.activity-logs', 'icon' => 'manage_history'],
        ]
        : [
            ['label' => 'Beranda', 'route' => 'petani.dashboard', 'icon' => 'home'],
            ['label' => 'Catat', 'route' => 'petani.harvests.create', 'icon' => 'add_circle'],
            ['label' => 'Riwayat', 'route' => 'petani.harvests', 'icon' => 'history'],
            ['label' => 'Harga', 'route' => 'petani.prices', 'icon' => 'payments'],
        ]);
@endphp

<nav class="fixed bottom-0 left-0 right-0 z-40 flex h-20 items-center justify-around border-t border-[#dfe8dc] bg-white/90 px-4 shadow-[0_-18px_42px_-34px_rgba(5,25,39,0.5)] backdrop-blur-xl md:hidden">
    @foreach ($links as $link)
        @php $active = request()->routeIs($link['route']) || request()->routeIs($link['route'].'.*'); @endphp
        <a href="{{ route($link['route']) }}" class="flex min-w-14 flex-col items-center gap-1 rounded-2xl px-2 py-2 {{ $active ? 'bg-[#078d45]/10 text-[#078d45]' : 'text-[#718174]' }}">
            <span class="material-symbols-outlined {{ $active ? 'icon-filled' : '' }} text-xl">{{ $link['icon'] }}</span>
            <span class="text-[10px] font-bold">{{ $link['label'] }}</span>
        </a>
    @endforeach
</nav>
