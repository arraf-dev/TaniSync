<footer class="relative overflow-hidden border-t border-[#dfe8dc] bg-[#061826] px-6 py-12 text-white md:px-10 lg:px-12">
    <div class="absolute inset-x-0 top-0 h-1 bg-[linear-gradient(90deg,#078d45,#8bd45f,#078d45)]"></div>
    <div class="absolute -right-24 -top-24 h-64 w-64 rounded-full bg-[#078d45]/20 blur-3xl"></div>
    <div class="mx-auto grid max-w-7xl gap-8 md:grid-cols-[1.2fr_0.8fr] md:items-end">
        <div>
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 font-heading text-2xl font-extrabold">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 text-[#8bd45f]">
                    <span class="material-symbols-outlined icon-filled text-3xl">eco</span>
                </span>
                TaniSync
            </a>
            <p class="mt-4 max-w-xl text-sm leading-7 text-white/68">
                Dibuat untuk digitalisasi pertanian desa: panen tercatat, harga terpantau, dan laporan lebih mudah disiapkan.
            </p>
        </div>
        <div class="flex flex-wrap gap-3 md:justify-end">
            <a href="{{ route('landing') }}#fitur" class="rounded-full border border-white/14 px-5 py-2.5 text-sm font-semibold text-white/78 transition hover:border-[#8bd45f] hover:text-white">Fitur</a>
            <a href="{{ route('login') }}" class="rounded-full border border-white/14 px-5 py-2.5 text-sm font-semibold text-white/78 transition hover:border-[#8bd45f] hover:text-white">Masuk</a>
            <a href="{{ route('register') }}" class="rounded-full bg-[#078d45] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[#0aa052]">Daftar Gratis</a>
        </div>
    </div>
</footer>
