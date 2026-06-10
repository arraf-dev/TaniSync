@extends('layouts.app', ['title' => 'Input Harga', 'pageTitle' => $pageTitle])

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('admin.prices') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-[#196b2c] hover:underline">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Kembali ke daftar harga
        </a>

        <div class="surface-panel p-6 md:p-8">
            <div class="mb-6 space-y-2">
                <h2 class="font-heading text-2xl font-extrabold text-[#172018]">Input harga harian</h2>
                <p class="text-sm text-[#5b6658]">Masukkan harga per kg untuk setiap komoditas aktif. Harga berlaku untuk tanggal yang dipilih.</p>
            </div>

            <form method="POST" action="{{ route('admin.prices.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="id_pasar" class="text-sm font-semibold text-[#5b6658]">Pasar</label>
                        <select id="id_pasar" name="id_pasar" required class="field-input">
                            @foreach ($markets as $market)
                                <option value="{{ $market->id }}" {{ old('id_pasar') == $market->id ? 'selected' : '' }}>{{ $market->nama_pasar }}</option>
                            @endforeach
                        </select>
                        @error('id_pasar') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="tanggal" class="text-sm font-semibold text-[#5b6658]">Tanggal berlaku</label>
                        <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', now()->toDateString()) }}" required class="field-input">
                        @error('tanggal') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-sm font-semibold text-[#5b6658]">Harga per komoditas (Rp)</p>
                    @foreach ($commodities as $commodity)
                        <div class="flex items-center gap-4 rounded-2xl bg-[#f1f4ee] p-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-lg">
                                {{ $commodity->icon ?? '📦' }}
                            </div>
                            <div class="flex-1">
                                <p class="font-heading text-sm font-bold text-[#172018]">{{ $commodity->nama_komoditas }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $commodity->category?->nama_kategori }} · {{ $commodity->satuan }}</p>
                            </div>
                            <div class="w-40">
                                <input type="number" name="harga[{{ $commodity->id }}]" value="{{ old('harga.' . $commodity->id, $commodity->harga_acuan) }}" required class="field-input py-2.5 text-right" step="100" min="0">
                            </div>
                        </div>
                    @endforeach
                    @error('harga') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary w-full py-4 text-base">
                    <span class="material-symbols-outlined icon-filled text-lg">save</span>
                    Simpan harga harian
                </button>
            </form>
        </div>
    </div>
@endsection
