@extends('layouts.app', ['title' => 'Harga Petani', 'pageTitle' => $pageTitle])

@section('content')
    <div class="space-y-7">
        <div class="page-heading">
            <div>
                <p class="page-kicker">Harga komoditas</p>
                <h2 class="page-title">Pantau referensi harga terbaru</h2>
                <p class="page-copy">Gunakan harga terbaru dari admin desa sebagai referensi sebelum menjual hasil panen.</p>
            </div>
        </div>

        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Komoditas</th>
                        <th>Harga</th>
                        <th>Diperbarui</th>
                        <th>Trend</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prices as $price)
                        <tr>
                            <td>
                                <p class="font-heading text-base font-extrabold text-[#061826]">{{ $price['commodity_name'] }}</p>
                                <p class="text-xs text-[#718174]">{{ $price['category'] }}</p>
                            </td>
                            <td class="font-heading text-lg font-extrabold text-[#061826]">Rp {{ number_format($price['price'], 0, ',', '.') }}</td>
                            <td class="font-semibold">{{ \Carbon\Carbon::parse($price['effective_date'])->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="status-pill {{ $price['trend'] === 'up' ? 'status-success' : ($price['trend'] === 'down' ? 'status-danger' : 'status-muted') }}">
                                    {{ $price['trend'] === 'steady' ? 'stabil' : $price['trend_percent'].'%' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
