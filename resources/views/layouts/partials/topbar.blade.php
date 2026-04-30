<header class="topbar-shell">
    <div class="min-w-0">
        <div class="flex items-center gap-2">
            <span class="status-pill {{ $role === 'admin' ? 'status-warning' : 'status-success' }}">
                {{ $role === 'admin' ? 'Area admin' : 'Area petani' }}
            </span>
            @if ($user?->village)
                <span class="hidden text-xs font-semibold text-[#718174] sm:inline">{{ $user->village }}</span>
            @endif
        </div>
        <h1 class="mt-1 truncate font-heading text-xl font-extrabold text-[#061826] md:text-2xl">{{ $pageTitle }}</h1>
    </div>

    <div class="flex items-center gap-3 rounded-2xl border border-[#dfe8dc] bg-white px-3 py-2">
        <div class="hidden text-right sm:block">
            <p class="font-heading text-sm font-extrabold text-[#061826]">{{ $user?->name }}</p>
            <p class="text-xs text-[#718174]">{{ $role === 'admin' ? 'Pengelola data desa' : 'Petani terdaftar' }}</p>
        </div>
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#078d45]/10 font-heading text-sm font-extrabold text-[#078d45]">
            {{ strtoupper(substr($user?->name ?? 'T', 0, 1)) }}
        </div>
    </div>
</header>
