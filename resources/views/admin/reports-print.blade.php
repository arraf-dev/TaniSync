<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Panen TaniSync</title>
    <style>
        body { color: #061826; font-family: Arial, sans-serif; margin: 32px; }
        .top { align-items: flex-start; border-bottom: 2px solid #078d45; display: flex; justify-content: space-between; padding-bottom: 18px; }
        .brand { color: #078d45; font-size: 24px; font-weight: 800; }
        h1 { font-size: 26px; margin: 18px 0 4px; }
        p { color: #4e6256; margin: 4px 0; }
        .grid { display: grid; gap: 12px; grid-template-columns: repeat(5, 1fr); margin: 24px 0; }
        .card { border: 1px solid #dfe8dc; border-radius: 14px; padding: 14px; }
        .label { color: #718174; font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .value { font-size: 20px; font-weight: 800; margin-top: 6px; }
        table { border-collapse: collapse; font-size: 12px; width: 100%; }
        th { background: #f1f7f0; color: #4e6256; font-size: 11px; letter-spacing: .08em; text-align: left; text-transform: uppercase; }
        th, td { border: 1px solid #dfe8dc; padding: 10px; vertical-align: top; }
        .right { text-align: right; }
        .actions { margin: 18px 0; text-align: right; }
        button { background: #078d45; border: 0; border-radius: 999px; color: white; cursor: pointer; font-weight: 700; padding: 10px 18px; }
        @media print {
            body { margin: 18mm; }
            .actions { display: none; }
            .card { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="top">
        <div>
            <div class="brand">TaniSync</div>
            <h1>Laporan Panen Organisasi</h1>
            <p>Dicetak pada {{ now()->translatedFormat('d M Y H:i') }}</p>
        </div>
        <div>
            <p><strong>Periode:</strong> {{ $filters['date_from'] ?? 'Awal data' }} - {{ $filters['date_to'] ?? 'Hari ini' }}</p>
            <p><strong>Status:</strong> {{ $filters['status'] ?? 'Semua status' }}</p>
        </div>
    </div>

    <div class="actions">
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="grid">
        <div class="card">
            <div class="label">Total panen</div>
            <div class="value">{{ number_format($summary['total_quantity'], 2, ',', '.') }} kg</div>
        </div>
        <div class="card">
            <div class="label">Jumlah catatan</div>
            <div class="value">{{ $summary['total_count'] }}</div>
        </div>
        <div class="card">
            <div class="label">Terverifikasi</div>
            <div class="value">{{ $summary['verified_count'] }}</div>
        </div>
        <div class="card">
            <div class="label">Menunggu</div>
            <div class="value">{{ $summary['pending_count'] }}</div>
        </div>
        <div class="card">
            <div class="label">Butuh review</div>
            <div class="value">{{ $summary['review_count'] }}</div>
        </div>
    </div>

    <table>
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
                    <td colspan="7">Belum ada catatan panen untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
