@extends('layouts.app', ['title' => 'Dashboard Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Dashboard admin</p>
            <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Ringkasan operasional desa</h2>
            <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Pantau produktivitas desa, cek distribusi panen, dan akses tindakan utama dari satu halaman yang lebih rapi.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <x-tanisync.stat-card :label="$metric['label']" :value="$metric['value']" :detail="$metric['detail']" :icon="$metric['icon']" :tone="$metric['tone']" />
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
            <div class="surface-panel p-6 md:p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-heading text-2xl font-extrabold text-[#172018]">Tren panen bulanan</h3>
                        <p class="mt-2 text-sm leading-6 text-[#5b6658]">Visual sederhana untuk melihat perubahan volume panen dari bulan ke bulan.</p>
                    </div>
                    <span class="rounded-full bg-[#dff2df] px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-[#196b2c]">6 bulan</span>
                </div>
                <div class="mt-8 flex h-72 items-end gap-4">
                    @foreach ($trends as $point)
                        <div class="flex flex-1 flex-col items-center gap-3">
                            <div class="flex h-full w-full items-end rounded-[1.5rem] bg-[#f1f4ee] p-2">
                                <div class="w-full rounded-[1.1rem] bg-[#196b2c]/80" style="height: {{ $point['value'] }}%"></div>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-[#172018]">{{ $point['label'] }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $point['value'] }}%</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="surface-muted p-6 md:p-8">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#9c5421]">Distribusi komoditas</p>
                    <h3 class="mt-2 font-heading text-2xl font-extrabold text-[#172018]">Kontributor panen terbesar</h3>
                </div>
                <div class="mt-6 space-y-4">
                    @foreach ($distribution as $item)
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-semibold text-[#172018]">{{ $item['label'] }}</span>
                                <span class="font-bold text-[#196b2c]">{{ $item['value'] }}%</span>
                            </div>
                            <div class="h-3 rounded-full bg-white">
                                <div class="h-3 rounded-full bg-[#196b2c]" style="width: {{ $item['value'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 rounded-[1.5rem] bg-[#196b2c] p-5 text-white">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#dff2df]">Catatan admin</p>
                    <p class="mt-2 text-sm leading-7">Gunakan panel harga harian dan validasi panen untuk menjaga laporan desa tetap konsisten setiap minggu.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <x-tanisync.action-card href="{{ route('admin.commodities') }}" title="Tambah komoditas" description="Input master komoditas baru untuk desa." icon="add_circle" tone="primary" />
            <x-tanisync.action-card href="{{ route('admin.prices') }}" title="Perbarui harga" description="Catat harga manual harian per komoditas." icon="price_change" tone="accent" />
            <x-tanisync.action-card href="{{ route('admin.harvests') }}" title="Validasi panen" description="Tinjau log panen yang perlu verifikasi." icon="task_alt" tone="success" />
        </div>
    </div>
@endsection
