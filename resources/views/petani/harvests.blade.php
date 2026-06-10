@extends('layouts.app', ['title' => 'Riwayat Panen', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Riwayat panen</p>
            <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Catatan panen pribadi</h2>
            <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Gunakan riwayat ini untuk meninjau hasil produksi, memeriksa kualitas, dan menyiapkan rekap panen berikutnya.</p>
        </div>

        <div class="surface-panel overflow-x-auto p-6 md:p-8">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Komoditas</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Tanggal</th>
                        <th class="px-4 py-2 text-right text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Jumlah</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Kualitas</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($harvests as $harvest)
                        <tr>
                            <td class="rounded-l-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <p class="font-heading text-base font-bold text-[#172018]">{{ $harvest->commodity?->nama_komoditas ?? '-' }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $harvest->location }}</p>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4 font-semibold text-[#172018]">{{ $harvest->harvest_date->translatedFormat('d M Y') }}</td>
                            <td class="bg-[#f1f4ee] px-4 py-4 text-right font-heading text-lg font-bold text-[#172018]">{{ number_format($harvest->quantity, 1, ',', '.') }} {{ $harvest->unit }}</td>
                            <td class="bg-[#f1f4ee] px-4 py-4"><span class="rounded-full bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">{{ $harvest->quality }}</span></td>
                            <td class="rounded-r-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $harvest->status === 'terverifikasi' ? 'bg-[#dff2df] text-[#196b2c]' : ($harvest->status === 'menunggu' ? 'bg-orange-100 text-[#9c5421]' : 'bg-red-50 text-red-600') }}">{{ $harvest->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="rounded-[1.5rem] bg-[#f1f4ee] px-4 py-12 text-center text-[#5b6658]">
                                <span class="material-symbols-outlined mb-2 text-4xl text-[#cad4c4]">history</span>
                                <p>Belum ada catatan panen pribadi. <a href="{{ route('petani.harvests.create') }}" class="font-semibold text-[#196b2c] hover:underline">Catat sekarang</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
