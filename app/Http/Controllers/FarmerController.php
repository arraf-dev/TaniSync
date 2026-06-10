<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\DailyPrice;
use App\Models\Harvest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();
        $thisMonth = now()->month;
        $thisYear = now()->year;

        $totalHarvestKg = Harvest::query()
            ->where('user_id', $user->id)
            ->whereMonth('harvest_date', $thisMonth)
            ->whereYear('harvest_date', $thisYear)
            ->sum('quantity');

        $activeCommodities = Harvest::query()
            ->where('user_id', $user->id)
            ->distinct('commodity_id')
            ->count('commodity_id');

        $harvestCount = Harvest::query()->where('user_id', $user->id)->count();

        // Get latest price for the first commodity
        $latestPrice = DailyPrice::query()->latest('tanggal')->first();
        $firstCommodity = Commodity::query()->where('is_active', true)->first();
        $latestPriceValue = 0;
        $latestPriceName = '-';
        if ($latestPrice && $firstCommodity) {
            $latestPriceValue = $latestPrice->data_harga[$firstCommodity->id] ?? 0;
            $latestPriceName = $firstCommodity->nama_komoditas . ' per ' . $firstCommodity->satuan;
        }

        $metrics = [
            ['label' => 'Panen bulan ini', 'value' => number_format($totalHarvestKg / 1000, 2, ',', '.') . ' ton', 'detail' => 'Tercatat dari ' . Harvest::query()->where('user_id', $user->id)->whereMonth('harvest_date', $thisMonth)->count() . ' entri panen', 'icon' => 'eco', 'tone' => 'primary'],
            ['label' => 'Komoditas aktif', 'value' => $activeCommodities . ' jenis', 'detail' => 'Komoditas yang pernah dipanen', 'icon' => 'agriculture', 'tone' => 'accent'],
            ['label' => 'Harga terakhir', 'value' => 'Rp ' . number_format($latestPriceValue, 0, ',', '.'), 'detail' => $latestPriceName, 'icon' => 'trending_up', 'tone' => 'success'],
            ['label' => 'Riwayat tersimpan', 'value' => $harvestCount . ' catatan', 'detail' => 'Siap dipakai untuk laporan', 'icon' => 'history', 'tone' => 'warning'],
        ];

        // Trends (last 6 months)
        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $sum = Harvest::query()
                ->where('user_id', $user->id)
                ->whereYear('harvest_date', $month->year)
                ->whereMonth('harvest_date', $month->month)
                ->sum('quantity');
            $trends[] = ['label' => $month->translatedFormat('M'), 'value' => $sum > 0 ? min(round($sum / 10), 100) : 0];
        }

        // Latest prices for quick reference
        $priceList = collect();
        if ($latestPrice) {
            $commodities = Commodity::query()->where('is_active', true)->take(3)->get();
            foreach ($commodities as $commodity) {
                $price = $latestPrice->data_harga[$commodity->id] ?? null;
                if ($price !== null) {
                    $priceList->push([
                        'commodity_name' => $commodity->nama_komoditas,
                        'price' => $price,
                        'effective_date' => $latestPrice->tanggal->translatedFormat('d M Y'),
                        'source_note' => 'Input manual admin',
                    ]);
                }
            }
        }

        return view('petani.dashboard', [
            'pageTitle' => 'Ringkasan panen dan harga terbaru',
            'metrics' => $metrics,
            'trends' => $trends,
            'prices' => $priceList,
        ]);
    }

    public function prices(): View
    {
        $commodities = Commodity::query()->where('is_active', true)->orderBy('nama_komoditas')->get();
        $latestPrice = DailyPrice::query()->latest('tanggal')->first();

        $priceList = collect();
        if ($latestPrice) {
            foreach ($commodities as $commodity) {
                $price = $latestPrice->data_harga[$commodity->id] ?? null;
                if ($price !== null) {
                    $previousPrice = DailyPrice::query()
                        ->where('tanggal', '<', $latestPrice->tanggal)
                        ->latest('tanggal')
                        ->first();

                    $prevPrice = $previousPrice ? ($previousPrice->data_harga[$commodity->id] ?? $price) : $price;
                    $diff = $price - $prevPrice;
                    $trendPercent = $prevPrice > 0 ? round(abs($diff) / $prevPrice * 100, 1) : 0;

                    $priceList->push([
                        'commodity_name' => $commodity->nama_komoditas,
                        'category' => $commodity->category?->nama_kategori ?? '-',
                        'price' => $price,
                        'effective_date' => $latestPrice->tanggal,
                        'source_note' => 'Input manual admin',
                        'trend' => $diff > 0 ? 'up' : ($diff < 0 ? 'down' : 'steady'),
                        'trend_percent' => $trendPercent,
                    ]);
                }
            }
        }

        return view('petani.prices', [
            'pageTitle' => 'Pantau referensi harga terbaru',
            'prices' => $priceList,
        ]);
    }

    public function harvests(): View
    {
        $harvests = Harvest::query()
            ->where('user_id', auth()->id())
            ->with('commodity')
            ->latest('harvest_date')
            ->get();

        return view('petani.harvests', [
            'pageTitle' => 'Catatan panen pribadi',
            'harvests' => $harvests,
        ]);
    }

    public function createHarvest(): View
    {
        $commodities = Commodity::query()->where('is_active', true)->orderBy('nama_komoditas')->get();

        return view('petani.harvest-create', [
            'pageTitle' => 'Rekam panen musim ini',
            'commodities' => $commodities,
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
            'quality' => ['required', 'string', 'max:50'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'menunggu';

        Harvest::create($validated);

        $commodity = Commodity::find($validated['commodity_id']);

        return redirect()
            ->route('petani.harvests')
            ->with('status', "Data panen {$commodity->nama_komoditas} berhasil direkam dan menunggu verifikasi admin.");
    }
}
