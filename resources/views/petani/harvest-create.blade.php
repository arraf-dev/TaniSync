@extends('layouts.app', ['title' => 'Catat Panen', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        <div class="page-heading">
            <div>
                <p class="page-kicker">Jurnal panen</p>
                <h2 class="page-title">Catat hasil panen dengan langkah singkat</h2>
                <p class="page-copy">Isi komoditas, tanggal, lokasi, jumlah, kualitas, dan catatan bila diperlukan. Data akan masuk ke riwayat dan menunggu verifikasi admin.</p>
            </div>
        </div>

        <div class="space-y-6">
            @if (session('status'))
                <div class="rounded-2xl border border-[#078d45]/20 bg-[#e6f7ed] px-4 py-3 text-sm font-semibold text-[#05753a]">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">Periksa kembali data panen yang Anda isi.</div>
            @endif

            <form method="POST" action="{{ route('petani.harvests.store') }}" class="section-panel space-y-6">
                @csrf
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-semibold text-[#5c6f62]">Komoditas</label>
                        <select name="commodity_id" class="field-input">
                            @foreach ($commodities as $commodity)
                                <option value="{{ $commodity->id }}" @selected(old('commodity_id') == $commodity->id)>{{ $commodity->nama_komoditas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-[#5c6f62]">Tanggal panen</label>
                        <input type="date" name="harvest_date" value="{{ old('harvest_date') }}" class="field-input" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-[#5c6f62]">Lokasi / blok lahan</label>
                        <input type="text" name="location" value="{{ old('location') }}" class="field-input" placeholder="Blok Utara 02" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-[#5c6f62]">Jumlah panen</label>
                        <input type="number" step="0.01" name="quantity" value="{{ old('quantity') }}" class="field-input" placeholder="0" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-[#5c6f62]">Satuan</label>
                        <select name="unit" class="field-input">
                            <option>kg</option>
                            <option>kuintal</option>
                            <option>ton</option>
                        </select>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-semibold text-[#5c6f62]">Kualitas panen</label>
                        <input type="text" name="quality" value="{{ old('quality', 'Grade A') }}" class="field-input" placeholder="Grade A" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-[#5c6f62]">Catatan tambahan</label>
                    <textarea name="note" rows="5" class="field-input" placeholder="Tulis kondisi panen, cuaca, atau catatan mutu bila diperlukan.">{{ old('note') }}</textarea>
                </div>

                <div class="flex flex-col gap-4 border-t border-[#eef4ed] pt-5 md:flex-row md:items-center md:justify-between">
                    <p class="flex items-center gap-2 text-sm text-[#5c6f62]">
                        <span class="material-symbols-outlined text-lg text-[#078d45]">cloud_done</span>
                        Tersimpan ke database setelah dikirim.
                    </p>
                    <button type="submit" class="btn-primary px-8 py-4 text-base">
                        Simpan catatan panen
                        <span class="material-symbols-outlined text-xl">save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
