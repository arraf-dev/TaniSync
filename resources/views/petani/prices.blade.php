@extends('layouts.app', ['title' => 'Harga Petani', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Harga komoditas</p>
            <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Pantau referensi harga terbaru</h2>
            <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Semua harga di tahap Laravel Blade ini memakai sumber mock, tetapi struktur tabelnya sudah mengikuti data yang akan datang dari backend domain.</p>
        </div>

        <div class="surface-panel overflow-x-auto p-6 md:p-8">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Komoditas</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Harga</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Diperbarui</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Trend</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prices as $price)
                        <tr>
                            <td class="rounded-l-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <p class="font-heading text-base font-bold text-[#172018]">{{ $price['commodity_name'] }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $price['category'] }}</p>
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
