@extends('layouts.app', ['pageTitle' => $pageTitle])

@section('content')
    @if (session('status'))
        <div class="mb-6 rounded-2xl border border-[#196b2c]/20 bg-[#dff2df] px-5 py-4 text-sm font-semibold text-[#114b1e]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined icon-filled text-lg">check_circle</span>
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">error</span>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-[#5b6658]">Total {{ $users->count() }} pengguna terdaftar</p>
        </div>
        <a href="{{ route('superadmin.users.create') }}" class="btn-primary">
            <span class="material-symbols-outlined icon-filled text-lg">person_add</span>
            Tambah pengguna
        </a>
    </div>

    <div class="surface-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#cad4c4]/70 bg-[#f1f4ee]">
                        <th class="px-5 py-4 font-heading text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Nama</th>
                        <th class="px-5 py-4 font-heading text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Email</th>
                        <th class="px-5 py-4 font-heading text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Desa</th>
                        <th class="px-5 py-4 font-heading text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Peran</th>
                        <th class="px-5 py-4 font-heading text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Dibuat</th>
                        <th class="px-5 py-4 font-heading text-xs font-bold uppercase tracking-[0.16em] text-[#5b6658]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $userRow)
                        <tr class="border-b border-[#cad4c4]/40 transition hover:bg-[#f1f4ee]/60">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#dff2df] text-[#196b2c]">
                                        <span class="material-symbols-outlined text-lg">person</span>
                                    </div>
                                    <span class="font-heading font-bold text-[#172018]">{{ $userRow->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-[#5b6658]">{{ $userRow->email }}</td>
                            <td class="px-5 py-4 text-[#5b6658]">{{ $userRow->village }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $roleBadge = match ($userRow->role) {
                                        'superadmin' => 'bg-purple-100 text-purple-800',
                                        'admin' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-[#dff2df] text-[#196b2c]',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.12em] {{ $roleBadge }}">
                                    {{ $userRow->role }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-[#5b6658]">{{ $userRow->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                @if (!$userRow->isSuperAdmin() && $userRow->id !== auth()->id())
                                    <form method="POST" action="{{ route('superadmin.users.delete', $userRow) }}" onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $userRow->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-[#7b8578]">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-[#5b6658]">
                                <span class="material-symbols-outlined mb-2 text-4xl text-[#cad4c4]">group_off</span>
                                <p>Belum ada pengguna terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
