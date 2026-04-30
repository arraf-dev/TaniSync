@extends('layouts.auth', ['title' => 'Register TaniSync'])

@section('content')
    <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl gap-6 lg:grid-cols-[0.95fr_1.05fr]">
        <section class="flex items-center justify-center order-2 lg:order-1">
            <form method="POST" action="{{ route('register') }}" class="surface-panel w-full max-w-xl space-y-6 p-6 md:p-8">
                @csrf
                <div class="space-y-2">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#9c5421]">Daftar akun baru</p>
                    <h1 class="font-heading text-4xl font-extrabold text-[#172018]">Buat akun TaniSync</h1>
                    <p class="text-sm leading-6 text-[#5b6658]">Registrasi awal ini menyiapkan akun Laravel dengan role admin atau petani.</p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach (['petani' => 'Catat panen dan lihat harga', 'admin' => 'Kelola komoditas, harga, dan laporan'] as $role => $text)
                        <label class="cursor-pointer rounded-[1.5rem] border p-4 transition {{ old('role', 'petani') === $role ? 'border-[#196b2c] bg-[#dff2df] text-[#196b2c]' : 'border-[#cad4c4] bg-[#f1f4ee] text-[#5b6658]' }}">
                            <input type="radio" name="role" value="{{ $role }}" class="sr-only" {{ old('role', 'petani') === $role ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/80">
                                    <span class="material-symbols-outlined {{ old('role', 'petani') === $role ? 'icon-filled' : '' }} text-xl">{{ $role === 'petani' ? 'nature_people' : 'admin_panel_settings' }}</span>
                                </div>
                                <div>
                                    <p class="font-heading text-base font-bold capitalize">{{ $role }}</p>
                                    <p class="text-xs">{{ $text }}</p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-[#5b6658]">Nama lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required class="field-input" placeholder="Contoh: Bapak Rahmat">
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

                <button type="submit" class="btn-primary w-full py-4 text-base">
                    Buat akun & lanjutkan
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </button>

                <div class="flex items-center justify-between gap-4 text-sm text-[#5b6658]">
                    <a href="{{ route('landing') }}" class="font-semibold text-[#196b2c] hover:underline">Kembali ke beranda</a>
                    <a href="{{ route('login') }}" class="font-semibold text-[#196b2c] hover:underline">Sudah punya akun?</a>
                </div>
            </form>
        </section>

        <section class="relative order-1 hidden overflow-hidden rounded-[2.5rem] border border-[#cad4c4]/70 bg-[#f1f4ee] p-10 shadow-[0_22px_48px_-28px_rgba(23,32,24,0.22)] lg:flex lg:flex-col lg:justify-between">
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#9c5421]">Registrasi awal</p>
                <h2 class="font-heading text-4xl font-extrabold text-[#172018]">Bangun fondasi akun untuk admin desa maupun petani.</h2>
                <p class="text-sm leading-7 text-[#5b6658]">Form ini memakai struktur user Laravel yang terhubung dengan role management TaniSync.</p>
            </div>
            <div class="visual-frame">
                <img src="{{ asset('images/tanisync/report-management.png') }}" alt="Ilustrasi manajemen laporan TaniSync" class="h-[520px]">
            </div>
        </section>
    </div>
@endsection
