@extends('layouts.app', ['title' => 'Organisasi Platform', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        @if (session('status'))
            <div class="rounded-2xl border border-[#078d45]/20 bg-[#e6f7ed] px-4 py-3 text-sm font-semibold text-[#05753a]">{{ session('status') }}</div>
        @endif

        <div class="page-heading">
            <div>
                <p class="page-kicker">Manajemen platform</p>
                <h2 class="page-title">Organisasi TaniSync</h2>
                <p class="page-copy">Tinjau status organisasi, aktifkan workspace baru, dan pantau jumlah anggota serta panen.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('platform.organizations') }}" class="section-panel">
            <div class="grid gap-4 md:grid-cols-[1fr_220px_auto_auto] md:items-end">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Cari organisasi</label>
                    <input name="search" value="{{ request('search') }}" class="field-input py-3" placeholder="Nama, slug, atau wilayah">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-[0.14em] text-[#718174]">Status</label>
                    <select name="status" class="field-input py-3">
                        <option value="">Semua status</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>
                <a href="{{ route('platform.organizations') }}" class="btn-secondary py-3 text-center">Reset</a>
                <button class="btn-primary py-3" type="submit">Terapkan</button>
            </div>
        </form>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Organisasi</th>
                        <th>Status</th>
                        <th>Anggota</th>
                        <th>Panen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($organizations as $organization)
                        <tr>
                            <td>
                                <p class="font-heading text-base font-extrabold text-[#061826]">{{ $organization->name }}</p>
                                <p class="text-xs text-[#718174]">{{ $organization->slug }} · {{ $organization->region ?: 'Wilayah belum diisi' }}</p>
                            </td>
                            <td>
                                <span class="status-pill {{ $organization->status === 'active' ? 'status-success' : ($organization->status === 'rejected' ? 'status-danger' : 'status-warning') }}">
                                    {{ $organization->status }}
                                </span>
                            </td>
                            <td class="font-semibold">
                                {{ $organization->users_count }} user
                                <span class="block text-xs text-[#718174]">{{ $organization->admins_count }} admin · {{ $organization->farmers_count }} petani</span>
                            </td>
                            <td class="font-semibold">{{ $organization->harvest_logs_count }} catatan</td>
                            <td>
                                @if ($organization->status === 'pending')
                                    <div class="flex flex-wrap gap-2">
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
                                @else
                                    <span class="text-sm font-semibold text-[#718174]">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-sm font-semibold text-[#718174]">Tidak ada organisasi yang cocok dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $organizations->links() }}
            </div>
        </div>
    </div>
@endsection
