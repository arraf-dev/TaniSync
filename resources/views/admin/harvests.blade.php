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

        <div class="section-panel">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Periode</label>
                    <input type="date" class="field-input py-3">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Komoditas</label>
                    <select class="field-input py-3">
                        <option>Semua komoditas</option>
                        @foreach ($harvests as $harvest)
                            <option>{{ $harvest['commodity_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Status</label>
                    <select class="field-input py-3">
                        <option>Semua status</option>
                        <option>Terverifikasi</option>
                        <option>Menunggu</option>
                        <option>Butuh review</option>
                    </select>
                </div>
            </div>
        </div>

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
                    @foreach ($harvests as $harvest)
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
