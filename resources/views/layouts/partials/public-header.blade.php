<header class="public-nav fixed inset-x-0 top-0 z-40">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 md:px-10">
        <a href="{{ route('landing') }}" class="flex items-center gap-3 font-heading text-2xl font-extrabold text-[#078d45]" aria-label="TaniSync beranda">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#078d45]/10 text-[#078d45]">
                <span class="material-symbols-outlined icon-filled text-3xl">eco</span>
            </span>
            TaniSync
        </a>

        <nav class="hidden items-center gap-10 md:flex" aria-label="Navigasi utama">
            <a href="{{ route('landing') }}" class="nav-link nav-link-active">Beranda</a>
            <a href="#fitur" class="nav-link">Fitur</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="btn-nav-secondary hidden sm:inline-flex">Masuk</a>
            <a href="{{ route('register') }}" class="btn-nav-primary">Daftar<span class="hidden sm:inline">&nbsp;Gratis</span></a>
        </div>
    </div>
</header>
