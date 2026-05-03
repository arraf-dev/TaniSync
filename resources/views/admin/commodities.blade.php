@extends('layouts.app', ['title' => 'Komoditas Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        @if (session('status'))
            <div class="rounded-2xl border border-[#078d45]/20 bg-[#e6f7ed] px-4 py-3 text-sm font-semibold text-[#05753a]">{{ session('status') }}</div>
        @endif

        <div class="page-heading">
            <div>
                <p class="page-kicker">Master data</p>
                <h2 class="page-title">Manajemen komoditas</h2>
                <p class="page-copy">Kelola daftar komoditas desa agar form panen dan harga harian tetap akurat.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.commodities.store') }}" class="section-panel">
            @csrf
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-[1.2fr_1fr_0.7fr_0.85fr_auto]">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Nama komoditas</label>
                    <input name="nama_komoditas" value="{{ old('nama_komoditas') }}" class="field-input py-3" placeholder="Contoh: Tomat Lokal" required>
                    @error('nama_komoditas') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Kategori</label>
                    <select name="kategori_id" class="field-input py-3" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('kategori_id') == $category->id)>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Satuan</label>
                    <input name="satuan" value="{{ old('satuan', 'kg') }}" class="field-input py-3" required>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Harga acuan</label>
                    <input type="number" min="0" step="100" name="harga_acuan" value="{{ old('harga_acuan') }}" class="field-input py-3" placeholder="0">
                </div>
                <div class="flex items-end">
                    <button class="btn-primary w-full py-3" type="submit">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Tambah
                    </button>
                </div>
            </div>
        </form>

        <form method="GET" action="{{ route('admin.commodities') }}" class="section-panel">
            <div class="grid gap-4 md:grid-cols-[1fr_220px_auto_auto] md:items-end">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Cari komoditas</label>
                    <input name="search" value="{{ request('search') }}" class="field-input py-3" placeholder="Nama komoditas atau kategori">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Status</label>
                    <select name="status" class="field-input py-3">
                        <option value="">Semua status</option>
                        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <a href="{{ route('admin.commodities') }}" class="btn-secondary py-3 text-center">Reset</a>
                <button class="btn-primary py-3" type="submit">Terapkan</button>
            </div>
        </form>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Komoditas</th>
                        <th>Kategori</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commodities as $commodity)
                        <tr>
                            <td>
                                <form id="commodity-update-{{ $commodity['id'] }}" method="POST" action="{{ route('admin.commodities.update', $commodity['id']) }}">
                                    @csrf
                                    @method('PATCH')
                                </form>
                                <input form="commodity-update-{{ $commodity['id'] }}" name="nama_komoditas" value="{{ $commodity['name'] }}" class="field-input py-2 text-sm font-bold">
                                <p class="mt-2 text-xs text-[#718174]">{{ $commodity['description'] }}</p>
                            </td>
                            <td>
                                <select form="commodity-update-{{ $commodity['id'] }}" name="kategori_id" class="field-input py-2 text-sm">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected($commodity['category_id'] == $category->id)>{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <input form="commodity-update-{{ $commodity['id'] }}" name="satuan" value="{{ $commodity['unit'] }}" class="field-input mx-auto w-24 py-2 text-center text-sm">
                                <input form="commodity-update-{{ $commodity['id'] }}" type="hidden" name="harga_acuan" value="{{ $commodity['harga_acuan'] }}">
                            </td>
                            <td class="text-center">
                                <span class="status-pill {{ $commodity['status'] === 'aktif' ? 'status-success' : 'status-warning' }}">{{ $commodity['status'] }}</span>
                            </td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <button form="commodity-update-{{ $commodity['id'] }}" class="btn-compact" type="submit">Simpan</button>
                                    <form method="POST" action="{{ route('admin.commodities.toggle', $commodity['id']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn-compact" type="submit">{{ $commodity['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-sm font-semibold text-[#718174]">Tidak ada komoditas yang cocok dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $commodities->links() }}
            </div>
        </div>
    </div>
@endsection
