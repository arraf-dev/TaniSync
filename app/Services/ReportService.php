<?php

namespace App\Services;

use App\Models\HarvestLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportService
{
    /**
     * @return array<string, string|null>
     */
    public function filters(Request $request): array
    {
        $organizationId = $request->user()?->organization_id;
        $rules = [
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'commodity_id' => ['nullable', 'integer', Rule::exists('komoditas', 'id')->where('organization_id', $organizationId)],
            'user_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'petani')->where('organization_id', $organizationId)],
            'status' => ['nullable', Rule::in(['menunggu', 'terverifikasi', 'butuh-review'])],
            'search' => ['nullable', 'string', 'max:120'],
        ];

        if ($request->filled('date_from')) {
            $rules['date_to'][] = 'after_or_equal:date_from';
        }

        $validated = Validator::make($request->query(), $rules)->validate();
        $status = (string) ($validated['status'] ?? '');

        return [
            'date_from' => $validated['date_from'] ?? null,
            'date_to' => $validated['date_to'] ?? null,
            'commodity_id' => isset($validated['commodity_id']) ? (string) $validated['commodity_id'] : null,
            'user_id' => isset($validated['user_id']) ? (string) $validated['user_id'] : null,
            'status' => in_array($status, ['menunggu', 'terverifikasi', 'butuh-review'], true) ? $status : null,
            'search' => isset($validated['search']) ? trim((string) $validated['search']) : null,
        ];
    }

    /**
     * @param  array<string, string|null>  $filters
     */
    public function query(array $filters): Builder
    {
        $organizationId = auth()->user()?->organization_id;

        return HarvestLog::with(['user', 'commodity'])
            ->forOrganization($organizationId)
            ->when($filters['commodity_id'], fn (Builder $query, string $id) => $query->where('commodity_id', $id))
            ->when($filters['user_id'], fn (Builder $query, string $id) => $query->where('user_id', $id))
            ->when($filters['status'], fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['date_from'], fn (Builder $query, string $date) => $query->whereDate('harvest_date', '>=', $date))
            ->when($filters['date_to'], fn (Builder $query, string $date) => $query->whereDate('harvest_date', '<=', $date))
            ->when($filters['search'], function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('location', 'like', "%{$search}%")
                        ->orWhere('note', 'like', "%{$search}%")
                        ->orWhereHas('user', fn (Builder $userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('commodity', fn (Builder $commodityQuery) => $commodityQuery->where('nama_komoditas', 'like', "%{$search}%"));
                });
            });
    }

    /**
     * @param  array<string, string|null>  $filters
     * @return array<string, mixed>
     */
    public function pageData(array $filters): array
    {
        $query = $this->query($filters);
        $allRows = (clone $query)->get();

        return [
            'filters' => $filters,
            'summary' => $this->summary($allRows),
            'harvests' => $query
                ->latest('harvest_date')
                ->paginate(10)
                ->withQueryString()
                ->through(fn (HarvestLog $harvest): array => $this->formatHarvest($harvest)),
        ];
    }

    /**
     * @param  array<string, string|null>  $filters
     * @return Collection<int, HarvestLog>
     */
    public function exportRows(array $filters): Collection
    {
        return $this->query($filters)
            ->latest('harvest_date')
            ->get();
    }

    /**
     * @param  Collection<int, HarvestLog>  $rows
     * @return array<string, mixed>
     */
    public function summary(Collection $rows): array
    {
        $dominant = $rows
            ->groupBy(fn (HarvestLog $harvest): string => $harvest->commodity?->nama_komoditas ?? 'Komoditas')
            ->map(fn (Collection $items): float => (float) $items->sum('quantity'))
            ->sortDesc();

        return [
            'total_quantity' => (float) $rows->sum('quantity'),
            'total_count' => $rows->count(),
            'verified_count' => $rows->where('status', 'terverifikasi')->count(),
            'pending_count' => $rows->where('status', 'menunggu')->count(),
            'review_count' => $rows->where('status', 'butuh-review')->count(),
            'dominant_commodity' => $dominant->keys()->first() ?? '-',
            'dominant_quantity' => (float) ($dominant->first() ?? 0),
        ];
    }

    public function formatHarvest(HarvestLog $harvest): array
    {
        return [
            'id' => $harvest->id,
            'user_name' => $harvest->user?->name ?? 'Petani',
            'commodity_name' => $harvest->commodity?->nama_komoditas ?? 'Komoditas',
            'harvest_date' => $harvest->harvest_date?->toDateString(),
            'quantity' => number_format((float) $harvest->quantity, 2, ',', '.'),
            'raw_quantity' => (float) $harvest->quantity,
            'unit' => $harvest->unit,
            'note' => $harvest->note,
            'location' => $harvest->location,
            'quality' => $harvest->quality,
            'status' => $harvest->status,
        ];
    }
}
