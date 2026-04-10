@extends('layouts.app', ['title' => 'Harga Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Harga harian</p>
                <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Update harga komoditas</h2>
                <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Harga pada MVP dikelola secara manual oleh admin agar tetap stabil walau belum ada integrasi pihak ketiga.</p>
            </div>
            <button class="btn-primary">Input harga baru</button>
        </div>

        <div class="surface-panel p-5 md:p-6">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Tanggal berlaku</label>
                    <input type="date" class="field-input py-3">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Komoditas</label>
                    <select class="field-input py-3">
                        <option>Semua komoditas</option>
                        @foreach ($prices as $price)
                            <option>{{ $price['commodity_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Sumber</label>
                    <select class="field-input py-3">
                        <option>Input manual admin</option>
                        <option>Rekap pasar mitra</option>
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button class="btn-secondary">Reset</button>
                    <button class="btn-primary">Terapkan</button>
                </div>
            </div>
        </div>

        <div class="surface-panel overflow-x-auto p-6 md:p-8">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Komoditas</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Harga</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Berlaku</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Trend</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prices as $price)
                        <tr>
                            <td class="rounded-l-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <p class="font-heading text-base font-bold text-[#172018]">{{ $price['commodity_name'] }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $price['source_note'] }}</p>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4 font-heading text-lg font-bold text-[#172018]">Rp {{ number_format($price['price'], 0, ',', '.') }}</td>
                            <td class="bg-[#f1f4ee] px-4 py-4 font-semibold text-[#172018]">{{ \Carbon\Carbon::parse($price['effective_date'])->translatedFormat('d M Y') }}</td>
                            <td class="rounded-r-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $price['trend'] === 'up' ? 'bg-[#dff2df] text-[#196b2c]' : ($price['trend'] === 'down' ? 'bg-red-50 text-red-600' : 'bg-white text-[#5b6658]') }}">
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
