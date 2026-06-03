@extends('layouts.app', ['title' => 'Dashboard Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        <div class="page-heading">
            <div>
                <p class="page-kicker">Dashboard admin</p>
                <h2 class="page-title">Ringkasan operasional organisasi</h2>
                <p class="page-copy">Pantau produktivitas organisasi, distribusi panen, dan tindakan utama dari satu ruang kerja yang ringkas.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.access-requests') }}" class="btn-secondary">Persetujuan admin</a>
                <a href="{{ route('admin.prices') }}" class="btn-secondary">Update harga</a>
                <a href="{{ route('admin.harvests') }}" class="btn-primary">Validasi panen</a>
            </div>
        </div>

        <div class="section-panel flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-bold text-[#078d45]">Pusat Data Operasional</p>
                <h3 class="mt-1 font-heading text-2xl font-extrabold text-[#061826]">Pantau harga, panen, dan komoditas dalam satu dashboard.</h3>
            </div>
            <p class="max-w-lg text-sm leading-7 text-[#5c6f62]">Kelola pembaruan harga dan validasi panen secara terstruktur agar laporan organisasi selalu siap ditinjau.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <x-tanisync.stat-card :label="$metric['label']" :value="$metric['value']" :detail="$metric['detail']" :icon="$metric['icon']" :tone="$metric['tone']" />
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
            <div class="section-panel">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-heading text-2xl font-extrabold text-[#061826]">Tren panen bulanan</h3>
                        <p class="mt-2 text-sm leading-6 text-[#5c6f62]">Perubahan volume panen enam bulan terakhir.</p>
                    </div>
                    <span class="rounded-full bg-[#dff2df] px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-[#196b2c]">6 bulan</span>
                </div>
                <div class="mt-8 flex h-72 items-end gap-4">
                    @foreach ($trends as $point)
                        <div class="flex flex-1 flex-col items-center gap-3">
                                <div class="flex h-full w-full items-end rounded-2xl bg-[#f2f7f1] p-2">
                                    <div class="w-full rounded-xl bg-[#078d45]" style="height: {{ $point['value'] }}%"></div>
                                </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-[#061826]">{{ $point['label'] }}</p>
                                <p class="text-xs text-[#718174]">{{ $point['value'] }}%</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="section-panel bg-[#f7faf7]">
                <div>
                    <p class="page-kicker">Distribusi komoditas</p>
                    <h3 class="mt-2 font-heading text-2xl font-extrabold text-[#061826]">Kontributor panen terbesar</h3>
                </div>
                <div class="mt-6 space-y-4">
                    @foreach ($distribution as $item)
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-semibold text-[#061826]">{{ $item['label'] }}</span>
                                <span class="font-bold text-[#078d45]">{{ $item['value'] }}%</span>
                            </div>
                            <div class="h-3 rounded-full bg-white">
                                <div class="h-3 rounded-full bg-[#078d45]" style="width: {{ $item['value'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 rounded-[1.25rem] bg-[#061826] p-5 text-white">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#dff2df]">Catatan admin</p>
                    <p class="mt-2 text-sm leading-7">Gunakan panel harga harian dan validasi panen untuk menjaga laporan organisasi tetap konsisten setiap minggu.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <x-tanisync.action-card href="{{ route('admin.commodities') }}" title="Tambah komoditas" description="Input master komoditas baru untuk organisasi." icon="add_circle" tone="primary" />
            <x-tanisync.action-card href="{{ route('admin.prices') }}" title="Perbarui harga" description="Tetapkan harga harian sebagai acuan petani dan laporan organisasi." icon="price_change" tone="accent" />
            <x-tanisync.action-card href="{{ route('admin.harvests') }}" title="Validasi panen" description="Tinjau log panen yang perlu verifikasi." icon="task_alt" tone="success" />
        </div>

        <div class="section-panel">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="page-kicker">Aktivitas sistem</p>
                    <h3 class="mt-2 font-heading text-2xl font-extrabold text-[#061826]">Perubahan terbaru</h3>
                </div>
                <a href="{{ route('admin.activity-logs') }}" class="btn-compact">Lihat semua</a>
            </div>
            <div class="mt-6 space-y-3">
                @forelse ($recentActivities as $activity)
                    <div class="flex flex-col gap-2 rounded-2xl border border-[#e7eee5] bg-[#f7faf7] p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-[#061826]">{{ $activity->description }}</p>
                            <p class="text-xs text-[#718174]">{{ $activity->user?->name ?? 'Sistem' }}</p>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="rounded-2xl border border-[#e7eee5] bg-[#f7faf7] p-4 text-sm font-semibold text-[#718174]">Belum ada aktivitas yang tercatat.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
