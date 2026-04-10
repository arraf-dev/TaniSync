<?php

namespace App\Services;

class TaniSyncMockData
{
    public function landing(): array
    {
        return [
            'heroImage' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1400&q=80',
            'featureCards' => [
                ['title' => 'Pencatatan hasil panen', 'description' => 'Petani mencatat tanggal, komoditas, jumlah, satuan, lokasi, dan catatan dengan alur singkat.', 'icon' => 'rebase_edit'],
                ['title' => 'Harga komoditas harian', 'description' => 'Admin desa memperbarui harga manual sebagai fallback utama yang tetap terstruktur.', 'icon' => 'payments'],
                ['title' => 'Dashboard desa', 'description' => 'Ringkasan panen, tren bulanan, dan distribusi komoditas tersaji dalam panel yang mudah dibaca.', 'icon' => 'analytics'],
                ['title' => 'Laporan & ekspor', 'description' => 'Alur ekspor PDF dan Excel sudah dipersiapkan untuk integrasi backend lanjutan.', 'icon' => 'description'],
            ],
        ];
    }

    public function adminProfile(): array
    {
        return ['name' => 'Admin Gapoktan', 'email' => 'admin@tanisync.id', 'village' => 'Desa Sukamaju', 'avatar' => 'https://i.pravatar.cc/120?img=12'];
    }

    public function farmerProfile(): array
    {
        return ['name' => 'Bapak Rahmat', 'email' => 'rahmat@tanisync.id', 'village' => 'Desa Sukamaju', 'avatar' => 'https://i.pravatar.cc/120?img=15'];
    }

    public function commodities(): array
    {
        return [
            ['id' => 'c-1', 'name' => 'Padi Ciherang', 'category' => 'Pangan', 'unit' => 'kg', 'status' => 'aktif', 'description' => 'Komoditas utama desa untuk panen sawah irigasi.'],
            ['id' => 'c-2', 'name' => 'Jagung Manis', 'category' => 'Pangan', 'unit' => 'kg', 'status' => 'aktif', 'description' => 'Dipanen mingguan untuk suplai pasar kecamatan.'],
            ['id' => 'c-3', 'name' => 'Cabai Merah', 'category' => 'Hortikultura', 'unit' => 'kg', 'status' => 'aktif', 'description' => 'Harga manual diperbarui setiap pagi oleh admin.'],
            ['id' => 'c-4', 'name' => 'Kentang Lokal', 'category' => 'Umbi-umbian', 'unit' => 'kg', 'status' => 'aktif', 'description' => 'Data panen dipantau per blok lahan.'],
            ['id' => 'c-5', 'name' => 'Kedelai', 'category' => 'Palawija', 'unit' => 'kg', 'status' => 'nonaktif', 'description' => 'Sementara tidak masuk musim panen aktif.'],
        ];
    }

    public function prices(): array
    {
        return [
            ['id' => 'p-1', 'commodity_id' => 'c-1', 'commodity_name' => 'Padi Ciherang', 'category' => 'Pangan', 'price' => 12500, 'effective_date' => '2026-04-10', 'source_note' => 'Input manual admin pasar desa', 'trend' => 'up', 'trend_percent' => 2.4],
            ['id' => 'p-2', 'commodity_id' => 'c-2', 'commodity_name' => 'Jagung Manis', 'category' => 'Pangan', 'price' => 8100, 'effective_date' => '2026-04-10', 'source_note' => 'Perbandingan pengepul lokal', 'trend' => 'steady', 'trend_percent' => 0],
            ['id' => 'p-3', 'commodity_id' => 'c-3', 'commodity_name' => 'Cabai Merah', 'category' => 'Hortikultura', 'price' => 43200, 'effective_date' => '2026-04-10', 'source_note' => 'Input manual admin pasar induk', 'trend' => 'down', 'trend_percent' => 1.6],
            ['id' => 'p-4', 'commodity_id' => 'c-4', 'commodity_name' => 'Kentang Lokal', 'category' => 'Umbi-umbian', 'price' => 18000, 'effective_date' => '2026-04-09', 'source_note' => 'Rekap kios mitra', 'trend' => 'up', 'trend_percent' => 0.8],
        ];
    }

