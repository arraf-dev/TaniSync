@php
    $adminLinks = [
        ['label' => 'Beranda', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Komoditas', 'route' => 'admin.commodities', 'icon' => 'compost'],
        ['label' => 'Harga', 'route' => 'admin.prices', 'icon' => 'payments'],
        ['label' => 'Panen', 'route' => 'admin.harvests', 'icon' => 'rebase_edit'],
        ['label' => 'Laporan', 'route' => 'admin.reports', 'icon' => 'analytics'],
    ];
    $farmerLinks = [
        ['label' => 'Beranda', 'route' => 'petani.dashboard', 'icon' => 'home'],
        ['label' => 'Catat', 'route' => 'petani.harvests.create', 'icon' => 'add_circle'],
        ['label' => 'Riwayat', 'route' => 'petani.harvests', 'icon' => 'history'],
        ['label' => 'Harga', 'route' => 'petani.prices', 'icon' => 'payments'],
    ];
    $links = $role === 'admin' ? $adminLinks : $farmerLinks;
    $actionRoute = $role === 'admin' ? route('admin.commodities') : route('petani.harvests.create');
    $actionLabel = $role === 'admin' ? 'Tambah data baru' : 'Catat panen';
@endphp

<aside class="hidden h-screen w-72 shrink-0 flex-col border-r border-[#cad4c4]/70 bg-white/80 px-5 py-8 md:flex">
    <div class="px-3">
        <p class="font-heading text-2xl font-extrabold text-[#196b2c]">TaniSync</p>
        <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.22em] text-[#5b6658]">Digital agritech desa</p>
    </div>
    <nav class="mt-10 flex flex-1 flex-col gap-2">
        @foreach ($links as $link)
            @php $active = request()->routeIs($link['route']); @endphp
            <a href="{{ route($link['route']) }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ $active ? 'bg-[#dff2df] text-[#196b2c]' : 'text-[#5b6658] hover:bg-[#f1f4ee] hover:text-[#172018]' }}">
                <span class="material-symbols-outlined {{ $active ? 'icon-filled' : '' }} text-xl">{{ $link['icon'] }}</span>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>
    <a href="{{ $actionRoute }}" class="mt-6 inline-flex items-center justify-center gap-2 rounded-2xl bg-[#196b2c] px-5 py-4 text-sm font-bold text-white shadow-[0_12px_30px_-18px_rgba(25,107,44,0.18)] transition hover:bg-[#114b1e]">
        <span class="material-symbols-outlined icon-filled text-xl">add_circle</span>
        {{ $actionLabel }}
    </a>
    <div class="mt-6 flex items-center gap-3 border-t border-[#cad4c4]/70 px-3 pt-6">
        <img src="{{ $role === 'admin' ? 'https://i.pravatar.cc/120?img=12' : 'https://i.pravatar.cc/120?img=15' }}" alt="{{ $user?->name }}" class="h-12 w-12 rounded-2xl object-cover">
        <div class="min-w-0">
            <p class="truncate font-heading text-sm font-bold text-[#172018]">{{ $user?->name }}</p>
            <p class="truncate text-xs text-[#5b6658]">{{ $user?->email }}</p>
        </div>
    </div>
    <form method="POST" action="{{ route('logout') }}" class="mt-4 px-3">
        @csrf
        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl border border-[#cad4c4] px-4 py-3 text-sm font-semibold text-[#42513f] transition hover:border-[#196b2c] hover:text-[#196b2c]">
            <span class="material-symbols-outlined text-xl">logout</span>
            Keluar
        </button>
    </form>
</aside>
