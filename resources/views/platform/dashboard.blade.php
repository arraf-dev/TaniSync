@extends('layouts.app', ['title' => 'Dashboard Platform', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        @if (session('status'))
            <div class="rounded-2xl border border-[#078d45]/20 bg-[#e6f7ed] px-4 py-3 text-sm font-semibold text-[#05753a]">{{ session('status') }}</div>
        @endif

        <div class="page-heading">
            <div>
                <p class="page-kicker">Platform TaniSync</p>
                <h2 class="page-title">Kontrol organisasi dan pertumbuhan platform</h2>
                <p class="page-copy">Pantau organisasi aktif, tinjau pengajuan baru, dan jaga operasional lintas wilayah kerja tetap rapi.</p>
            </div>
            <a href="{{ route('platform.organizations') }}" class="btn-primary px-5 py-3">
                Kelola organisasi
                <span class="material-symbols-outlined text-xl">arrow_forward</span>
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <x-tanisync.stat-card :label="$metric['label']" :value="$metric['value']" :detail="$metric['detail']" :icon="$metric['icon']" :tone="$metric['tone']" />
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(360px,0.9fr)]">
            <section class="section-panel">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <p class="page-kicker">Butuh approval</p>
                        <h3 class="font-heading text-xl font-extrabold text-[#061826]">Pengajuan organisasi</h3>
                    </div>
                    <a href="{{ route('platform.organizations', ['status' => 'pending']) }}" class="text-sm font-bold text-[#078d45] hover:underline">Lihat semua</a>
                </div>

                <div class="space-y-3">
                    @forelse ($pendingOrganizations as $organization)
                        <div class="rounded-2xl border border-[#e3ede1] bg-[#f7faf7] p-4">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-heading text-base font-extrabold text-[#061826]">{{ $organization->name }}</p>
                                    <p class="mt-1 text-sm text-[#718174]">{{ $organization->region ?: 'Wilayah belum diisi' }} · {{ ucfirst($organization->type) }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('platform.organizations.approve', $organization) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn-compact" type="submit">
                                            <span class="material-symbols-outlined text-lg">check_circle</span>
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('platform.organizations.reject', $organization) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn-secondary px-4 py-2" type="submit">Reject</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl border border-[#e3ede1] bg-[#f7faf7] p-5 text-sm font-semibold text-[#718174]">Belum ada pengajuan organisasi baru.</p>
                    @endforelse
                </div>
            </section>

            <section class="section-panel">
                <div class="mb-5">
                    <p class="page-kicker">Aktivitas platform</p>
                    <h3 class="font-heading text-xl font-extrabold text-[#061826]">Log terbaru</h3>
                </div>
                <div class="space-y-4">
                    @forelse ($recentActivities as $activity)
                        <div class="border-b border-[#edf3eb] pb-4 last:border-0 last:pb-0">
                            <p class="text-sm font-bold text-[#061826]">{{ $activity->description }}</p>
                            <p class="mt-1 text-xs text-[#718174]">{{ $activity->created_at?->translatedFormat('d M Y H:i') }} · {{ $activity->organization?->name ?? 'Platform' }}</p>
                        </div>
                    @empty
                        <p class="text-sm font-semibold text-[#718174]">Belum ada aktivitas platform.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection
