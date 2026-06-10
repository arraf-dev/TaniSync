<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Commodity;
use App\Models\DailyPrice;
use App\Models\Harvest;
use App\Models\Market;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $petaniCount = User::query()->where('role', 'petani')->count();
        $totalHarvestKg = Harvest::query()->whereMonth('harvest_date', now()->month)->sum('quantity');
        $activeCommodities = Commodity::query()->where('is_active', true)->count();
        $inactiveCommodities = Commodity::query()->where('is_active', false)->count();
        $pendingHarvests = Harvest::query()->where('status', 'menunggu')->count();

        $metrics = [
            ['label' => 'Petani aktif', 'value' => (string) $petaniCount, 'detail' => 'Total petani terdaftar', 'icon' => 'groups', 'tone' => 'primary'],
            ['label' => 'Total panen bulan ini', 'value' => number_format($totalHarvestKg / 1000, 1, ',', '.') . ' ton', 'detail' => 'Dari ' . Harvest::query()->whereMonth('harvest_date', now()->month)->count() . ' catatan masuk', 'icon' => 'analytics', 'tone' => 'success'],
            ['label' => 'Komoditas aktif', 'value' => (string) $activeCommodities, 'detail' => $inactiveCommodities . ' komoditas nonaktif', 'icon' => 'compost', 'tone' => 'accent'],
            ['label' => 'Menunggu verifikasi', 'value' => (string) $pendingHarvests, 'detail' => 'Panen butuh ditinjau', 'icon' => 'pending_actions', 'tone' => 'warning'],
        ];

        // Monthly trends (last 6 months)
        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $sum = Harvest::query()
                ->whereYear('harvest_date', $month->year)
                ->whereMonth('harvest_date', $month->month)
                ->sum('quantity');
            $trends[] = ['label' => $month->translatedFormat('M'), 'value' => $sum > 0 ? min(round($sum / 10), 100) : rand(20, 40)];
        }

        // Distribution by commodity
        $distribution = Harvest::query()
            ->selectRaw('commodity_id, SUM(quantity) as total')
            ->groupBy('commodity_id')
            ->orderByDesc('total')
            ->with('commodity')
            ->get();

        $grandTotal = $distribution->sum('total');
        $distData = $distribution->map(fn ($item) => [
            'label' => $item->commodity?->nama_komoditas ?? 'Unknown',
            'value' => $grandTotal > 0 ? round($item->total / $grandTotal * 100) : 0,
        ])->toArray();

        return view('admin.dashboard', [
            'pageTitle' => 'Ringkasan operasional desa',
            'metrics' => $metrics,
            'trends' => $trends,
            'distribution' => $distData ?: [['label' => 'Belum ada data', 'value' => 0]],
        ]);
    }

    // ─── Commodities CRUD ────────────────────────────────────

    public function commodities(): View
    {
        $commodities = Commodity::query()->with('category')->orderBy('nama_komoditas')->get();

        return view('admin.commodities', [
            'pageTitle' => 'Manajemen komoditas',
            'commodities' => $commodities,
        ]);
    }

    public function createCommodity(): View
    {
        return view('admin.commodity-create', [
            'pageTitle' => 'Tambah komoditas baru',
            'categories' => Category::query()->where('is_active', true)->get(),
        ]);
    }

    public function storeCommodity(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_komoditas' => ['required', 'string', 'max:100'],
            'kategori_id' => ['required', 'exists:kategori_komoditas,id'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_acuan' => ['nullable', 'numeric', 'min:0'],
            'icon' => ['nullable', 'string', 'max:10'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        Commodity::create($validated);

        return redirect()->route('admin.commodities')->with('status', "Komoditas {$validated['nama_komoditas']} berhasil ditambahkan.");
    }

    public function editCommodity(Commodity $commodity): View
    {
        return view('admin.commodity-edit', [
            'pageTitle' => 'Edit komoditas',
            'commodity' => $commodity,
            'categories' => Category::query()->where('is_active', true)->get(),
        ]);
    }

    public function updateCommodity(Request $request, Commodity $commodity): RedirectResponse
    {
        $validated = $request->validate([
            'nama_komoditas' => ['required', 'string', 'max:100'],
            'kategori_id' => ['required', 'exists:kategori_komoditas,id'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_acuan' => ['nullable', 'numeric', 'min:0'],
            'icon' => ['nullable', 'string', 'max:10'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $commodity->update($validated);

        return redirect()->route('admin.commodities')->with('status', "Komoditas {$validated['nama_komoditas']} berhasil diperbarui.");
    }

    public function deleteCommodity(Commodity $commodity): RedirectResponse
    {
        $name = $commodity->nama_komoditas;
        $commodity->delete();

        return redirect()->route('admin.commodities')->with('status', "Komoditas {$name} berhasil dihapus.");
    }

    // ─── Prices ──────────────────────────────────────────────

    public function prices(Request $request): View
    {
        $commodities = Commodity::query()->where('is_active', true)->orderBy('nama_komoditas')->get();
        $latestPrice = DailyPrice::query()->latest('tanggal')->first();

        // Build price list from latest daily price entry
        $priceList = collect();
        if ($latestPrice) {
            $priceData = $latestPrice->data_harga;
            foreach ($commodities as $commodity) {
                $price = $priceData[$commodity->id] ?? null;
                if ($price !== null) {
                    // Get previous day price for trend
                    $previousPrice = DailyPrice::query()
                        ->where('tanggal', '<', $latestPrice->tanggal)
                        ->latest('tanggal')
                        ->first();

                    $prevPrice = $previousPrice ? ($previousPrice->data_harga[$commodity->id] ?? $price) : $price;
                    $diff = $price - $prevPrice;
                    $trendPercent = $prevPrice > 0 ? round(abs($diff) / $prevPrice * 100, 1) : 0;

                    $priceList->push([
                        'commodity' => $commodity,
                        'price' => $price,
                        'effective_date' => $latestPrice->tanggal,
                        'source_note' => 'Input manual admin',
                        'trend' => $diff > 0 ? 'up' : ($diff < 0 ? 'down' : 'steady'),
                        'trend_percent' => $trendPercent,
                    ]);
                }
            }
        }

        return view('admin.prices', [
            'pageTitle' => 'Update harga komoditas',
            'prices' => $priceList,
            'commodities' => $commodities,
        ]);
    }

    public function createPrice(): View
    {
        $commodities = Commodity::query()->where('is_active', true)->orderBy('nama_komoditas')->get();
        $markets = Market::query()->where('is_active', true)->get();

        return view('admin.price-create', [
            'pageTitle' => 'Input harga harian baru',
            'commodities' => $commodities,
            'markets' => $markets,
        ]);
    }

    public function storePrice(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_pasar' => ['required', 'exists:pasar,id'],
            'tanggal' => ['required', 'date'],
            'harga' => ['required', 'array'],
            'harga.*' => ['required', 'numeric', 'min:0'],
        ]);

        DailyPrice::query()->updateOrCreate(
            ['id_pasar' => $validated['id_pasar'], 'tanggal' => $validated['tanggal']],
            [
                'data_harga' => $validated['harga'],
                'status' => 'verified',
                'created_by' => auth()->id(),
            ]
        );

        return redirect()->route('admin.prices')->with('status', 'Harga harian berhasil disimpan.');
    }

    // ─── Harvests ────────────────────────────────────────────

    public function harvests(Request $request): View
    {
        $query = Harvest::query()->with(['user', 'commodity'])->latest('harvest_date');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('commodity_id') && $request->commodity_id !== 'all') {
            $query->where('commodity_id', $request->commodity_id);
        }

        return view('admin.harvests', [
            'pageTitle' => 'Pantau log panen yang masuk',
            'harvests' => $query->get(),
            'commodities' => Commodity::query()->where('is_active', true)->get(),
            'currentStatus' => $request->get('status', 'all'),
            'currentCommodity' => $request->get('commodity_id', 'all'),
        ]);
    }

    public function updateHarvestStatus(Request $request, Harvest $harvest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:terverifikasi,ditolak'],
        ]);

        $harvest->update([
            'status' => $validated['status'],
            'verified_by' => auth()->id(),
        ]);

        $label = $validated['status'] === 'terverifikasi' ? 'diverifikasi' : 'ditolak';

        return back()->with('status', "Panen {$harvest->commodity->nama_komoditas} oleh {$harvest->user->name} berhasil {$label}.");
    }

    // ─── Reports ─────────────────────────────────────────────

    public function reports(): View
    {
        $totalHarvest = Harvest::query()->sum('quantity');
        $verifiedCount = Harvest::query()->where('status', 'terverifikasi')->count();
        $pendingCount = Harvest::query()->where('status', 'menunggu')->count();

        return view('admin.reports', [
            'pageTitle' => 'Analitik dan ekspor',
            'totalHarvest' => number_format($totalHarvest / 1000, 1, ',', '.'),
            'verifiedCount' => $verifiedCount,
            'pendingCount' => $pendingCount,
            'commodities' => Commodity::query()->where('is_active', true)->get(),
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $harvests = Harvest::query()->with(['user', 'commodity'])->latest('harvest_date')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-panen-' . now()->format('Y-m-d') . '.csv"',
        ];

        return Response::stream(function () use ($harvests) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['Petani', 'Komoditas', 'Tanggal Panen', 'Jumlah', 'Satuan', 'Lokasi', 'Kualitas', 'Status', 'Catatan']);

            foreach ($harvests as $harvest) {
                fputcsv($handle, [
                    $harvest->user->name ?? '-',
                    $harvest->commodity->nama_komoditas ?? '-',
                    $harvest->harvest_date->format('Y-m-d'),
                    $harvest->quantity,
                    $harvest->unit,
                    $harvest->location,
                    $harvest->quality,
                    $harvest->status,
                    $harvest->note ?? '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
