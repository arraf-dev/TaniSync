@extends('layouts.public', ['title' => 'TaniSync'])

@section('content')
    <section class="landing-hero relative isolate overflow-hidden px-6 pb-14 pt-28 md:px-10 lg:min-h-screen lg:px-12 lg:pb-16 lg:pt-24">
        <div class="absolute inset-0 -z-30 bg-[#f8fbf7]"></div>
        <img
            src="{{ asset('images/tanisync/landing-hero-v3.png') }}"
            alt="Ilustrasi petani menggunakan tablet di tengah sawah untuk memantau data TaniSync"
            class="absolute inset-y-0 right-0 -z-20 h-full w-full object-cover object-[66%_center]"
        >
        <div class="absolute inset-0 -z-10 bg-[linear-gradient(90deg,rgba(255,255,255,0.98)_0%,rgba(255,255,255,0.9)_32%,rgba(255,255,255,0.2)_58%,rgba(255,255,255,0)_100%)]"></div>
        <div class="absolute inset-y-0 left-0 -z-10 w-[58%] bg-[radial-gradient(circle_at_20%_45%,rgba(255,255,255,0.96)_0%,rgba(255,255,255,0.72)_38%,rgba(255,255,255,0)_78%)]"></div>
        <div class="absolute inset-x-0 bottom-0 -z-10 h-56 bg-[linear-gradient(0deg,rgba(248,251,247,1),rgba(248,251,247,0))]"></div>

        <div class="mx-auto grid max-w-7xl gap-10 lg:min-h-[calc(100vh-7rem)] lg:grid-cols-[0.92fr_1.08fr] lg:items-center">
            <div class="max-w-2xl space-y-8">
                <div class="space-y-6">
                    <p class="inline-flex items-center gap-2 rounded-full border border-[#15924f]/20 bg-white/80 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#07843f] shadow-sm backdrop-blur">
                        <span class="material-symbols-outlined icon-filled text-base">eco</span>
                        Platform pertanian multi-organisasi
                    </p>
                    <h1 class="font-heading text-4xl font-extrabold leading-[1.08] text-[#051927] sm:text-5xl md:text-6xl lg:text-7xl">
                        Kelola Pertanian Lebih Cerdas dengan
                        <span class="block text-[#078d45]">TaniSync</span>
                    </h1>
                    <p class="max-w-xl text-lg leading-8 text-[#3e5064] md:text-xl md:leading-9">
                        Pantau hasil panen, harga komoditas, dan laporan usahatani berbagai organisasi dalam satu platform terintegrasi yang siap dipakai admin dan petani.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="btn-primary btn-hero w-full sm:w-auto">
                        Mulai Gratis
                        <span class="material-symbols-outlined text-2xl">arrow_forward</span>
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary btn-hero-secondary w-full sm:w-auto">
                        Masuk
                    </a>
                </div>

                <div class="grid gap-3 rounded-[1.35rem] border border-white/80 bg-white/84 p-4 shadow-[0_20px_48px_-34px_rgba(5,25,39,0.3)] backdrop-blur">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-extrabold text-[#061826]">Ringkasan hari ini</p>
                        <span class="status-pill status-success">Live</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach ([['12,4 ton', 'Panen'], ['Rp 12.500', 'Beras'], ['18%', 'Naik']] as [$value, $label])
                            <div class="rounded-2xl bg-[#f2f7f1] p-3">
                                <p class="font-heading text-lg font-extrabold text-[#061826]">{{ $value }}</p>
                                <p class="mt-1 text-xs font-semibold text-[#718174]">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid max-w-xl gap-3 sm:grid-cols-3">
                    @foreach ([['3 peran', 'Platform, admin, petani'], ['Harga harian', 'Referensi komoditas'], ['Laporan organisasi', 'Rekap siap filter']] as [$title, $text])
                        <div class="rounded-2xl border border-white/70 bg-white/75 p-4 shadow-[0_16px_36px_-28px_rgba(5,25,39,0.5)] backdrop-blur">
                            <p class="font-heading text-lg font-extrabold text-[#051927]">{{ $title }}</p>
                            <p class="mt-1 text-sm leading-6 text-[#50637a]">{{ $text }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="hidden lg:block" aria-hidden="true"></div>
        </div>
    </section>

    <section id="fitur" class="relative bg-[#f8fbf7] px-6 py-16 md:px-10 lg:px-12 lg:py-20">
        <div class="mx-auto max-w-7xl">
            <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-bold uppercase tracking-[0.18em] text-[#078d45]">Fitur utama</p>
                    <h2 class="mt-3 font-heading text-4xl font-extrabold leading-tight text-[#051927] md:text-5xl">
                        Semua alur penting organisasi pertanian berada dalam satu tempat.
                    </h2>
                </div>
                <p class="max-w-md text-base leading-7 text-[#50637a]">
                    Dibuat untuk pekerjaan nyata: petani mencatat panen, admin memperbarui harga, lalu laporan organisasi terbaca dari data yang sama.
                </p>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($landing['featureCards'] as $feature)
                    <article class="feature-card h-full">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#078d45]/10 text-[#078d45]">
                            <span class="material-symbols-outlined icon-filled text-3xl">{{ $feature['icon'] }}</span>
                        </div>
                        <h3 class="mt-6 font-heading text-2xl font-extrabold text-[#051927]">{{ $feature['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[#50637a]">{{ $feature['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
