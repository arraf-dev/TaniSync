@extends('layouts.app', ['title' => 'Komoditas Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        @if (session('status'))
            <div class="rounded-2xl border border-[#196b2c]/20 bg-[#dff2df] px-5 py-4 text-sm font-semibold text-[#114b1e]">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined icon-filled text-lg">check_circle</span>
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Master data</p>
                <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Manajemen komoditas</h2>
                <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Kelola daftar komoditas desa agar form panen dan harga harian tetap akurat.</p>
            </div>
            <a href="{{ route('admin.commodities.create') }}" class="btn-primary">
                <span class="material-symbols-outlined icon-filled text-lg">add_circle</span>
                Tambah komoditas
            </a>
        </div>

        <div class="surface-panel overflow-x-auto p-6 md:p-8">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Komoditas</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Kategori</th>
                        <th class="px-4 py-2 text-center text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Satuan</th>
                        <th class="px-4 py-2 text-right text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Harga Acuan</th>
                        <th class="px-4 py-2 text-center text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Status</th>
                        <th class="px-4 py-2 text-center text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commodities as $commodity)
                        <tr>
                            <td class="rounded-l-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">{{ $commodity->icon ?? '📦' }}</span>
                                    <p class="font-heading text-base font-bold text-[#172018]">{{ $commodity->nama_komoditas }}</p>
                                </div>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4">
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">{{ $commodity->category?->nama_kategori ?? '-' }}</span>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4 text-center font-semibold text-[#172018]">{{ $commodity->satuan }}</td>
                            <td class="bg-[#f1f4ee] px-4 py-4 text-right font-heading font-bold text-[#172018]">Rp {{ number_format($commodity->harga_acuan ?? 0, 0, ',', '.') }}</td>
                            <td class="bg-[#f1f4ee] px-4 py-4 text-center">
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $commodity->is_active ? 'bg-[#dff2df] text-[#196b2c]' : 'bg-orange-100 text-[#9c5421]' }}">{{ $commodity->is_active ? 'aktif' : 'nonaktif' }}</span>
                            </td>
                            <td class="rounded-r-[1.5rem] bg-[#f1f4ee] px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.commodities.edit', $commodity) }}" class="inline-flex items-center gap-1 rounded-xl border border-[#cad4c4] px-3 py-2 text-xs font-semibold text-[#5b6658] transition hover:border-[#196b2c] hover:text-[#196b2c]">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.commodities.delete', $commodity) }}" onsubmit="return confirm('Yakin ingin menghapus {{ $commodity->nama_komoditas }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="rounded-[1.5rem] bg-[#f1f4ee] px-4 py-12 text-center text-[#5b6658]">
                                <span class="material-symbols-outlined mb-2 text-4xl text-[#cad4c4]">inventory_2</span>
                                <p>Belum ada komoditas. <a href="{{ route('admin.commodities.create') }}" class="font-semibold text-[#196b2c] hover:underline">Tambah sekarang</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
