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
                    @foreach ($harvests as $harvest)
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
