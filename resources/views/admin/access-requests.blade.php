@extends('layouts.app', ['title' => 'Persetujuan Admin', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        @if (session('status'))
            <div class="rounded-2xl border border-[#078d45]/20 bg-[#e6f7ed] px-4 py-3 text-sm font-semibold text-[#05753a]">{{ session('status') }}</div>
        @endif

        <div class="page-heading">
            <div>
                <p class="page-kicker">Kontrol akses</p>
                <h2 class="page-title">Persetujuan akses admin</h2>
                <p class="page-copy">Tinjau calon pengelola desa sebelum mereka dapat membuka dashboard admin.</p>
            </div>
        </div>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Desa / Gapoktan</th>
                        <th>Status</th>
                        <th>Tanggal daftar</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $candidate)
                        <tr>
                            <td>
                                <p class="font-heading text-base font-extrabold text-[#061826]">{{ $candidate->name }}</p>
                                <p class="text-xs text-[#718174]">{{ $candidate->email }}</p>
                            </td>
                            <td class="font-semibold">{{ $candidate->village }}</td>
                            <td>
                                <span class="status-pill {{ $candidate->isRejected() ? 'status-danger' : 'status-warning' }}">
                                    {{ $candidate->isRejected() ? 'Ditolak' : 'Menunggu Persetujuan' }}
                                </span>
                            </td>
                            <td class="font-semibold">{{ $candidate->created_at->translatedFormat('d M Y') }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.access-requests.approve', $candidate) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-compact">
                                            <span class="material-symbols-outlined text-lg">check_circle</span>
                                            Setujui
                                        </button>
                                    </form>
                                    @if (! $candidate->isRejected())
                                        <form method="POST" action="{{ route('admin.access-requests.reject', $candidate) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-compact text-red-600 hover:border-red-200 hover:text-red-700">
                                                <span class="material-symbols-outlined text-lg">block</span>
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-sm font-semibold text-[#718174]">Belum ada permintaan akses admin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
