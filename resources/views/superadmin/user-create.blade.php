@extends('layouts.app', ['pageTitle' => $pageTitle])

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('superadmin.users') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-[#196b2c] hover:underline">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Kembali ke daftar pengguna
        </a>

        <div class="surface-panel p-6 md:p-8">
            <div class="mb-6 space-y-2">
                <h2 class="font-heading text-2xl font-extrabold text-[#172018]">Tambah pengguna baru</h2>
                <p class="text-sm text-[#5b6658]">Buat akun Admin atau Petani baru. Pengguna akan langsung dapat login setelah dibuat.</p>
            </div>

            <form method="POST" action="{{ route('superadmin.users.store') }}" class="space-y-6">
                @csrf

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-[#5b6658]">Pilih peran pengguna</p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach (['admin' => 'Kelola komoditas, harga, dan laporan', 'petani' => 'Catat panen dan lihat harga'] as $role => $text)
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="{{ $role }}" class="peer sr-only" {{ old('role', 'petani') === $role ? 'checked' : '' }}>
                                <div class="rounded-[1.5rem] border border-[#cad4c4] bg-[#f1f4ee] p-4 text-[#5b6658] transition peer-checked:border-[#196b2c] peer-checked:bg-[#dff2df] peer-checked:text-[#196b2c] peer-focus-visible:ring-2 peer-focus-visible:ring-[#196b2c]/20">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/80">
                                            <span class="material-symbols-outlined text-xl">{{ $role === 'petani' ? 'nature_people' : 'admin_panel_settings' }}</span>
                                        </div>
                                        <div>
                                            <p class="font-heading text-base font-bold capitalize">{{ $role }}</p>
                                            <p class="text-xs">{{ $text }}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('role') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-[#5b6658]">Nama lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required class="field-input" placeholder="Contoh: Admin Desa">
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
                    <span class="material-symbols-outlined icon-filled text-lg">person_add</span>
                    Buat akun pengguna
                </button>
            </form>
        </div>
    </div>
@endsection
