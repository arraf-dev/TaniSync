@extends('layouts.app', ['title' => 'Aktivitas Sistem', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        <div class="page-heading">
            <div>
                <p class="page-kicker">Audit trail</p>
                <h2 class="page-title">Aktivitas sistem</h2>
                <p class="page-copy">Pantau perubahan penting pada akses, komoditas, harga, dan catatan panen.</p>
            </div>
        </div>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th>Pengguna</th>
                        <th>Waktu</th>
                        <th>Alamat IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr>
                            <td>
                                <p class="font-heading text-base font-extrabold text-[#061826]">{{ $activity->description }}</p>
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#718174]">{{ str_replace('_', ' ', $activity->action) }}</p>
                            </td>
                            <td>
                                <p class="font-semibold text-[#061826]">{{ $activity->user?->name ?? 'Sistem' }}</p>
                                <p class="text-xs text-[#718174]">{{ $activity->user?->email ?? 'Otomatis' }}</p>
                            </td>
                            <td class="font-semibold">{{ $activity->created_at->translatedFormat('d M Y H:i') }}</td>
                            <td class="text-sm text-[#718174]">{{ $activity->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-sm font-semibold text-[#718174]">Belum ada aktivitas yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
