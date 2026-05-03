<?php

namespace App\Services;

use App\Models\Commodity;
use App\Models\DailyPrice;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PriceService
{
    public function latestPrices(?Request $request = null): LengthAwarePaginator
    {
        $request ??= request();
        $hasDailyPriceFilters = $this->hasDailyPriceFilters($request);

        $commodities = Commodity::where('is_active', true)
            ->with('category')
            ->when($request->filled('commodity_id'), fn ($query) => $query->whereKey($request->integer('commodity_id')))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search): void {
                    $query->where('nama_komoditas', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('nama_kategori', 'like', "%{$search}%"));
                });
            })
            ->orderBy('nama_komoditas')
            ->get();

        $dailyPrices = DailyPrice::with('market')
            ->when($request->filled('market_id'), fn ($query) => $query->where('id_pasar', $request->integer('market_id')))
            ->when($request->filled('status'), function ($query) use ($request): void {
                $status = $request->string('status')->toString();

                if (in_array($status, ['draft', 'submitted', 'verified'], true)) {
                    $query->where('status', $status);
                }
            })
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('tanggal', '>=', $request->date('date_from')))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('tanggal', '<=', $request->date('date_to')))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        $rows = $commodities
            ->map(function (Commodity $commodity) use ($dailyPrices, $hasDailyPriceFilters): ?array {
                $latest = $this->firstPriceForCommodity($dailyPrices, $commodity);

                if ($hasDailyPriceFilters && ! $latest) {
                    return null;
                }

                $previous = $dailyPrices
                    ->filter(fn (DailyPrice $price): bool => $latest && $price->id !== $latest->id && $this->hasCommodityPrice($price, $commodity))
                    ->first();

                $currentPrice = $latest
                    ? $this->commodityPrice($latest, $commodity)
                    : (float) ($commodity->harga_acuan ?? 0);
                $previousPrice = $previous
                    ? $this->commodityPrice($previous, $commodity)
                    : $currentPrice;
                $delta = $previousPrice > 0 ? (($currentPrice - $previousPrice) / $previousPrice) * 100 : 0;

                return [
                    'id' => 'price-'.$commodity->id,
                    'commodity_id' => $commodity->id,
                    'commodity_name' => $commodity->nama_komoditas,
                    'category' => $commodity->category?->nama_kategori ?? '-',
                    'price' => $currentPrice,
                    'effective_date' => $latest?->tanggal?->toDateString() ?? now()->toDateString(),
                    'source_note' => $latest?->market?->nama_pasar ?? 'Harga acuan komoditas',
                    'status' => $latest?->status ?? 'acuan',
                    'trend' => $delta > 0 ? 'up' : ($delta < 0 ? 'down' : 'steady'),
                    'trend_percent' => round(abs($delta), 1),
                ];
            })
            ->filter()
            ->values();

        return $this->paginateCollection($rows, $request);
    }

    private function hasDailyPriceFilters(Request $request): bool
    {
        $status = $request->string('status')->toString();

        return $request->filled('market_id')
            || $request->filled('date_from')
            || $request->filled('date_to')
            || in_array($status, ['draft', 'submitted', 'verified'], true);
    }

    /**
     * @param  Collection<int, DailyPrice>  $dailyPrices
     */
    private function firstPriceForCommodity(Collection $dailyPrices, Commodity $commodity): ?DailyPrice
    {
        return $dailyPrices->first(fn (DailyPrice $price): bool => $this->hasCommodityPrice($price, $commodity));
    }

    private function hasCommodityPrice(DailyPrice $price, Commodity $commodity): bool
    {
        return array_key_exists((string) $commodity->id, $price->data_harga ?? [])
            || array_key_exists($commodity->id, $price->data_harga ?? []);
    }

    private function commodityPrice(DailyPrice $price, Commodity $commodity): float
    {
        return (float) (($price->data_harga ?? [])[(string) $commodity->id] ?? ($price->data_harga ?? [])[$commodity->id]);
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $items
     */
    private function paginateCollection(Collection $items, Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();

        return (new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url()]
        ))->appends($request->query());
    }
}
