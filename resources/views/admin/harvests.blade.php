@extends('layouts.app', ['title' => 'Panen Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Monitoring panen</p>
            <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Pantau log panen yang masuk</h2>
            <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Admin dapat meninjau catatan panen terbaru, memeriksa status verifikasi, dan menyiapkan data untuk laporan desa.</p>
        </div>

        <div class="surface-panel p-5 md:p-6">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Periode</label>
                    <input type="date" class="field-input py-3">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Komoditas</label>
                    <select class="field-input py-3">
                        <option>Semua komoditas</option>
                        @foreach ($harvests as $harvest)
                            <option>{{ $harvest['commodity_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Status</label>
                    <select class="field-input py-3">
                        <option>Semua status</option>
                        <option>Terverifikasi</option>
                        <option>Menunggu</option>
                        <option>Butuh review</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="surface-panel overflow-x-auto p-6 md:p-8">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Petani</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Komoditas</th>
                        <th class="px-4 py-2 text-right text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Jumlah</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($harvests as $harvest)
                        <tr>
                            <td class="rounded-l-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <p class="font-heading text-base font-bold text-[#172018]">{{ $harvest['user_name'] }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $harvest['location'] }}</p>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4">
                                <p class="font-semibold text-[#172018]">{{ $harvest['commodity_name'] }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $harvest['quality'] }}</p>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4 text-right font-heading text-lg font-bold text-[#172018]">{{ $harvest['quantity'] }} {{ $harvest['unit'] }}</td>
                            <td class="bg-[#f1f4ee] px-4 py-4 font-semibold text-[#172018]">{{ \Carbon\Carbon::parse($harvest['harvest_date'])->translatedFormat('d M Y') }}</td>
                            <td class="rounded-r-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $harvest['status'] === 'terverifikasi' ? 'bg-[#dff2df] text-[#196b2c]' : ($harvest['status'] === 'menunggu' ? 'bg-orange-100 text-[#9c5421]' : 'bg-red-50 text-red-600') }}">{{ $harvest['status'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
