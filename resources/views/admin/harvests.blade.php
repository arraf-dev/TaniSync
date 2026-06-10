@extends('layouts.app', ['title' => 'Panen Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        @if (session('status'))
            <div class="rounded-2xl border border-[#196b2c]/20 bg-[#dff2df] px-5 py-4 text-sm font-semibold text-[#114b1e]">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined icon-filled text-lg">check_circle</span>
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <div class="space-y-3">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Monitoring panen</p>
            <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Pantau log panen yang masuk</h2>
            <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Admin dapat meninjau catatan panen terbaru, memeriksa status verifikasi, dan menyiapkan data untuk laporan desa.</p>
        </div>

        <form method="GET" action="{{ route('admin.harvests') }}" class="surface-panel p-5 md:p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-end">
                <div class="flex-1 grid gap-4 sm:grid-cols-2 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="commodity_id" class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Komoditas</label>
                        <select id="commodity_id" name="commodity_id" class="field-input py-3">
                            <option value="all" {{ $currentCommodity === 'all' ? 'selected' : '' }}>Semua komoditas</option>
                            @foreach ($commodities as $commodity)
                                <option value="{{ $commodity->id }}" {{ (string) $currentCommodity === (string) $commodity->id ? 'selected' : '' }}>{{ $commodity->nama_komoditas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="status" class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Status</label>
                        <select id="status" name="status" class="field-input py-3">
                            <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>Semua status</option>
                            <option value="menunggu" {{ $currentStatus === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="terverifikasi" {{ $currentStatus === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="ditolak" {{ $currentStatus === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.harvests') }}" class="btn-secondary py-3">Reset</a>
                    <button type="submit" class="btn-primary py-3">Terapkan</button>
                </div>
            </div>
        </form>

        <div class="surface-panel overflow-x-auto p-6 md:p-8">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Petani</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Komoditas</th>
                        <th class="px-4 py-2 text-right text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Jumlah</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Status</th>
                        <th class="px-4 py-2 text-center text-xs font-bold uppercase tracking-[0.18em] text-[#5b6658]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($harvests as $harvest)
                        <tr>
                            <td class="rounded-l-[1.5rem] bg-[#f1f4ee] px-4 py-4">
                                <p class="font-heading text-base font-bold text-[#172018]">{{ $harvest->user?->name ?? '-' }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $harvest->location }}</p>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4">
                                <p class="font-semibold text-[#172018]">{{ $harvest->commodity?->nama_komoditas ?? '-' }}</p>
                                <p class="text-xs text-[#5b6658]">{{ $harvest->quality }}</p>
                            </td>
                            <td class="bg-[#f1f4ee] px-4 py-4 text-right font-heading text-lg font-bold text-[#172018]">{{ number_format($harvest->quantity, 1, ',', '.') }} {{ $harvest->unit }}</td>
                            <td class="bg-[#f1f4ee] px-4 py-4 font-semibold text-[#172018]">{{ $harvest->harvest_date->translatedFormat('d M Y') }}</td>
                            <td class="bg-[#f1f4ee] px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] {{ $harvest->status === 'terverifikasi' ? 'bg-[#dff2df] text-[#196b2c]' : ($harvest->status === 'menunggu' ? 'bg-orange-100 text-[#9c5421]' : 'bg-red-50 text-red-600') }}">{{ $harvest->status }}</span>
                            </td>
                            <td class="rounded-r-[1.5rem] bg-[#f1f4ee] px-4 py-4 text-center">
                                @if ($harvest->status === 'menunggu')
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST" action="{{ route('admin.harvests.status', $harvest) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="terverifikasi">
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-xl bg-[#196b2c] px-3 py-1.5 text-xs font-bold text-white transition hover:bg-[#114b1e]">
                                                <span class="material-symbols-outlined text-sm">check</span>
                                                Verifikasi
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.harvests.status', $harvest) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-xl border border-red-200 bg-white px-3 py-1.5 text-xs font-bold text-red-600 transition hover:bg-red-50">
                                                <span class="material-symbols-outlined text-sm">close</span>
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-[#5b6658] font-semibold">
                                        Oleh: {{ $harvest->verifier?->name ?? 'Sistem' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="rounded-[1.5rem] bg-[#f1f4ee] px-4 py-12 text-center text-[#5b6658]">
                                <span class="material-symbols-outlined mb-2 text-4xl text-[#cad4c4]">rebase_edit</span>
                                <p>Tidak ada data panen yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
