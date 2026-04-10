<header class="glass-panel fixed inset-x-0 top-0 z-40 border-b border-[#cad4c4]/70">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6 md:px-10">
        <a href="{{ route('landing') }}" class="font-heading text-2xl font-extrabold text-[#196b2c]">TaniSync</a>
        <nav class="hidden items-center gap-8 md:flex">
            <a href="#fitur" class="text-sm font-semibold text-[#5b6658] transition hover:text-[#196b2c]">Fitur</a>
            <a href="#manfaat" class="text-sm font-semibold text-[#5b6658] transition hover:text-[#196b2c]">Manfaat</a>
            <a href="#alur" class="text-sm font-semibold text-[#5b6658] transition hover:text-[#196b2c]">Alur</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="btn-secondary hidden md:inline-flex">Login</a>
            <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
        </div>
    </div>
</header>
