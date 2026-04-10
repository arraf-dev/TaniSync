@extends('layouts.app', ['title' => 'Dashboard Petani', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Dashboard petani</p>
            <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Ringkasan panen dan harga terbaru</h2>
            <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Halaman utama petani menjaga alur tetap ringkas: lihat progress panen, cek harga, lalu lanjutkan ke form catat panen.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <x-tanisync.stat-card :label="$metric['label']" :value="$metric['value']" :detail="$metric['detail']" :icon="$metric['icon']" :tone="$metric['tone']" />
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="surface-panel p-6 md:p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-heading text-2xl font-extrabold text-[#172018]">Grafik hasil panen</h3>
                        <p class="mt-2 text-sm leading-6 text-[#5b6658]">Visual sederhana untuk membantu petani melihat perkembangan hasil dari waktu ke waktu.</p>
                    </div>
                    <span class="rounded-full bg-[#dff2df] px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-[#196b2c]">6 bulan</span>
                </div>
                <div class="mt-8 flex h-72 items-end gap-4">
                    @foreach ($trends as $point)
                        <div class="flex flex-1 flex-col items-center gap-3">
                            <div class="flex h-full w-full items-end rounded-[1.5rem] bg-[#f1f4ee] p-2">
                                <div class="w-full rounded-[1.1rem] bg-[#196b2c]/85" style="height: {{ $point['value'] }}%"></div>
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
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#9c5421]">Harga terkini</p>
                    <h3 class="mt-2 font-heading text-2xl font-extrabold text-[#172018]">Referensi cepat sebelum menjual panen</h3>
                </div>
                <div class="mt-6 space-y-4">
                    @foreach ($prices as $price)
                        <div class="rounded-[1.5rem] bg-white p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-heading text-lg font-bold text-[#172018]">{{ $price['commodity_name'] }}</p>
                                    <p class="text-xs text-[#5b6658]">{{ $price['source_note'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-heading text-xl font-extrabold text-[#196b2c]">Rp {{ number_format($price['price'], 0, ',', '.') }}</p>
                                    <p class="text-xs text-[#5b6658]">{{ $price['effective_date'] }}</p>
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
