<?php

namespace App\Http\Controllers;

use App\Services\TaniSyncMockData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function __construct(private readonly TaniSyncMockData $mockData)
    {
    }

    public function dashboard(): View
    {
        $dashboard = $this->mockData->farmerDashboard();

        return view('petani.dashboard', [
            'pageTitle' => 'Ringkasan panen dan harga terbaru',
            'metrics' => $dashboard['metrics'],
            'trends' => $dashboard['trends'],
            'prices' => array_slice($this->mockData->prices(), 0, 3),
        ]);
    }

    public function prices(): View
    {
        return view('petani.prices', [
            'pageTitle' => 'Pantau referensi harga terbaru',
            'prices' => $this->mockData->prices(),
        ]);
    }

    public function harvests(): View
    {
        return view('petani.harvests', [
            'pageTitle' => 'Catatan panen pribadi',
            'harvests' => array_values(array_filter(
                $this->mockData->harvests(),
                fn (array $harvest): bool => $harvest['user_name'] === 'Bapak Rahmat'
            )),
        ]);
    }

    public function createHarvest(): View
    {
        return view('petani.harvest-create', [
            'pageTitle' => 'Rekam panen musim ini',
            'commodities' => array_values(array_filter(
                $this->mockData->commodities(),
                fn (array $commodity): bool => $commodity['status'] === 'aktif'
            )),
        ]);
    }

    public function storeHarvest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commodity' => ['required', 'string', 'max:120'],
            'harvest_date' => ['required', 'date'],
            'field_location' => ['required', 'string', 'max:120'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        return redirect()
            ->route('petani.harvests.create')
            ->with('status', "Data panen {$validated['commodity']} berhasil direkam dan siap ditinjau.");
    }
}
