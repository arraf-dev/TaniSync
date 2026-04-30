<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\DailyPrice;
use App\Models\HarvestLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();
        $harvests = HarvestLog::where('user_id', $user->id);

        return view('petani.dashboard', [
            'pageTitle' => 'Ringkasan panen dan harga terbaru',
            'metrics' => [
                ['label' => 'Panen bulan ini', 'value' => number_format((float) $harvests->clone()->whereDate('harvest_date', '>=', now()->startOfMonth())->sum('quantity'), 2, ',', '.').' kg', 'detail' => 'Total catatan milik Anda', 'icon' => 'eco', 'tone' => 'primary'],
                ['label' => 'Komoditas aktif', 'value' => Commodity::where('is_active', true)->count().' jenis', 'detail' => 'Tersedia untuk dicatat', 'icon' => 'agriculture', 'tone' => 'accent'],
                ['label' => 'Harga terakhir', 'value' => 'Rp '.number_format($this->latestPrices()[0]['price'] ?? 0, 0, ',', '.'), 'detail' => $this->latestPrices()[0]['commodity_name'] ?? 'Belum ada harga', 'icon' => 'trending_up', 'tone' => 'success'],
                ['label' => 'Riwayat tersimpan', 'value' => $harvests->clone()->count().' catatan', 'detail' => 'Siap dipakai untuk laporan', 'icon' => 'history', 'tone' => 'warning'],
            ],
            'trends' => $this->monthlyHarvestTrend($user->id),
            'prices' => array_slice($this->latestPrices(), 0, 3),
        ]);
    }

    public function prices(): View
    {
        return view('petani.prices', [
            'pageTitle' => 'Pantau referensi harga terbaru',
            'prices' => $this->latestPrices(),
        ]);
    }

    public function harvests(): View
    {
        return view('petani.harvests', [
            'pageTitle' => 'Catatan panen pribadi',
            'harvests' => HarvestLog::with('commodity')
                ->where('user_id', auth()->id())
                ->latest('harvest_date')
                ->get()
                ->map(fn (HarvestLog $harvest): array => $this->formatHarvest($harvest)),
        ]);
    }

    public function createHarvest(): View
    {
        return view('petani.harvest-create', [
            'pageTitle' => 'Rekam panen musim ini',
            'commodities' => Commodity::where('is_active', true)->orderBy('nama_komoditas')->get(),
        ]);
    }

    public function storeHarvest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commodity_id' => ['required', 'exists:komoditas,id'],
            'harvest_date' => ['required', 'date'],
            'location' => ['required', 'string', 'max:120'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'string', 'max:20'],
            'quality' => ['required', 'string', 'max:80'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $harvest = HarvestLog::create($validated + [
            'user_id' => $request->user()->id,
            'status' => 'menunggu',
        ]);

        return redirect()
            ->route('petani.harvests')
            ->with('status', "Data panen {$harvest->commodity?->nama_komoditas} berhasil direkam dan siap ditinjau.");
    }

    private function formatHarvest(HarvestLog $harvest): array
    {
        return [
            'id' => $harvest->id,
            'commodity_name' => $harvest->commodity?->nama_komoditas ?? 'Komoditas',
            'harvest_date' => $harvest->harvest_date?->toDateString(),
            'quantity' => number_format((float) $harvest->quantity, 2, ',', '.'),
            'unit' => $harvest->unit,
            'note' => $harvest->note,
            'location' => $harvest->location,
            'quality' => $harvest->quality,
            'status' => $harvest->status,
        ];
    }

    private function latestPrices(): array
    {
        $commodities = Commodity::where('is_active', true)->with('category')->orderBy('nama_komoditas')->get();
        $dailyPrices = DailyPrice::with('market')->orderByDesc('tanggal')->orderByDesc('id')->get();

        return $commodities->map(function (Commodity $commodity) use ($dailyPrices): array {
            $latest = $dailyPrices->first(fn (DailyPrice $price): bool => array_key_exists((string) $commodity->id, $price->data_harga ?? []));
            $previous = $dailyPrices
                ->filter(fn (DailyPrice $price): bool => $latest && $price->id !== $latest->id && array_key_exists((string) $commodity->id, $price->data_harga ?? []))
                ->first();

            $currentPrice = $latest ? (float) $latest->data_harga[$commodity->id] : (float) ($commodity->harga_acuan ?? 0);
            $previousPrice = $previous ? (float) $previous->data_harga[$commodity->id] : $currentPrice;
            $delta = $previousPrice > 0 ? (($currentPrice - $previousPrice) / $previousPrice) * 100 : 0;

            return [
                'id' => 'price-'.$commodity->id,
                'commodity_name' => $commodity->nama_komoditas,
                'category' => $commodity->category?->nama_kategori ?? '-',
                'price' => $currentPrice,
                'effective_date' => $latest?->tanggal?->toDateString() ?? now()->toDateString(),
                'source_note' => $latest?->market?->nama_pasar ?? 'Harga acuan komoditas',
                'trend' => $delta > 0 ? 'up' : ($delta < 0 ? 'down' : 'steady'),
                'trend_percent' => round(abs($delta), 1),
            ];
        })->all();
    }

    private function monthlyHarvestTrend(int $userId): array
    {
        $rows = collect(range(5, 0))->map(function (int $monthsAgo) use ($userId): array {
            $month = now()->subMonths($monthsAgo);

            return [
                'label' => $month->translatedFormat('M'),
                'quantity' => (float) HarvestLog::where('user_id', $userId)
                    ->whereYear('harvest_date', $month->year)
                    ->whereMonth('harvest_date', $month->month)
                    ->sum('quantity'),
            ];
        });

        $max = max((float) $rows->max('quantity'), 1);

        return $rows->map(fn (array $row): array => [
            'label' => $row['label'],
            'value' => (int) round(($row['quantity'] / $max) * 100),
        ])->all();
    }
}
