@extends('layouts.app', ['title' => 'Harga Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        @if (session('status'))
            <div class="rounded-2xl border border-[#196b2c]/20 bg-[#dff2df] px-5 py-4 text-sm font-semibold text-[#114b1e]">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined icon-filled text-lg">check_circle</span>
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Harga harian</p>
                <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Update harga komoditas</h2>
                <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Harga dikelola secara manual oleh admin. Klik tombol di bawah untuk input harga baru.</p>
            </div>
            <a href="{{ route('admin.prices.create') }}" class="btn-primary">
                <span class="material-symbols-outlined icon-filled text-lg">price_change</span>
                Input harga baru
            </a>
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
                    @forelse ($prices as $price)
                        <tr>
                            <td class="rounded-l-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">{{ $price['commodity']->icon ?? '📦' }}</span>
                                    <div>
                                        <p class="font-heading text-base font-bold text-[#172018]">{{ $price['commodity']->nama_komoditas }}</p>
                                        <p class="text-xs text-[#5b6658]">{{ $price['source_note'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4 font-heading text-lg font-bold text-[#172018]">Rp {{ number_format($price['price'], 0, ',', '.') }}</td>
                            <td class="bg-[#f1f4ee] px-4 py-4 font-semibold text-[#172018]">{{ $price['effective_date']->translatedFormat('d M Y') }}</td>
                            <td class="rounded-r-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $price['trend'] === 'up' ? 'bg-[#dff2df] text-[#196b2c]' : ($price['trend'] === 'down' ? 'bg-red-50 text-red-600' : 'bg-white text-[#5b6658]') }}">
                                    {{ $price['trend'] === 'steady' ? 'stabil' : ($price['trend'] === 'up' ? '↑' : '↓') . ' ' . $price['trend_percent'] . '%' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="rounded-[1.5rem] bg-[#f1f4ee] px-4 py-12 text-center text-[#5b6658]">
                                <span class="material-symbols-outlined mb-2 text-4xl text-[#cad4c4]">payments</span>
                                <p>Belum ada data harga. <a href="{{ route('admin.prices.create') }}" class="font-semibold text-[#196b2c] hover:underline">Input sekarang</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
