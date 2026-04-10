<?php

namespace App\Http\Controllers;

use App\Services\TaniSyncMockData;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly TaniSyncMockData $mockData)
    {
    }

    public function dashboard(): View
    {
        $dashboard = $this->mockData->adminDashboard();

        return view('admin.dashboard', [
            'pageTitle' => 'Ringkasan operasional desa',
            'metrics' => $dashboard['metrics'],
            'trends' => $dashboard['trends'],
            'distribution' => $dashboard['distribution'],
        ]);
    }

    public function commodities(): View
    {
        return view('admin.commodities', [
            'pageTitle' => 'Manajemen komoditas',
            'commodities' => $this->mockData->commodities(),
        ]);
    }

    public function prices(): View
    {
        return view('admin.prices', [
            'pageTitle' => 'Update harga komoditas',
            'prices' => $this->mockData->prices(),
        ]);
    }

    public function harvests(): View
    {
        return view('admin.harvests', [
            'pageTitle' => 'Pantau log panen yang masuk',
            'harvests' => $this->mockData->harvests(),
        ]);
    }

    public function reports(): View
    {
        return view('admin.reports', [
            'pageTitle' => 'Analitik dan ekspor',
            'prices' => $this->mockData->prices(),
        ]);
    }
}
