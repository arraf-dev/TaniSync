@extends('layouts.app', ['title' => 'Laporan Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#9c5421]">Laporan</p>
            <h2 class="editorial-heading font-heading text-4xl font-extrabold text-[#172018]">Analitik dan ekspor</h2>
            <p class="max-w-2xl text-base leading-7 text-[#5b6658]">Frontend Laravel ini menyiapkan alur laporan, filter, dan pilihan ekspor. Proses file aktual akan dihubungkan ke backend domain berikutnya.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="surface-panel space-y-6 p-6 md:p-8">
                <div class="surface-panel p-5 shadow-none bg-[#f1f4ee]/30">
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Rentang tanggal</label>
                            <input type="text" class="field-input py-3" placeholder="01 Apr 2026 - 10 Apr 2026" disabled>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Komoditas</label>
                            <select class="field-input py-3" disabled>
                                <option>Semua komoditas</option>
                                @foreach ($commodities as $commodity)
                                    <option value="{{ $commodity->id }}">{{ $commodity->nama_komoditas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Petani</label>
                            <select class="field-input py-3" disabled>
                                <option>Semua petani</option>
                                <option>Bapak Rahmat</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-3">
                            <button class="btn-secondary py-3 w-full" disabled>Reset</button>
                            <button class="btn-primary py-3 w-full" disabled>Terapkan</button>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-[1.5rem] bg-[#f1f4ee] p-5">
                        <p class="text-sm font-semibold text-[#5b6658]">Total panen</p>
                        <p class="mt-2 font-heading text-3xl font-extrabold text-[#172018]">{{ $totalHarvest }} ton</p>
                        <p class="mt-2 text-sm text-[#5b6658]">Periode berjalan</p>
                    </div>
                    <div class="rounded-[1.5rem] bg-[#f1f4ee] p-5">
                        <p class="text-sm font-semibold text-[#5b6658]">Catatan diverifikasi</p>
                        <p class="mt-2 font-heading text-3xl font-extrabold text-[#172018]">{{ $verifiedCount }}</p>
                        <p class="mt-2 text-sm text-[#5b6658]">Siap rekap</p>
                    </div>
                    <div class="rounded-[1.5rem] bg-[#f1f4ee] p-5">
                        <p class="text-sm font-semibold text-[#5b6658]">Catatan menunggu</p>
                        <p class="mt-2 font-heading text-3xl font-extrabold text-[#172018]">{{ $pendingCount }}</p>
                        <p class="mt-2 text-sm text-[#5b6658]">Butuh tinjauan</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- CSV Card (Real Download) -->
                    <div class="surface-panel flex items-center gap-4 p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#196b2c]/10 text-[#196b2c]">
                            <span class="material-symbols-outlined icon-filled text-2xl">table_view</span>
                        </div>
                        <div class="flex-1 space-y-1">
                            <p class="font-heading text-base font-bold text-[#172018]">Ekspor CSV</p>
                            <p class="text-sm leading-6 text-[#5b6658]">Unduh seluruh data log panen dalam format CSV yang kompatibel dengan Excel.</p>
                        </div>
                        <a href="{{ route('admin.reports.export-csv') }}" class="btn-secondary px-6 py-2.5 rounded-xl font-bold">Unduh</a>
                    </div>

                    <!-- PDF Card (Simulated) -->
                    <div class="surface-panel flex items-center gap-4 p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#196b2c]/10 text-[#196b2c]">
                            <span class="material-symbols-outlined icon-filled text-2xl">picture_as_pdf</span>
                        </div>
                        <div class="flex-1 space-y-1">
                            <p class="font-heading text-base font-bold text-[#172018]">Ekspor PDF</p>
                            <p class="text-sm leading-6 text-[#5b6658]">Ringkasan formal untuk kebutuhan administrasi desa dan pelaporan rutin.</p>
                        </div>
                        <button onclick="alert('Unduhan PDF sedang disimulasikan')" class="btn-secondary px-6 py-2.5 rounded-xl font-bold">Unduh</button>
                    </div>

                    <!-- JSON Card (Simulated) -->
                    <div class="surface-panel flex items-center gap-4 p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#196b2c]/10 text-[#196b2c]">
                            <span class="material-symbols-outlined icon-filled text-2xl">data_object</span>
                        </div>
                        <div class="flex-1 space-y-1">
                            <p class="font-heading text-base font-bold text-[#172018]">Ekspor JSON</p>
                            <p class="text-sm leading-6 text-[#5b6658]">Payload pengembang untuk validasi struktur data dan integrasi API.</p>
                        </div>
                        <button onclick="alert('Unduhan JSON sedang disimulasikan')" class="btn-secondary px-6 py-2.5 rounded-xl font-bold">Unduh</button>
                    </div>
                </div>
            </div>

            <div class="surface-muted relative overflow-hidden p-0">
                <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&w=1200&q=80" alt="Lanskap pertanian desa" class="h-full min-h-[420px] w-full object-cover">
                <div class="absolute inset-x-8 bottom-8 rounded-[1.5rem] bg-white/90 p-6 backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#9c5421]">Catatan implementasi</p>
                    <h3 class="mt-2 font-heading text-2xl font-extrabold text-[#172018]">Template laporan sudah siap.</h3>
                    <p class="mt-3 text-sm leading-7 text-[#5b6658]">Tahap backend berikutnya cukup menghubungkan trigger ekspor ke endpoint Laravel dan generator file.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
