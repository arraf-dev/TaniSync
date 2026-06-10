<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $landing = [
            'heroImage' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1400&q=80',
            'featureCards' => [
                ['title' => 'Pencatatan hasil panen', 'description' => 'Petani mencatat tanggal, komoditas, jumlah, satuan, lokasi, dan catatan dengan alur singkat.', 'icon' => 'rebase_edit'],
                ['title' => 'Harga komoditas harian', 'description' => 'Admin desa memperbarui harga manual sebagai fallback utama yang tetap terstruktur.', 'icon' => 'payments'],
                ['title' => 'Dashboard desa', 'description' => 'Ringkasan panen, tren bulanan, dan distribusi komoditas tersaji dalam panel yang mudah dibaca.', 'icon' => 'analytics'],
                ['title' => 'Laporan & ekspor', 'description' => 'Ekspor CSV untuk pengolahan data lebih detail di luar aplikasi.', 'icon' => 'description'],
            ],
        ];

        return view('landing.index', [
            'landing' => $landing,
        ]);
    }
}
