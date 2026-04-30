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
                <p class="page-copy">Harga MVP dikelola manual oleh admin dan tersimpan ke database MySQL.</p>
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

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Komoditas</th>
                        <th>Harga</th>
                        <th>Berlaku</th>
                        <th>Trend</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prices as $price)
                        <tr>
                            <td>
                                <p class="font-heading text-base font-extrabold text-[#061826]">{{ $price['commodity_name'] }}</p>
                                <p class="text-xs text-[#718174]">{{ $price['source_note'] }}</p>
                            </td>
                            <td class="font-heading text-lg font-extrabold text-[#061826]">Rp {{ number_format($price['price'], 0, ',', '.') }}</td>
                            <td class="font-semibold">{{ \Carbon\Carbon::parse($price['effective_date'])->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="status-pill {{ $price['trend'] === 'up' ? 'status-success' : ($price['trend'] === 'down' ? 'status-danger' : 'status-muted') }}">
                                    {{ $price['trend'] === 'steady' ? 'stabil' : $price['trend_percent'].'%' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
