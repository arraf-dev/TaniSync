@extends('layouts.public', ['title' => 'TaniSync'])

@section('content')
    <section class="px-6 pb-16 pt-28 md:px-10 lg:px-12 lg:pb-24 lg:pt-36">
        <div class="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="space-y-8">
                <div class="space-y-5">
                    <span class="inline-flex rounded-full bg-[#dff2df] px-4 py-1.5 text-xs font-bold uppercase tracking-[0.22em] text-[#196b2c]">Sistem agritech desa</span>
                    <h1 class="editorial-heading max-w-3xl font-heading text-5xl font-extrabold leading-[1.02] text-[#172018] md:text-7xl">
                        Digitalisasi pencatatan hasil panen dan harga komoditas desa.
                    </h1>
                    <p class="max-w-xl text-lg leading-8 text-[#5b6658]">
                        TaniSync membantu admin desa dan petani mencatat panen, memperbarui harga harian, dan menyiapkan laporan dengan alur yang singkat serta mudah dipahami.
                    </p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('login') }}" class="btn-primary px-8 py-4 text-base">
                        Mulai masuk
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </a>
                    <a href="#fitur" class="btn-secondary px-8 py-4 text-base">
                        Lihat fitur
                        <span class="material-symbols-outlined text-xl">south</span>
                    </a>
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach ([['2 peran', 'Admin desa & petani'], ['Input manual', 'Harga tetap bisa dicatat tanpa API'], ['Siap laporan', 'Ekspor PDF & Excel di tahap backend']] as [$title, $text])
                        <div class="rounded-[1.5rem] border border-[#cad4c4]/70 bg-white/70 p-4">
                            <p class="font-heading text-lg font-bold text-[#172018]">{{ $title }}</p>
                            <p class="mt-1 text-sm leading-6 text-[#5b6658]">{{ $text }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="relative">
                <div class="absolute inset-6 rounded-[2.5rem] bg-[#196b2c]/10 blur-3xl"></div>
                <div class="relative overflow-hidden rounded-[2.5rem] border border-[#cad4c4]/50 bg-white p-3 shadow-[0_22px_48px_-28px_rgba(23,32,24,0.22)]">
                    <img src="{{ $landing['heroImage'] }}" alt="Panorama area pertanian" class="h-[520px] w-full rounded-[2rem] object-cover">
                    <div class="absolute bottom-8 left-8 right-8 rounded-[1.5rem] border border-white/30 bg-white/88 p-5 shadow-lg backdrop-blur">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#5b6658]">Update hari ini</p>
                                <p class="mt-2 font-heading text-xl font-bold text-[#172018]">Harga cabai diperbarui manual admin</p>
                                <p class="mt-1 text-sm text-[#5b6658]">Catatan panen terbaru langsung masuk ke rekap desa.</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#196b2c] text-white">
                                <span class="material-symbols-outlined icon-filled text-2xl">monitoring</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="px-6 py-20 md:px-10 lg:px-12">
        <div class="mx-auto max-w-7xl space-y-12">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Fitur utama</p>
                <h2 class="editorial-heading mt-3 font-heading text-4xl font-extrabold text-[#172018] md:text-5xl">Rangkaian screen yang rapi, konsisten, dan siap dipindahkan ke alur Laravel penuh.</h2>
                <p class="mt-4 text-base leading-7 text-[#5b6658] md:text-lg">Semua halaman dibangun dengan token desain yang sama agar admin dan petani merasakan alur yang jelas dan tetap ringan dipakai di ponsel.</p>
            </div>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($landing['featureCards'] as $feature)
                    <div class="surface-muted h-full p-6">
                        <div class="space-y-5">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#196b2c]/10 text-[#196b2c]">
                                <span class="material-symbols-outlined icon-filled text-3xl">{{ $feature['icon'] }}</span>
                            </div>
                            <div class="space-y-3">
                                <h3 class="font-heading text-2xl font-extrabold text-[#172018]">{{ $feature['title'] }}</h3>
                                <p class="text-sm leading-7 text-[#5b6658]">{{ $feature['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="manfaat" class="bg-white px-6 py-20 md:px-10 lg:px-12">
        <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[0.95fr_1.05fr]">
            <div class="surface-muted bg-[radial-gradient(circle_at_top_right,rgba(25,107,44,0.12),transparent_40%),#f1f4ee] p-8">
                <div class="space-y-6">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#9c5421]">Kenapa TaniSync</p>
                    <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Dirancang untuk kerja lapangan yang cepat, bukan untuk membuat pengguna berputar-putar.</h2>
                    <div class="space-y-5">
                        @foreach (['Form panen singkat dan mobile-first untuk petani.', 'Harga harian tersimpan terstruktur dan mudah dicek ulang.', 'Dashboard admin fokus pada ringkasan desa, bukan metrik yang tidak relevan.', 'Siap dihubungkan ke Laravel tanpa ganti struktur tampilan.'] as $item)
                            <div class="flex gap-3">
                                <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#196b2c] text-white">
                                    <span class="material-symbols-outlined icon-filled text-lg">check</span>
                                </div>
                                <p class="text-sm leading-7 text-[#5b6658]">{{ $item }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="grid gap-6 sm:grid-cols-2">
                @foreach ([['Transparansi data', 'Admin dan petani mengakses data dari sumber yang sama sehingga laporan lebih konsisten.'], ['Siap untuk desa', 'Bahasa antarmuka Indonesia, label jelas, dan struktur navigasi sederhana.'], ['Fallback manual', 'Harga komoditas tetap berjalan walau integrasi pihak ketiga belum ada.'], ['Ekspor terarah', 'Frontend sudah menyiapkan alur untuk PDF dan Excel sesuai PRD.']] as [$title, $description])
                    <div class="surface-panel h-full p-6">
                        <h3 class="font-heading text-2xl font-extrabold text-[#172018]">{{ $title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[#5b6658]">{{ $description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="alur" class="px-6 py-20 md:px-10 lg:px-12">
        <div class="mx-auto max-w-7xl">
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Alur utama</p>
                <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Alur petani dan admin dibuat sejajar dengan PRD.</h2>
                <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Route publik, halaman per peran, dan binding data mock sudah dirangkai agar backend domain nanti bisa masuk tanpa mengubah arah visual.</p>
            </div>
            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                @foreach ([
                    ['label' => 'Petani', 'tone' => 'bg-[#dff2df] text-[#196b2c]', 'steps' => ['Login ke aplikasi.', 'Catat panen dengan form singkat.', 'Lihat riwayat panen pribadi.', 'Bandingkan harga komoditas terbaru.']],
                    ['label' => 'Admin desa', 'tone' => 'bg-[#ffebde] text-[#9c5421]', 'steps' => ['Masuk ke dashboard operasional desa.', 'Kelola master komoditas dan harga harian.', 'Pantau log panen yang masuk dari petani.', 'Buka laporan dan siapkan ekspor.']],
                ] as $group)
                    <div class="surface-panel p-8">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] {{ $group['label'] === 'Petani' ? 'text-[#196b2c]' : 'text-[#9c5421]' }}">{{ $group['label'] }}</p>
                        <div class="mt-6 space-y-4">
                            @foreach ($group['steps'] as $index => $step)
                                <div class="flex gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl font-heading font-bold {{ $group['tone'] }}">{{ $index + 1 }}</div>
                                    <p class="pt-2 text-sm leading-7 text-[#5b6658]">{{ $step }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="px-6 pb-20 md:px-10 lg:px-12">
        <div class="mx-auto max-w-7xl rounded-[2.5rem] bg-[#196b2c] p-10 text-center text-white shadow-[0_12px_30px_-18px_rgba(25,107,44,0.18)] md:p-16">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#dff2df]">Siap dipakai</p>
            <h2 class="mt-4 font-heading text-4xl font-extrabold md:text-5xl">Masuk ke alur Laravel Blade TaniSync sekarang.</h2>
            <p class="mx-auto mt-4 max-w-2xl text-base leading-8 text-[#dff2df]">Landing page publik, auth, area petani, dan area admin sudah dipetakan ke Laravel Blade + Tailwind dengan route yang siap dikembangkan.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('login') }}" class="btn-secondary bg-white text-[#196b2c] hover:bg-[#f1f4ee]">Coba login</a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-6 py-3.5 text-sm font-semibold text-white transition duration-200 hover:bg-white/20">Buat akun</a>
            </div>
        </div>
    </section>
@endsection
