@extends('layouts.app', ['title' => 'Laporan Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        <div class="page-heading">
            <div>
                <p class="page-kicker">Laporan</p>
                <h2 class="page-title">Analitik dan ekspor</h2>
                <p class="page-copy">Laporan MVP membaca data panen dari MySQL dengan filter dasar. Export PDF dan Excel disiapkan untuk tahap berikutnya.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.reports') }}" class="section-panel">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
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
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Petani</label>
                    <select name="user_id" class="field-input py-3">
                        <option value="">Semua petani</option>
                        @foreach ($farmers as $farmer)
                            <option value="{{ $farmer->id }}" @selected((string) request('user_id') === (string) $farmer->id)>{{ $farmer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-3">
                <a href="{{ route('admin.reports') }}" class="btn-secondary">Reset</a>
                <button class="btn-primary" type="submit">Terapkan</button>
            </div>
        </form>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="data-card">
                <p class="text-sm font-semibold text-[#718174]">Total panen</p>
                <p class="mt-2 font-heading text-3xl font-extrabold text-[#061826]">{{ number_format($report['total_quantity'], 2, ',', '.') }} kg</p>
                <p class="mt-2 text-sm text-[#5c6f62]">Sesuai filter aktif</p>
            </div>
            <div class="data-card">
                <p class="text-sm font-semibold text-[#718174]">Catatan diverifikasi</p>
                <p class="mt-2 font-heading text-3xl font-extrabold text-[#061826]">{{ $report['verified_count'] }}</p>
                <p class="mt-2 text-sm text-[#5c6f62]">Siap rekap</p>
            </div>
            <div class="data-card">
                <p class="text-sm font-semibold text-[#718174]">Catatan menunggu</p>
                <p class="mt-2 font-heading text-3xl font-extrabold text-[#061826]">{{ $report['pending_count'] }}</p>
                <p class="mt-2 text-sm text-[#5c6f62]">Butuh tindak lanjut</p>
            </div>
        </div>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Petani</th>
                        <th>Komoditas</th>
                        <th class="text-right">Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report['harvests'] as $harvest)
                        <tr>
                            <td class="font-semibold text-[#061826]">{{ $harvest['user_name'] }}</td>
                            <td>
                                <p class="font-semibold text-[#061826]">{{ $harvest['commodity_name'] }}</p>
                                <p class="text-xs text-[#718174]">{{ $harvest['location'] }}</p>
                            </td>
                            <td class="text-right font-heading font-extrabold text-[#061826]">{{ $harvest['quantity'] }} {{ $harvest['unit'] }}</td>
                            <td><span class="status-pill status-muted">{{ $harvest['status'] }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="rounded-2xl border border-[#e7eee5] bg-[#f7faf7] px-4 py-8 text-center text-sm text-[#718174]">Belum ada catatan panen untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            @foreach ([['picture_as_pdf', 'Ekspor PDF', 'Belum aktif di MVP ini.'], ['table_view', 'Ekspor Excel', 'Belum aktif di MVP ini.']] as [$icon, $label, $desc])
                <div class="data-card flex items-center gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#078d45]/10 text-[#078d45]">
                        <span class="material-symbols-outlined icon-filled text-2xl">{{ $icon }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-heading text-base font-extrabold text-[#061826]">{{ $label }}</p>
                        <p class="text-sm leading-6 text-[#5c6f62]">{{ $desc }}</p>
                    </div>
                    <button type="button" disabled class="btn-compact opacity-60">Segera</button>
                </div>
            @endforeach
        </div>
    </div>
@endsection
