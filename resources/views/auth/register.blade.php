@extends('layouts.auth', ['title' => 'Register TaniSync'])

@php
    $roles = [
        'petani' => [
            'icon' => 'nature_people',
            'label' => 'Petani',
            'description' => 'Langsung masuk dashboard petani untuk catat panen dan lihat harga.',
            'status' => 'Aktif langsung',
        ],
        'admin' => [
            'icon' => 'admin_panel_settings',
            'label' => 'Admin',
            'description' => 'Akun masuk antrean persetujuan sebelum membuka dashboard admin.',
            'status' => 'Perlu persetujuan',
        ],
    ];

    $flowSteps = [
        ['value' => '01', 'label' => 'Petani aktif langsung'],
        ['value' => '02', 'label' => 'Admin perlu persetujuan'],
        ['value' => '03', 'label' => 'Masuk sesuai role'],
    ];
@endphp

@section('content')
    <div class="mx-auto grid min-h-[calc(100vh-4rem)] w-full max-w-7xl gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(440px,0.9fr)] lg:items-center">
        <section class="relative overflow-hidden rounded-[2rem] border border-[#d9e5d7] bg-[#f4f8f1] p-5 shadow-[0_22px_56px_-36px_rgba(5,25,39,0.32)] sm:p-7 lg:min-h-[calc(100vh-7rem)] lg:p-9">
            <div class="flex h-full flex-col justify-between gap-7">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 font-heading text-xl font-extrabold text-[#196b2c]">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-[#078d45] shadow-sm">
                            <span class="material-symbols-outlined icon-filled text-2xl">grass</span>
                        </span>
                        TaniSync
                    </a>
                    <span class="hidden rounded-full border border-[#d9e5d7] bg-white/80 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-[#5c6f62] sm:inline-flex">Registrasi akun</span>
                </div>

                <div class="grid flex-1 items-end gap-6">
                    <div class="mx-auto flex w-full max-w-[570px] justify-center">
                        <img
                            src="{{ asset('images/tanisync/register-onboarding.png') }}"
                            alt="Ilustrasi onboarding akun petani dan admin TaniSync"
                            class="h-[320px] w-full object-contain object-bottom sm:h-[440px] lg:h-[590px]"
                            fetchpriority="high"
                        >
                    </div>

                    <div class="rounded-[1.5rem] border border-white/80 bg-white/90 p-5 shadow-[0_18px_44px_-34px_rgba(5,25,39,0.28)] backdrop-blur">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#9c5421]">Alur akses TaniSync</p>
                        <h2 class="mt-2 font-heading text-2xl font-extrabold leading-tight text-[#172018] sm:text-3xl">
                            Pilih role sejak awal agar dashboard terbuka sesuai tanggung jawab.
                        </h2>
                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                            @foreach ($flowSteps as $step)
                                <div class="rounded-2xl bg-[#f2f7f1] px-4 py-3">
                                    <p class="text-xl font-extrabold text-[#078d45]">{{ $step['value'] }}</p>
                                    <p class="mt-1 text-xs font-semibold leading-5 text-[#5c6f62]">{{ $step['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center">
            <form method="POST" action="{{ route('register') }}" class="surface-panel w-full max-w-xl space-y-6 p-5 sm:p-7 lg:p-8">
                @csrf

                <div class="space-y-3 border-b border-[#e6efe3] pb-6">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#9c5421]">Daftar akun baru</p>
                    <div class="space-y-2">
                        <h1 class="font-heading text-3xl font-extrabold leading-tight text-[#172018] sm:text-4xl">Buat akun TaniSync</h1>
                        <p class="text-sm leading-6 text-[#5b6658]">
                            Isi identitas sesuai role. Petani dapat langsung memakai aplikasi, sedangkan admin akan masuk proses persetujuan.
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-[#5b6658]">Pilih peran akun</p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($roles as $role => $item)
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="{{ $role }}" class="peer sr-only" {{ old('role', 'petani') === $role ? 'checked' : '' }}>
                                <span class="flex min-h-[142px] flex-col justify-between rounded-[1.35rem] border border-[#cad4c4] bg-[#f7faf7] p-4 text-[#5b6658] transition peer-checked:border-[#078d45] peer-checked:bg-[#e7f6eb] peer-checked:text-[#078d45] peer-focus-visible:ring-4 peer-focus-visible:ring-[#078d45]/10">
                                    <span class="flex items-start gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white shadow-sm">
                                            <span class="material-symbols-outlined text-xl">{{ $item['icon'] }}</span>
                                        </span>
                                        <span>
                                            <span class="block font-heading text-base font-bold">{{ $item['label'] }}</span>
                                            <span class="mt-1 block text-xs leading-5">{{ $item['description'] }}</span>
                                        </span>
                                    </span>
                                    <span class="mt-4 inline-flex w-fit items-center gap-2 rounded-full bg-white/85 px-3 py-1.5 text-xs font-bold text-[#078d45] shadow-sm">
                                        <span class="material-symbols-outlined text-base">verified_user</span>
                                        {{ $item['status'] }}
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('role') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-[#5b6658]">Nama lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="field-input" placeholder="Contoh: Bapak Rahmat">
                        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="village" class="text-sm font-semibold text-[#5b6658]">Desa / Gapoktan</label>
                        <input id="village" name="village" type="text" value="{{ old('village', 'Desa Sukamaju') }}" required class="field-input" placeholder="Desa Sukamaju">
                        @error('village') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-sm font-semibold text-[#5b6658]">Alamat email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="field-input" placeholder="nama@tanisync.id">
                    @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-semibold text-[#5b6658]">Kata sandi</label>
                        <input id="password" name="password" type="password" required class="field-input" placeholder="Minimal 8 karakter">
                        @error('password') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-semibold text-[#5b6658]">Konfirmasi sandi</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="field-input" placeholder="Ulangi kata sandi">
                    </div>
                </div>

                <div class="rounded-[1.35rem] border border-[#d9e5d7] bg-[#f7faf7] p-4">
                    <div class="flex gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-[#078d45] shadow-sm">
                            <span class="material-symbols-outlined icon-filled text-xl">info</span>
                        </span>
                        <p class="text-sm leading-6 text-[#5c6f62]">
                            Setelah submit, petani diarahkan ke dashboard petani. Admin diarahkan ke halaman status sampai akses disetujui pengelola aktif.
                        </p>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full py-4 text-base">
                    Buat akun & lanjutkan
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </button>

                <div class="flex flex-col gap-3 text-sm text-[#5b6658] sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('landing') }}" class="font-semibold text-[#078d45] hover:underline">Kembali ke beranda</a>
                    <a href="{{ route('login') }}" class="font-semibold text-[#078d45] hover:underline">Sudah punya akun?</a>
                </div>
            </form>
        </section>
    </div>
@endsection
