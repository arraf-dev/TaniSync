@extends('layouts.auth', ['title' => 'Login TaniSync'])

@section('content')
    <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl gap-6 lg:grid-cols-[1.05fr_0.95fr]">
        <section class="relative hidden overflow-hidden rounded-[2.5rem] border border-[#cad4c4]/70 bg-[#f1f4ee] p-10 shadow-[0_22px_48px_-28px_rgba(23,32,24,0.22)] lg:flex lg:flex-col lg:justify-between">
            <div>
                <p class="font-heading text-3xl font-extrabold text-[#196b2c]">TaniSync</p>
                <p class="mt-2 max-w-sm text-sm leading-7 text-[#5b6658]">Pusat pencatatan panen dan harga komoditas desa yang dirancang mobile-first.</p>
            </div>
            <div class="space-y-6">
                <div class="visual-frame">
                    <img src="{{ asset('images/tanisync/hero-dashboard.png') }}" alt="Ilustrasi dashboard TaniSync" class="h-[420px]">
                </div>
                <div class="rounded-[2rem] bg-white/80 p-6">
                    <h2 class="font-heading text-3xl font-extrabold text-[#172018]">Masuk ke dashboard yang sesuai dengan peran Anda.</h2>
                    <p class="mt-3 text-sm leading-7 text-[#5b6658]">Admin memantau desa secara agregat, sementara petani fokus pada input panen dan harga terbaru dari database.</p>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center">
            <form method="POST" action="{{ route('login') }}" class="surface-panel w-full max-w-xl space-y-6 p-6 md:p-8">
                @csrf
                <div class="space-y-2">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#9c5421]">Masuk aplikasi</p>
                    <h1 class="font-heading text-4xl font-extrabold text-[#172018]">Masuk ke TaniSync</h1>
                    <p class="text-sm leading-6 text-[#5b6658]">Pilih peran yang sesuai dengan akun Anda. Login hanya berhasil jika email, kata sandi, dan role cocok.</p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-[#5b6658]">Pilih peran akun</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                    @foreach (['petani' => 'Fokus pada catat panen & harga', 'admin' => 'Kelola komoditas, harga, dan laporan'] as $role => $text)
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="{{ $role }}" class="peer sr-only" {{ old('role', 'petani') === $role ? 'checked' : '' }}>
                            <div class="rounded-[1.5rem] border border-[#cad4c4] bg-[#f1f4ee] p-4 text-[#5b6658] transition peer-checked:border-[#196b2c] peer-checked:bg-[#dff2df] peer-checked:text-[#196b2c] peer-focus-visible:ring-2 peer-focus-visible:ring-[#196b2c]/20">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/80 transition peer-checked:bg-white">
                                        <span class="material-symbols-outlined text-xl">{{ $role === 'petani' ? 'nature_people' : 'admin_panel_settings' }}</span>
                                    </div>
                                    <div>
                                        <p class="font-heading text-base font-bold capitalize">{{ $role }}</p>
                                        <p class="text-xs">{{ $text }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between text-xs font-semibold">
                                    <span>{{ $role === 'admin' ? 'Untuk pengelola desa' : 'Untuk pemilik data panen' }}</span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-2.5 py-1 text-[#196b2c] opacity-0 transition peer-checked:opacity-100">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                        Dipilih
                                    </span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                    </div>
                </div>
                @error('role') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

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

                <label class="flex items-center gap-3 text-sm text-[#5b6658]">
                    <input type="checkbox" name="remember" class="rounded border-[#cad4c4] text-[#196b2c] focus:ring-[#196b2c]/20">
                    Ingat sesi saya
                </label>

                <button type="submit" class="btn-primary w-full py-4 text-base">
                    Masuk ke dashboard
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </button>

                <div class="space-y-3 rounded-2xl border border-[#196b2c]/10 bg-[#dff2df]/60 px-4 py-4 text-sm text-[#114b1e]">
                    <p class="font-heading text-base font-bold">Akun demo</p>
                    <div class="rounded-2xl bg-white/70 px-4 py-3">
                        <p class="font-semibold text-[#172018]">Admin</p>
                        <p class="mt-1 text-sm">Email: <strong>admin@tanisync.id</strong></p>
                        <p class="text-sm">Password: <strong>password123</strong></p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-[0.16em] text-[#196b2c]">Pilih role admin</p>
                    </div>
                    <div class="rounded-2xl bg-white/70 px-4 py-3">
                        <p class="font-semibold text-[#172018]">Petani</p>
                        <p class="mt-1 text-sm">Email: <strong>rahmat@tanisync.id</strong></p>
                        <p class="text-sm">Password: <strong>password123</strong></p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-[0.16em] text-[#196b2c]">Pilih role petani</p>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 text-sm text-[#5b6658]">
                    <a href="{{ route('landing') }}" class="font-semibold text-[#196b2c] hover:underline">Kembali ke beranda</a>
                    <a href="{{ route('register') }}" class="font-semibold text-[#196b2c] hover:underline">Belum punya akun?</a>
                </div>
            </form>
        </section>
    </div>
@endsection
