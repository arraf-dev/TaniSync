@php
    $links = match ($role) {
        'superadmin' => [
            ['label' => 'Beranda', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Pengguna', 'route' => 'superadmin.users', 'icon' => 'group'],
            ['label' => 'Harga', 'route' => 'admin.prices', 'icon' => 'payments'],
            ['label' => 'Laporan', 'route' => 'admin.reports', 'icon' => 'analytics'],
        ],
        'admin' => [
            ['label' => 'Beranda', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Panen', 'route' => 'admin.harvests', 'icon' => 'rebase_edit'],
            ['label' => 'Harga', 'route' => 'admin.prices', 'icon' => 'payments'],
            ['label' => 'Laporan', 'route' => 'admin.reports', 'icon' => 'analytics'],
        ],
        default => [
            ['label' => 'Beranda', 'route' => 'petani.dashboard', 'icon' => 'home'],
            ['label' => 'Catat', 'route' => 'petani.harvests.create', 'icon' => 'add_circle'],
            ['label' => 'Riwayat', 'route' => 'petani.harvests', 'icon' => 'history'],
            ['label' => 'Harga', 'route' => 'petani.prices', 'icon' => 'payments'],
        ],
    };
@endphp

<nav class="glass-panel fixed bottom-0 left-0 right-0 z-40 flex h-20 items-center justify-around border-t border-[#cad4c4]/70 px-4 md:hidden">
    @foreach ($links as $link)
        @php $active = request()->routeIs($link['route']); @endphp
        <a href="{{ route($link['route']) }}" class="flex flex-col items-center gap-1 {{ $active ? 'text-[#196b2c]' : 'text-[#5b6658]' }}">
            <span class="material-symbols-outlined {{ $active ? 'icon-filled' : '' }} text-xl">{{ $link['icon'] }}</span>
            <span class="text-[10px] font-bold uppercase tracking-[0.18em]">{{ $link['label'] }}</span>
        </a>
    @endforeach
</nav>
