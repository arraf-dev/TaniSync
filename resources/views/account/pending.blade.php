@extends('layouts.auth', ['title' => 'Status Akun TaniSync'])

@section('content')
    <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-3xl items-center justify-center px-4">
        <section class="surface-panel w-full space-y-6 p-6 text-center md:p-10">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl {{ $user->isRejected() ? 'bg-red-50 text-red-600' : 'bg-[#fff6df] text-[#a66b00]' }}">
                <span class="material-symbols-outlined icon-filled text-4xl">{{ $user->isRejected() ? 'block' : 'hourglass_top' }}</span>
            </div>
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#9c5421]">Status akses admin</p>
                <h1 class="font-heading text-4xl font-extrabold text-[#172018]">
                    {{ $user->isRejected() ? 'Permintaan akses belum disetujui' : 'Akun admin menunggu persetujuan' }}
                </h1>
                <p class="mx-auto max-w-xl text-sm leading-7 text-[#5b6658]">
                    {{ $user->isRejected()
                        ? 'Akses admin Anda belum dapat digunakan. Hubungi pengelola TaniSync untuk peninjauan ulang.'
                        : 'Akun dan organisasi Anda sudah tercatat. Super admin perlu menyetujui pengajuan sebelum dashboard admin dapat dibuka.' }}
                </p>
            </div>
            <div class="rounded-3xl border border-[#dfe8dc] bg-[#f7faf7] p-5 text-left">
                <div class="grid gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <p class="font-bold text-[#061826]">{{ $user->name }}</p>
                        <p class="text-[#718174]">{{ $user->email }}</p>
                        @if ($user->organization?->name)
                            <p class="mt-1 text-xs font-semibold text-[#718174]">{{ $user->organization->name }}</p>
                        @endif
                    </div>
                    <div class="sm:text-right">
                        <span class="status-pill {{ $user->isRejected() ? 'status-danger' : 'status-warning' }}">
                            {{ $user->isRejected() ? 'Ditolak' : 'Menunggu Persetujuan' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-primary px-8 py-3">
                        Keluar
                        <span class="material-symbols-outlined text-xl">logout</span>
                    </button>
                </form>
                <a href="{{ route('landing') }}" class="btn-secondary px-8 py-3">Kembali ke beranda</a>
            </div>
        </section>
    </div>
@endsection
