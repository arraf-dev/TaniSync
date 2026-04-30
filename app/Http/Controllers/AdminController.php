<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Commodity;
use App\Models\DailyPrice;
use App\Models\HarvestLog;
use App\Models\Market;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $startOfMonth = now()->startOfMonth();
        $harvestsThisMonth = HarvestLog::whereDate('harvest_date', '>=', $startOfMonth);

        return view('admin.dashboard', [
            'pageTitle' => 'Ringkasan operasional desa',
            'metrics' => [
                ['label' => 'Petani aktif', 'value' => User::where('role', 'petani')->count(), 'detail' => 'Akun petani terdaftar', 'icon' => 'groups', 'tone' => 'primary'],
                ['label' => 'Total panen bulan ini', 'value' => number_format((float) $harvestsThisMonth->clone()->sum('quantity'), 2, ',', '.').' kg', 'detail' => $harvestsThisMonth->clone()->count().' catatan masuk', 'icon' => 'analytics', 'tone' => 'success'],
                ['label' => 'Komoditas aktif', 'value' => Commodity::where('is_active', true)->count(), 'detail' => Commodity::where('is_active', false)->count().' komoditas nonaktif', 'icon' => 'compost', 'tone' => 'accent'],
                ['label' => 'Panen menunggu', 'value' => HarvestLog::where('status', 'menunggu')->count(), 'detail' => 'Perlu verifikasi admin', 'icon' => 'description', 'tone' => 'warning'],
            ],
            'trends' => $this->monthlyHarvestTrend(),
            'distribution' => $this->harvestDistribution(),
        ]);
    }

    public function commodities(): View
    {
        return view('admin.commodities', [
            'pageTitle' => 'Manajemen komoditas',
            'categories' => Category::where('is_active', true)->orderBy('nama_kategori')->get(),
            'commodities' => Commodity::with('category')->latest()->get()->map(fn (Commodity $commodity): array => $this->formatCommodity($commodity)),
        ]);
    }

    public function storeCommodity(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_komoditas' => ['required', 'string', 'max:100'],
            'kategori_id' => ['required', 'exists:kategori_komoditas,id'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_acuan' => ['nullable', 'numeric', 'min:0'],
        ]);

        Commodity::create($validated + ['is_active' => true]);

        return redirect()->route('admin.commodities')->with('status', 'Komoditas baru berhasil ditambahkan.');
    }

    public function updateCommodity(Request $request, Commodity $commodity): RedirectResponse
    {
        $validated = $request->validate([
            'nama_komoditas' => ['required', 'string', 'max:100'],
            'kategori_id' => ['required', 'exists:kategori_komoditas,id'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_acuan' => ['nullable', 'numeric', 'min:0'],
        ]);

        $commodity->update($validated);

        return redirect()->route('admin.commodities')->with('status', 'Komoditas berhasil diperbarui.');
    }

    public function toggleCommodity(Commodity $commodity): RedirectResponse
    {
        $commodity->update(['is_active' => ! $commodity->is_active]);

        return redirect()->route('admin.commodities')->with('status', 'Status komoditas berhasil diubah.');
    }

    public function prices(): View
    {
        return view('admin.prices', [
            'pageTitle' => 'Update harga komoditas',
            'markets' => Market::where('is_active', true)->orderBy('nama_pasar')->get(),
            'commodities' => Commodity::where('is_active', true)->with('category')->orderBy('nama_komoditas')->get(),
            'prices' => $this->latestPrices(),
        ]);
    }

    public function storePrice(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_pasar' => ['required', 'exists:pasar,id'],
            'tanggal' => ['required', 'date'],
            'prices' => ['required', 'array'],
            'prices.*' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,submitted,verified'],
        ]);

        $priceData = collect($validated['prices'])
            ->filter(fn ($price): bool => $price !== null && $price !== '')
            ->map(fn ($price): float => (float) $price)
            ->all();

        DailyPrice::updateOrCreate(
            ['id_pasar' => $validated['id_pasar'], 'tanggal' => $validated['tanggal']],
            ['data_harga' => $priceData, 'status' => $validated['status'], 'created_by' => $request->user()->id]
        );

        return redirect()->route('admin.prices')->with('status', 'Harga harian berhasil disimpan.');
    }

    public function harvests(): View
    {
        return view('admin.harvests', [
            'pageTitle' => 'Pantau log panen yang masuk',
            'harvests' => HarvestLog::with(['user', 'commodity'])->latest('harvest_date')->get()->map(fn (HarvestLog $harvest): array => $this->formatHarvest($harvest)),
        ]);
    }

    public function updateHarvestStatus(Request $request, HarvestLog $harvestLog): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,terverifikasi,butuh-review'],
        ]);

        $harvestLog->update($validated);

        return redirect()->route('admin.harvests')->with('status', 'Status panen berhasil diperbarui.');
    }

    public function reports(Request $request): View
    {
        $query = HarvestLog::with(['user', 'commodity']);

        if ($request->filled('commodity_id')) {
            $query->where('commodity_id', $request->integer('commodity_id'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('harvest_date', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('harvest_date', '<=', $request->date('date_to'));
        }

        $filteredHarvests = $query->get();

        return view('admin.reports', [
            'pageTitle' => 'Analitik dan ekspor',
            'prices' => $this->latestPrices(),
            'commodities' => Commodity::where('is_active', true)->orderBy('nama_komoditas')->get(),
            'farmers' => User::where('role', 'petani')->orderBy('name')->get(),
            'report' => [
                'total_quantity' => $filteredHarvests->sum('quantity'),
                'verified_count' => $filteredHarvests->where('status', 'terverifikasi')->count(),
                'pending_count' => $filteredHarvests->where('status', 'menunggu')->count(),
                'harvests' => $filteredHarvests->sortByDesc('harvest_date')->take(8)->map(fn (HarvestLog $harvest): array => $this->formatHarvest($harvest)),
            ],
        ]);
    }

    private function formatCommodity(Commodity $commodity): array
    {
        return [
            'id' => $commodity->id,
            'name' => $commodity->nama_komoditas,
            'category' => $commodity->category?->nama_kategori ?? '-',
            'category_id' => $commodity->kategori_id,
            'unit' => $commodity->satuan,
            'harga_acuan' => $commodity->harga_acuan,
            'status' => $commodity->is_active ? 'aktif' : 'nonaktif',
            'description' => 'Harga acuan: '.($commodity->harga_acuan ? 'Rp '.number_format((float) $commodity->harga_acuan, 0, ',', '.') : 'belum diatur'),
        ];
    }

    private function formatHarvest(HarvestLog $harvest): array
    {
        return [
            'id' => $harvest->id,
            'user_name' => $harvest->user?->name ?? 'Petani',
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
                'commodity_id' => $commodity->id,
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

    private function monthlyHarvestTrend(): array
    {
        $rows = collect(range(5, 0))->map(function (int $monthsAgo): array {
            $month = now()->subMonths($monthsAgo);

            return [
                'label' => $month->translatedFormat('M'),
                'quantity' => (float) HarvestLog::whereYear('harvest_date', $month->year)
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

    private function harvestDistribution(): array
    {
        $rows = HarvestLog::query()
            ->select('commodity_id', DB::raw('SUM(quantity) as total_quantity'))
            ->with('commodity')
            ->groupBy('commodity_id')
            ->orderByDesc('total_quantity')
            ->take(4)
            ->get();

        $total = max((float) $rows->sum('total_quantity'), 1);

        return $rows->map(fn (HarvestLog $row): array => [
            'label' => $row->commodity?->nama_komoditas ?? 'Komoditas',
            'value' => (int) round(((float) $row->total_quantity / $total) * 100),
        ])->all();
    }
}
