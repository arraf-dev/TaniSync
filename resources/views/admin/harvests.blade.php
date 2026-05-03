@extends('layouts.app', ['title' => 'Panen Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        @if (session('status'))
            <div class="rounded-2xl border border-[#078d45]/20 bg-[#e6f7ed] px-4 py-3 text-sm font-semibold text-[#05753a]">{{ session('status') }}</div>
        @endif

        <div class="page-heading">
            <div>
                <p class="page-kicker">Monitoring panen</p>
                <h2 class="page-title">Pantau log panen yang masuk</h2>
                <p class="page-copy">Tinjau catatan panen terbaru, periksa status verifikasi, dan siapkan data untuk laporan desa.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.harvests') }}" class="section-panel">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
                <div class="space-y-2 xl:col-span-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Cari</label>
                    <input name="search" value="{{ request('search') }}" class="field-input py-3" placeholder="Petani, komoditas, lokasi, catatan">
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
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Komoditas</label>
                    <select name="commodity_id" class="field-input py-3">
                        <option value="">Semua komoditas</option>
                        @foreach ($commodities as $commodity)
                            <option value="{{ $commodity->id }}" @selected((string) request('commodity_id') === (string) $commodity->id)>{{ $commodity->nama_komoditas }}</option>
                        @endforeach
                    </select>
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
            <div class="mt-4 grid gap-4 md:grid-cols-[1fr_auto_auto] md:items-end">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Petani</label>
                    <select name="user_id" class="field-input py-3">
                        <option value="">Semua petani</option>
                        @foreach ($farmers as $farmer)
                            <option value="{{ $farmer->id }}" @selected((string) request('user_id') === (string) $farmer->id)>{{ $farmer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('admin.harvests') }}" class="btn-secondary py-3 text-center">Reset</a>
                <button class="btn-primary py-3" type="submit">Terapkan</button>
            </div>
        </form>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Petani</th>
                        <th>Komoditas</th>
                        <th class="text-right">Jumlah</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($harvests as $harvest)
                        <tr>
                            <td>
                                <p class="font-heading text-base font-extrabold text-[#061826]">{{ $harvest['user_name'] }}</p>
                                <p class="text-xs text-[#718174]">{{ $harvest['location'] }}</p>
                            </td>
                            <td>
                                <p class="font-semibold text-[#061826]">{{ $harvest['commodity_name'] }}</p>
                                <p class="text-xs text-[#718174]">{{ $harvest['quality'] }}</p>
                            </td>
                            <td class="text-right font-heading text-lg font-extrabold text-[#061826]">{{ $harvest['quantity'] }} {{ $harvest['unit'] }}</td>
                            <td class="font-semibold">{{ \Carbon\Carbon::parse($harvest['harvest_date'])->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="status-pill {{ $harvest['status'] === 'terverifikasi' ? 'status-success' : ($harvest['status'] === 'menunggu' ? 'status-warning' : 'status-danger') }}">{{ $harvest['status'] }}</span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.harvests.status', $harvest['id']) }}" class="flex justify-end gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="field-input w-40 py-2 text-sm">
                                        <option value="menunggu" @selected($harvest['status'] === 'menunggu')>Menunggu</option>
                                        <option value="terverifikasi" @selected($harvest['status'] === 'terverifikasi')>Terverifikasi</option>
                                        <option value="butuh-review" @selected($harvest['status'] === 'butuh-review')>Butuh review</option>
                                    </select>
                                    <button class="btn-compact" type="submit">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-sm font-semibold text-[#718174]">Tidak ada catatan panen yang cocok dengan filter.</td>
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
