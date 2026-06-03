<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Panen TaniSync</title>
    <style>
        @page { margin: 24px 28px; }
        body { color: #061826; font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; line-height: 1.45; }
        .header { border-bottom: 2px solid #078d45; margin-bottom: 18px; padding-bottom: 12px; }
        .brand { color: #078d45; font-size: 21px; font-weight: 800; }
        h1 { font-size: 22px; margin: 10px 0 4px; }
        p { color: #4e6256; margin: 2px 0; }
        .meta { color: #4e6256; font-size: 10px; text-align: right; }
        .summary { border-collapse: collapse; margin: 18px 0; width: 100%; }
        .summary td { border: 1px solid #dfe8dc; padding: 10px; width: 20%; }
        .label { color: #718174; font-size: 9px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
        .value { color: #061826; font-size: 15px; font-weight: 800; margin-top: 5px; }
        .data { border-collapse: collapse; width: 100%; }
        .data th { background: #f1f7f0; color: #4e6256; font-size: 9px; letter-spacing: .04em; text-align: left; text-transform: uppercase; }
        .data th, .data td { border: 1px solid #dfe8dc; padding: 7px; vertical-align: top; }
        .right { text-align: right; }
        .muted { color: #718174; }
        .footer { border-top: 1px solid #dfe8dc; color: #718174; font-size: 9px; margin-top: 16px; padding-top: 8px; }
    </style>
</head>
<body>
    <table class="header" width="100%">
        <tr>
            <td>
                <div class="brand">TaniSync</div>
                <h1>Laporan Panen Organisasi</h1>
                <p>Rekap data panen berdasarkan filter aktif.</p>
            </td>
            <td class="meta">
                <p><strong>Dibuat:</strong> {{ $generatedAt->translatedFormat('d M Y H:i') }}</p>
                <p><strong>Periode:</strong> {{ $filters['date_from'] ?? 'Awal data' }} - {{ $filters['date_to'] ?? 'Hari ini' }}</p>
                <p><strong>Status:</strong> {{ $filters['status'] ?? 'Semua status' }}</p>
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Total panen</div>
                <div class="value">{{ number_format($summary['total_quantity'], 2, ',', '.') }} kg</div>
            </td>
            <td>
                <div class="label">Jumlah catatan</div>
                <div class="value">{{ $summary['total_count'] }}</div>
            </td>
            <td>
                <div class="label">Terverifikasi</div>
                <div class="value">{{ $summary['verified_count'] }}</div>
            </td>
            <td>
                <div class="label">Menunggu</div>
                <div class="value">{{ $summary['pending_count'] }}</div>
            </td>
            <td>
                <div class="label">Butuh review</div>
                <div class="value">{{ $summary['review_count'] }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Petani</th>
                <th>Komoditas</th>
                <th>Lokasi</th>
                <th class="right">Jumlah</th>
                <th>Kualitas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($harvests as $harvest)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($harvest['harvest_date'])->translatedFormat('d M Y') }}</td>
                    <td>{{ $harvest['user_name'] }}</td>
                    <td>{{ $harvest['commodity_name'] }}</td>
                    <td>{{ $harvest['location'] }}</td>
                    <td class="right">{{ $harvest['quantity'] }} {{ $harvest['unit'] }}</td>
                    <td>{{ $harvest['quality'] }}</td>
                    <td>{{ $harvest['status'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="muted">Belum ada catatan panen untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat otomatis dari data TaniSync sesuai filter yang dipilih admin.
    </div>
</body>
</html>
