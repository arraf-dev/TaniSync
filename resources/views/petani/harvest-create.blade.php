@extends('layouts.app', ['title' => 'Catat Panen', 'pageTitle' => $pageTitle])

@section('content')
    <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <div class="surface-muted p-8">
            <div class="space-y-4">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#9c5421]">Jurnal panen</p>
                <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Catat hasil panen dengan langkah yang singkat.</h2>
                <p class="text-sm leading-7 text-[#5b6658]">Form ini mengikuti PRD: tanggal, komoditas, jumlah, satuan, dan catatan tambahan. Saya tambahkan lokasi dan kualitas agar lebih berguna saat validasi admin.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80" alt="Lahan pertanian" class="mt-8 h-[360px] w-full rounded-[2rem] object-cover">
        </div>

        <div class="space-y-6">
            @if (session('status'))
                <div class="rounded-2xl border border-[#196b2c]/20 bg-[#dff2df] px-4 py-3 text-sm text-[#114b1e]">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('petani.harvests.store') }}" class="surface-panel space-y-6 p-6 md:p-8">
                @csrf
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-semibold text-[#5b6658]">Komoditas</label>
                        <select name="commodity_id" class="field-input">
                            @foreach ($commodities as $commodity)
                                <option value="{{ $commodity['id'] }}">{{ $commodity['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-[#5b6658]">Tanggal panen</label>
                        <input type="date" name="harvest_date" class="field-input" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-[#5b6658]">Lokasi / blok lahan</label>
                        <input type="text" name="location" class="field-input" placeholder="Blok Utara 02" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-[#5b6658]">Jumlah panen</label>
                        <input type="number" step="0.01" name="quantity" class="field-input" placeholder="0" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-[#5b6658]">Satuan</label>
                        <select name="unit" class="field-input">
                            <option>kg</option>
                            <option>kuintal</option>
                            <option>ton</option>
                        </select>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-semibold text-[#5b6658]">Kualitas panen</label>
                        <input type="text" name="quality" class="field-input" placeholder="Grade A" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-[#5b6658]">Catatan tambahan</label>
                    <textarea name="note" rows="5" class="field-input" placeholder="Tulis kondisi panen, cuaca, atau catatan mutu bila diperlukan."></textarea>
                </div>

                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <p class="flex items-center gap-2 text-sm text-[#5b6658]">
                        <span class="material-symbols-outlined text-lg text-[#196b2c]">cloud_done</span>
                        Data contoh ini akan di-flash ke session sebagai simulasi sebelum database domain penuh disiapkan.
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
