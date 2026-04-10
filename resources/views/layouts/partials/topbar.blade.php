<header class="glass-panel sticky top-0 z-30 flex h-20 items-center justify-between border-b border-[#cad4c4]/70 px-5 md:px-8">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#9c5421]">{{ $role === 'admin' ? 'Area admin' : 'Area petani' }}</p>
        <h1 class="mt-1 font-heading text-xl font-extrabold text-[#172018]">{{ $pageTitle }}</h1>
    </div>
    <div class="flex items-center gap-3">
        <button class="hidden rounded-full bg-[#f1f4ee] p-3 text-[#5b6658] transition hover:text-[#172018] md:inline-flex">
            <span class="material-symbols-outlined text-xl">notifications</span>
        </button>
        <div class="flex items-center gap-3 rounded-full border border-[#cad4c4]/70 bg-white px-3 py-2">
            <img src="{{ $role === 'admin' ? 'https://i.pravatar.cc/120?img=12' : 'https://i.pravatar.cc/120?img=15' }}" alt="{{ $user?->name }}" class="h-10 w-10 rounded-full object-cover">
            <div class="hidden sm:block">
                <p class="font-heading text-sm font-bold text-[#172018]">{{ $user?->name }}</p>
                <p class="text-xs text-[#5b6658]">{{ $user?->village }}</p>
            </div>
        </div>
    </div>
</header>
