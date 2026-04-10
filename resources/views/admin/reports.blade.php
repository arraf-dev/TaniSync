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
                <div class="surface-panel p-5 shadow-none">
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Rentang tanggal</label>
                            <input type="text" class="field-input py-3" placeholder="01 Apr 2026 - 10 Apr 2026">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Komoditas</label>
                            <select class="field-input py-3">
                                <option>Semua komoditas</option>
                                @foreach ($prices as $price)
                                    <option>{{ $price['commodity_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Petani</label>
                            <select class="field-input py-3">
                                <option>Semua petani</option>
                                <option>Bapak Rahmat</option>
                                <option>Ibu Sari</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-3">
                            <button class="btn-secondary">Reset</button>
                            <button class="btn-primary">Terapkan</button>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ([['Total panen', '4.2 ton', 'Periode berjalan'], ['Catatan diverifikasi', '18', 'Siap rekap'], ['Catatan menunggu', '3', 'Butuh tindak lanjut']] as [$label, $value, $detail])
                        <div class="rounded-[1.5rem] bg-[#f1f4ee] p-5">
                            <p class="text-sm font-semibold text-[#5b6658]">{{ $label }}</p>
                            <p class="mt-2 font-heading text-3xl font-extrabold text-[#172018]">{{ $value }}</p>
                            <p class="mt-2 text-sm text-[#5b6658]">{{ $detail }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-4">
                    @foreach ([['picture_as_pdf', 'Ekspor PDF', 'Ringkasan formal untuk kebutuhan administrasi desa dan pelaporan rutin.'], ['table_view', 'Ekspor Excel', 'Format lanjutan untuk pengolahan data lebih detail di luar aplikasi.'], ['data_object', 'Ekspor JSON', 'Payload pengembang untuk validasi struktur data dan integrasi API.']] as [$icon, $label, $desc])
                        <div class="surface-panel flex items-center gap-4 p-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#196b2c]/10 text-[#196b2c]">
                                <span class="material-symbols-outlined icon-filled text-2xl">{{ $icon }}</span>
                            </div>
                            <div class="flex-1 space-y-1">
                                <p class="font-heading text-base font-bold text-[#172018]">{{ $label }}</p>
                                <p class="text-sm leading-6 text-[#5b6658]">{{ $desc }}</p>
                            </div>
                            <button class="btn-secondary px-4 py-2.5">Unduh</button>
                        </div>
                    @endforeach
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
