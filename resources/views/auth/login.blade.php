@extends('layouts.auth', ['title' => 'Login TaniSync'])

@php
    $roles = [
        'petani' => [
            'icon' => 'nature_people',
            'label' => 'Petani',
            'description' => 'Catat panen dan cek harga harian',
            'helper' => 'Pilih role petani',
        ],
        'admin' => [
            'icon' => 'admin_panel_settings',
            'label' => 'Admin',
            'description' => 'Kelola komoditas, harga, dan laporan',
            'helper' => 'Pilih role admin',
        ],
    ];

    $demoAccounts = [
        [
            'role' => 'Admin',
            'email' => 'admin@tanisync.id',
            'helper' => 'Pilih role admin',
        ],
        [
            'role' => 'Petani',
            'email' => 'rahmat@tanisync.id',
            'helper' => 'Pilih role petani',
        ],
    ];
@endphp

@section('content')
    <div class="mx-auto grid min-h-[calc(100vh-4rem)] w-full max-w-7xl gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(420px,0.88fr)] lg:items-center">
        <section class="relative overflow-hidden rounded-[2rem] border border-[#d9e5d7] bg-[#f4f8f1] p-5 shadow-[0_22px_56px_-36px_rgba(5,25,39,0.32)] sm:p-7 lg:min-h-[calc(100vh-7rem)] lg:p-9">
            <div class="flex h-full flex-col justify-between gap-7">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 font-heading text-xl font-extrabold text-[#196b2c]">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-[#078d45] shadow-sm">
                            <span class="material-symbols-outlined icon-filled text-2xl">grass</span>
                        </span>
                        TaniSync
                    </a>
                    <span class="hidden rounded-full border border-[#d9e5d7] bg-white/80 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-[#5c6f62] sm:inline-flex">Login aman</span>
                </div>

                <div class="grid flex-1 items-end gap-6">
                    <div class="mx-auto flex w-full max-w-[560px] justify-center">
                        <img
                            src="{{ asset('images/tanisync/login-farmer.png') }}"
                            alt="Ilustrasi petani menggunakan tablet TaniSync"
                            class="h-[320px] w-full object-contain object-bottom sm:h-[430px] lg:h-[590px]"
                            fetchpriority="high"
                        >
                    </div>

                    <div class="rounded-[1.5rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_44px_-34px_rgba(5,25,39,0.28)] backdrop-blur">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#9c5421]">Dashboard terpadu</p>
                        <h2 class="mt-2 font-heading text-2xl font-extrabold leading-tight text-[#172018] sm:text-3xl">
                            Kelola data panen dan harga desa dalam satu alur.
                        </h2>
                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl bg-[#f2f7f1] px-4 py-3">
                                <p class="text-xl font-extrabold text-[#078d45]">2</p>
                                <p class="text-xs font-semibold text-[#5c6f62]">Role akses</p>
                            </div>
                            <div class="rounded-2xl bg-[#f2f7f1] px-4 py-3">
                                <p class="text-xl font-extrabold text-[#078d45]">1</p>
                                <p class="text-xs font-semibold text-[#5c6f62]">Dashboard aktif</p>
                            </div>
                            <div class="rounded-2xl bg-[#f2f7f1] px-4 py-3">
                                <p class="text-xl font-extrabold text-[#078d45]">24/7</p>
                                <p class="text-xs font-semibold text-[#5c6f62]">Data tersimpan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center">
            <form method="POST" action="{{ route('login') }}" class="surface-panel w-full max-w-xl space-y-6 p-5 sm:p-7 lg:p-8">
                @csrf

                <div class="space-y-3 border-b border-[#e6efe3] pb-6">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#9c5421]">Masuk aplikasi</p>
                    <div class="space-y-2">
                        <h1 class="font-heading text-3xl font-extrabold leading-tight text-[#172018] sm:text-4xl">Masuk ke TaniSync</h1>
                        <p class="text-sm leading-6 text-[#5b6658]">
                            Gunakan email, kata sandi, dan role yang sesuai agar dashboard terbuka sesuai akses akun.
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-[#5b6658]">Pilih peran akun</p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($roles as $role => $item)
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="{{ $role }}" class="peer sr-only" {{ old('role', 'petani') === $role ? 'checked' : '' }}>
                                <span class="flex min-h-[116px] flex-col justify-between rounded-[1.35rem] border border-[#cad4c4] bg-[#f7faf7] p-4 text-[#5b6658] transition peer-checked:border-[#078d45] peer-checked:bg-[#e7f6eb] peer-checked:text-[#078d45] peer-focus-visible:ring-4 peer-focus-visible:ring-[#078d45]/10">
                                    <span class="flex items-start gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white shadow-sm">
                                            <span class="material-symbols-outlined text-xl">{{ $item['icon'] }}</span>
                                        </span>
                                        <span>
                                            <span class="block font-heading text-base font-bold">{{ $item['label'] }}</span>
                                            <span class="mt-1 block text-xs leading-5">{{ $item['description'] }}</span>
                                        </span>
                                    </span>
                                    <span class="mt-4 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.13em]">
                                        <span class="material-symbols-outlined text-base">radio_button_checked</span>
                                        {{ $item['helper'] }}
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('role') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5">
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-semibold text-[#5b6658]">Alamat email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="field-input" placeholder="nama@tanisync.id">
                        @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        @if ($errors->has('email'))
                            <p class="text-xs font-medium text-[#7b8578]">Pastikan role yang dipilih sesuai dengan akun yang Anda gunakan.</p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-semibold text-[#5b6658]">Kata sandi</label>
                        <input id="password" name="password" type="password" required class="field-input" placeholder="Minimal 8 karakter">
                        @error('password') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <label class="inline-flex items-center gap-3 text-sm font-medium text-[#5b6658]">
                        <input type="checkbox" name="remember" class="rounded border-[#cad4c4] text-[#078d45] focus:ring-[#078d45]/20">
                        Ingat sesi saya
                    </label>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-[#078d45] hover:underline">Belum punya akun?</a>
                </div>

                <button type="submit" class="btn-primary w-full py-4 text-base">
                    Masuk ke dashboard
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </button>

                <div class="rounded-[1.35rem] border border-[#d9e5d7] bg-[#f7faf7] p-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-heading text-base font-bold text-[#172018]">Akun demo</p>
                            <p class="mt-1 text-xs leading-5 text-[#5c6f62]">Password semua akun demo: <strong>password123</strong></p>
                        </div>
                        <a href="{{ route('landing') }}" class="text-sm font-semibold text-[#078d45] hover:underline">Kembali ke beranda</a>
                    </div>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($demoAccounts as $account)
                            <div class="rounded-2xl bg-white px-4 py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-semibold text-[#172018]">{{ $account['role'] }}</p>
                                    <span class="rounded-full bg-[#e7f6eb] px-2.5 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-[#078d45]">{{ $account['helper'] }}</span>
                                </div>
                                <p class="mt-2 break-all text-sm text-[#5c6f62]">{{ $account['email'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
