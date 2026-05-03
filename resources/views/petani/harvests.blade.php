@extends('layouts.app', ['title' => 'Riwayat Panen', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        @if (session('status'))
            <div class="rounded-2xl border border-[#078d45]/20 bg-[#e6f7ed] px-4 py-3 text-sm font-semibold text-[#05753a]">{{ session('status') }}</div>
        @endif

        <div class="page-heading">
            <div>
                <p class="page-kicker">Riwayat panen</p>
                <h2 class="page-title">Catatan panen pribadi</h2>
                <p class="page-copy">Tinjau hasil produksi, lokasi, kualitas, dan status verifikasi dari panen sebelumnya.</p>
            </div>
            <a href="{{ route('petani.harvests.create') }}" class="btn-primary">
                Catat panen
                <span class="material-symbols-outlined text-xl">add</span>
            </a>
        </div>

        <form method="GET" action="{{ route('petani.harvests') }}" class="section-panel">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div class="space-y-2 xl:col-span-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Cari riwayat</label>
                    <input name="search" value="{{ request('search') }}" class="field-input py-3" placeholder="Komoditas, lokasi, atau catatan">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Dari tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="field-input py-3">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Sampai tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="field-input py-3">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Status</label>
                    <select name="status" class="field-input py-3">
                        <option value="">Semua status</option>
                        <option value="terverifikasi" @selected(request('status') === 'terverifikasi')>Terverifikasi</option>
                        <option value="menunggu" @selected(request('status') === 'menunggu')>Menunggu</option>
                        <option value="butuh-review" @selected(request('status') === 'butuh-review')>Butuh review</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-3">
                <a href="{{ route('petani.harvests') }}" class="btn-secondary">Reset</a>
                <button class="btn-primary" type="submit">Terapkan</button>
            </div>
        </form>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Komoditas</th>
                        <th>Tanggal</th>
                        <th class="text-right">Jumlah</th>
                        <th>Kualitas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($harvests as $harvest)
                        <tr>
                            <td>
                                <p class="font-heading text-base font-extrabold text-[#061826]">{{ $harvest['commodity_name'] }}</p>
                                <p class="text-xs text-[#718174]">{{ $harvest['location'] }}</p>
                            </td>
                            <td class="font-semibold">{{ \Carbon\Carbon::parse($harvest['harvest_date'])->translatedFormat('d M Y') }}</td>
                            <td class="text-right font-heading text-lg font-extrabold text-[#061826]">{{ $harvest['quantity'] }} {{ $harvest['unit'] }}</td>
                            <td><span class="status-pill status-muted">{{ $harvest['quality'] }}</span></td>
                            <td><span class="status-pill {{ $harvest['status'] === 'terverifikasi' ? 'status-success' : ($harvest['status'] === 'menunggu' ? 'status-warning' : 'status-danger') }}">{{ $harvest['status'] }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-sm font-semibold text-[#718174]">Tidak ada riwayat panen yang cocok dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $harvests->links() }}
            </div>
        </div>
    </div>
@endsection