    public function harvests(): array
    {
        return [
            ['id' => 'h-1', 'user_name' => 'Bapak Rahmat', 'commodity_id' => 'c-1', 'commodity_name' => 'Padi Ciherang', 'harvest_date' => '2026-04-08', 'quantity' => 450, 'unit' => 'kg', 'note' => 'Cuaca cerah, gabah kering siap jemur.', 'location' => 'Blok Utara 02', 'quality' => 'Grade A', 'status' => 'terverifikasi'],
            ['id' => 'h-2', 'user_name' => 'Bapak Rahmat', 'commodity_id' => 'c-2', 'commodity_name' => 'Jagung Manis', 'harvest_date' => '2026-04-06', 'quantity' => 280, 'unit' => 'kg', 'note' => 'Bagian timur lahan, kadar air stabil.', 'location' => 'Lahan Timur 03', 'quality' => 'Grade B', 'status' => 'terverifikasi'],
            ['id' => 'h-3', 'user_name' => 'Ibu Sari', 'commodity_id' => 'c-3', 'commodity_name' => 'Cabai Merah', 'harvest_date' => '2026-04-05', 'quantity' => 96, 'unit' => 'kg', 'note' => 'Perlu sortir lanjutan.', 'location' => 'Kebun Lereng', 'quality' => 'Grade A', 'status' => 'menunggu'],
            ['id' => 'h-4', 'user_name' => 'Pak Dedi', 'commodity_id' => 'c-4', 'commodity_name' => 'Kentang Lokal', 'harvest_date' => '2026-04-03', 'quantity' => 320, 'unit' => 'kg', 'note' => 'Lahan basah, cek penyimpanan.', 'location' => 'Petak Barat 04', 'quality' => 'Grade A', 'status' => 'butuh-review'],
        ];
    }

    public function adminDashboard(): array
    {
        return [
            'metrics' => [
                ['label' => 'Petani aktif', 'value' => '128', 'detail' => 'Naik 12% dari bulan lalu', 'icon' => 'groups', 'tone' => 'primary'],
                ['label' => 'Total panen bulan ini', 'value' => '4.2 ton', 'detail' => 'Data dari 27 catatan masuk', 'icon' => 'analytics', 'tone' => 'success'],
                ['label' => 'Komoditas aktif', 'value' => '4', 'detail' => '1 komoditas nonaktif', 'icon' => 'compost', 'tone' => 'accent'],
                ['label' => 'Laporan siap ekspor', 'value' => '8', 'detail' => 'Periode mingguan & bulanan', 'icon' => 'description', 'tone' => 'warning'],
            ],
            'trends' => [
                ['label' => 'Jan', 'value' => 38], ['label' => 'Feb', 'value' => 44], ['label' => 'Mar', 'value' => 52],
                ['label' => 'Apr', 'value' => 61], ['label' => 'Mei', 'value' => 76], ['label' => 'Jun', 'value' => 69],
            ],
            'distribution' => [
                ['label' => 'Padi', 'value' => 52], ['label' => 'Jagung', 'value' => 23], ['label' => 'Cabai', 'value' => 14], ['label' => 'Kentang', 'value' => 11],
            ],
        ];
    }

    public function farmerDashboard(): array
    {
        return [
            'metrics' => [
                ['label' => 'Panen bulan ini', 'value' => '1.24 ton', 'detail' => 'Tercatat dari 4 entri panen', 'icon' => 'eco', 'tone' => 'primary'],
                ['label' => 'Komoditas aktif', 'value' => '3 jenis', 'detail' => 'Padi, jagung, cabai', 'icon' => 'agriculture', 'tone' => 'accent'],
                ['label' => 'Harga terakhir', 'value' => 'Rp 12.500', 'detail' => 'Padi Ciherang per kg', 'icon' => 'trending_up', 'tone' => 'success'],
                ['label' => 'Riwayat tersimpan', 'value' => '12 catatan', 'detail' => 'Siap dipakai untuk laporan', 'icon' => 'history', 'tone' => 'warning'],
            ],
            'trends' => [
                ['label' => 'Jan', 'value' => 34], ['label' => 'Feb', 'value' => 48], ['label' => 'Mar', 'value' => 57],
                ['label' => 'Apr', 'value' => 63], ['label' => 'Mei', 'value' => 71], ['label' => 'Jun', 'value' => 66],
            ],
        ];
    }
}
