@extends('layouts.app', ['title' => 'Dashboard Petani', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        <div class="page-heading">
            <div>
                <p class="page-kicker">Dashboard petani</p>
                <h2 class="page-title">Ringkasan panen dan harga terbaru</h2>
                <p class="page-copy">Lihat progress panen, cek harga, lalu lanjutkan ke form catat panen tanpa alur yang berbelit.</p>
            </div>
            <a href="{{ route('petani.harvests.create') }}" class="btn-primary">
                Catat panen
                <span class="material-symbols-outlined text-xl">add_circle</span>
            </a>
        </div>

        <div class="section-panel flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-bold text-[#078d45]">Jurnal panen aktif</p>
                <h3 class="mt-1 font-heading text-2xl font-extrabold text-[#061826]">Input panen masuk ke riwayat dan menunggu verifikasi admin.</h3>
            </div>
            <p class="max-w-lg text-sm leading-7 text-[#5c6f62]">Gunakan data ini untuk meninjau produksi pribadi, memantau harga referensi, dan menyiapkan rekap panen berikutnya.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <x-tanisync.stat-card :label="$metric['label']" :value="$metric['value']" :detail="$metric['detail']" :icon="$metric['icon']" :tone="$metric['tone']" />
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="section-panel">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-heading text-2xl font-extrabold text-[#061826]">Grafik hasil panen</h3>
                        <p class="mt-2 text-sm leading-6 text-[#5c6f62]">Perkembangan hasil enam bulan terakhir.</p>
                    </div>
                    <span class="status-pill status-success">6 bulan</span>
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
                    <p class="page-kicker">Harga terkini</p>
                    <h3 class="mt-2 font-heading text-2xl font-extrabold text-[#061826]">Referensi cepat sebelum menjual panen</h3>
                </div>
                <div class="mt-6 space-y-3">
                    @foreach ($prices as $price)
                        <div class="rounded-2xl border border-[#e3ede1] bg-white p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-heading text-lg font-extrabold text-[#061826]">{{ $price['commodity_name'] }}</p>
                                    <p class="text-xs text-[#718174]">{{ $price['source_note'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-heading text-xl font-extrabold text-[#078d45]">Rp {{ number_format($price['price'], 0, ',', '.') }}</p>
                                    <p class="text-xs text-[#718174]">{{ $price['effective_date'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <x-tanisync.action-card href="{{ route('petani.harvests.create') }}" title="Catat panen baru" description="Tambahkan hasil panen hari ini." icon="add_box" tone="primary" />
            <x-tanisync.action-card href="{{ route('petani.harvests') }}" title="Lihat riwayat" description="Tinjau seluruh catatan panen sebelumnya." icon="history" tone="neutral" />
            <x-tanisync.action-card href="{{ route('petani.prices') }}" title="Cek harga" description="Bandingkan harga komoditas terbaru." icon="monitoring" tone="accent" />
        </div>
    </div>
@endsection
