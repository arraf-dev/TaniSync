@php
    $adminLinks = [
        ['label' => 'Beranda', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Komoditas', 'route' => 'admin.commodities', 'icon' => 'compost'],
        ['label' => 'Harga', 'route' => 'admin.prices', 'icon' => 'payments'],
        ['label' => 'Panen', 'route' => 'admin.harvests', 'icon' => 'rebase_edit'],
        ['label' => 'Laporan', 'route' => 'admin.reports', 'icon' => 'analytics'],
        ['label' => 'Akses', 'route' => 'admin.access-requests', 'icon' => 'verified_user'],
        ['label' => 'Aktivitas', 'route' => 'admin.activity-logs', 'icon' => 'manage_history'],
    ];
    $farmerLinks = [
        ['label' => 'Beranda', 'route' => 'petani.dashboard', 'icon' => 'home'],
        ['label' => 'Catat', 'route' => 'petani.harvests.create', 'icon' => 'add_circle'],
        ['label' => 'Riwayat', 'route' => 'petani.harvests', 'icon' => 'history'],
        ['label' => 'Harga', 'route' => 'petani.prices', 'icon' => 'payments'],
    ];
    $links = $role === 'admin' ? $adminLinks : $farmerLinks;
    $actionRoute = $role === 'admin' ? route('admin.commodities') : route('petani.harvests.create');
    $actionLabel = $role === 'admin' ? 'Tambah komoditas' : 'Catat panen';
@endphp

<aside class="sidebar-shell">
    <a href="{{ $role === 'admin' ? route('admin.dashboard') : route('petani.dashboard') }}" class="flex items-center gap-3 px-2">
        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#078d45]/10 text-[#078d45]">
            <span class="material-symbols-outlined icon-filled text-3xl">eco</span>
        </span>
        <span>
            <span class="block font-heading text-2xl font-extrabold text-[#078d45]">TaniSync</span>
            <span class="block text-[10px] font-bold uppercase tracking-[0.22em] text-[#718174]">Agritech desa</span>
        </span>
    </a>

    <nav class="mt-10 flex flex-1 flex-col gap-1.5">
        @foreach ($links as $link)
            @php $active = request()->routeIs($link['route']) || request()->routeIs($link['route'].'.*'); @endphp
            <a href="{{ route($link['route']) }}" class="sidebar-link {{ $active ? 'sidebar-link-active' : 'sidebar-link-idle' }}">
                <span class="material-symbols-outlined {{ $active ? 'icon-filled' : '' }} text-xl">{{ $link['icon'] }}</span>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <a href="{{ $actionRoute }}" class="mt-5 inline-flex items-center justify-center gap-2 rounded-2xl bg-[#061826] px-5 py-3.5 text-sm font-bold text-white transition hover:bg-[#078d45]">
        <span class="material-symbols-outlined icon-filled text-xl">add_circle</span>
        {{ $actionLabel }}
    </a>

    <div class="mt-5 rounded-3xl border border-[#dfe8dc] bg-[#f7faf7] p-4">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white font-heading text-sm font-extrabold text-[#078d45]">
                {{ strtoupper(substr($user?->name ?? 'T', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="truncate font-heading text-sm font-extrabold text-[#061826]">{{ $user?->name }}</p>
                <p class="truncate text-xs text-[#718174]">{{ $user?->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn-compact w-full">
                <span class="material-symbols-outlined text-lg">logout</span>
                Keluar
            </button>
        </form>
    </div>
</aside>
