@extends('layouts.app', ['title' => 'Komoditas Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Master data</p>
                <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Manajemen komoditas</h2>
                <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Kelola daftar komoditas desa agar form panen dan harga harian tetap akurat.</p>
            </div>
            <button class="btn-primary">Tambah komoditas</button>
        </div>

        <div class="surface-panel overflow-x-auto p-6 md:p-8">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Komoditas</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Kategori</th>
                        <th class="px-4 py-2 text-center text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Satuan</th>
                        <th class="px-4 py-2 text-center text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commodities as $commodity)
                        <tr>
                            <td class="rounded-l-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <p class="font-heading text-base font-bold text-[#172018]">{{ $commodity['name'] }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $commodity['description'] }}</p>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4">
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">{{ $commodity['category'] }}</span>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4 text-center font-semibold text-[#172018]">{{ $commodity['unit'] }}</td>
                            <td class="rounded-r-[1.5rem] bg-[#f1f4ee] px-4 py-4 text-center">
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $commodity['status'] === 'aktif' ? 'bg-[#dff2df] text-[#196b2c]' : 'bg-orange-100 text-[#9c5421]' }}">{{ $commodity['status'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
