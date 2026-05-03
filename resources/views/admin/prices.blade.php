@extends('layouts.app', ['title' => 'Harga Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        @if (session('status'))
            <div class="rounded-2xl border border-[#078d45]/20 bg-[#e6f7ed] px-4 py-3 text-sm font-semibold text-[#05753a]">{{ session('status') }}</div>
        @endif

        <div class="page-heading">
            <div>
                <p class="page-kicker">Harga harian</p>
                <h2 class="page-title">Update harga komoditas</h2>
                <p class="page-copy">Kelola pembaruan harga komoditas harian agar petani memiliki acuan jual yang konsisten.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.prices.store') }}" class="section-panel space-y-5">
            @csrf
            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Tanggal berlaku</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="field-input py-3" required>
                    @error('tanggal') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Pasar</label>
                    <select name="id_pasar" class="field-input py-3" required>
                        @foreach ($markets as $market)
                            <option value="{{ $market->id }}" @selected(old('id_pasar') == $market->id)>{{ $market->nama_pasar }}</option>
                        @endforeach
                    </select>
                    @error('id_pasar') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Status</label>
                    <select name="status" class="field-input py-3">
                        <option value="verified" @selected(old('status') === 'verified')>Verified</option>
                        <option value="submitted" @selected(old('status') === 'submitted')>Submitted</option>
                        <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($commodities as $commodity)
                    <label class="rounded-2xl border border-[#e3ede1] bg-[#f7faf7] p-4">
                        <span class="text-sm font-bold text-[#061826]">{{ $commodity->nama_komoditas }}</span>
                        <input type="number" min="0" step="100" name="prices[{{ $commodity->id }}]" value="{{ old('prices.'.$commodity->id, $commodity->harga_acuan) }}" class="field-input mt-3 py-3" placeholder="Harga per {{ $commodity->satuan }}">
                    </label>
                @endforeach
            </div>
            <div class="flex justify-end">
                <button class="btn-primary px-8 py-3" type="submit">
                    Simpan harga
                    <span class="material-symbols-outlined text-xl">save</span>
                </button>
            </div>
        </form>

        <form method="GET" action="{{ route('admin.prices') }}" class="section-panel">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
                <div class="space-y-2 xl:col-span-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Cari harga</label>
                    <input name="search" value="{{ request('search') }}" class="field-input py-3" placeholder="Komoditas atau kategori">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Dari tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="field-input py-3">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Sampai tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="field-input py-3">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Pasar</label>
                    <select name="market_id" class="field-input py-3">
                        <option value="">Semua pasar</option>
                        @foreach ($markets as $market)
                            <option value="{{ $market->id }}" @selected((string) request('market_id') === (string) $market->id)>{{ $market->nama_pasar }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Status</label>
                    <select name="status" class="field-input py-3">
                        <option value="">Semua status</option>
                        <option value="verified" @selected(request('status') === 'verified')>Verified</option>
                        <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 grid gap-4 md:grid-cols-[1fr_auto_auto] md:items-end">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Komoditas</label>
                    <select name="commodity_id" class="field-input py-3">
                        <option value="">Semua komoditas</option>
                        @foreach ($commodities as $commodity)
                            <option value="{{ $commodity->id }}" @selected((string) request('commodity_id') === (string) $commodity->id)>{{ $commodity->nama_komoditas }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('admin.prices') }}" class="btn-secondary py-3 text-center">Reset</a>
                <button class="btn-primary py-3" type="submit">Terapkan</button>
            </div>
        </form>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Komoditas</th>
                        <th>Harga</th>
                        <th>Berlaku</th>
                        <th>Status</th>
                        <th>Trend</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prices as $price)
                        <tr>
                            <td>
                                <p class="font-heading text-base font-extrabold text-[#061826]">{{ $price['commodity_name'] }}</p>
                                <p class="text-xs text-[#718174]">{{ $price['source_note'] }}</p>
                            </td>
                            <td class="font-heading text-lg font-extrabold text-[#061826]">Rp {{ number_format($price['price'], 0, ',', '.') }}</td>
                            <td class="font-semibold">{{ \Carbon\Carbon::parse($price['effective_date'])->translatedFormat('d M Y') }}</td>
                            <td><span class="status-pill status-muted">{{ $price['status'] }}</span></td>
                            <td>
                                <span class="status-pill {{ $price['trend'] === 'up' ? 'status-success' : ($price['trend'] === 'down' ? 'status-danger' : 'status-muted') }}">
                                    {{ $price['trend'] === 'steady' ? 'stabil' : $price['trend_percent'].'%' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-sm font-semibold text-[#718174]">Tidak ada harga yang cocok dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $prices->links() }}
            </div>
        </div>
    </div>
@endsection
