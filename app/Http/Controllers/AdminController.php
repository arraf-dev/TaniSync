<?php

namespace App\Http\Controllers;

use App\Exports\HarvestReportExport;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Models\Commodity;
use App\Models\DailyPrice;
use App\Models\DailyPriceItem;
use App\Models\HarvestLog;
use App\Models\Market;
use App\Models\User;
use App\Services\PriceService;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $organizationId = auth()->user()->organization_id;
        $startOfMonth = now()->startOfMonth();
        $harvestsThisMonth = HarvestLog::forOrganization($organizationId)->whereDate('harvest_date', '>=', $startOfMonth);

        return view('admin.dashboard', [
            'pageTitle' => 'Ringkasan operasional organisasi',
            'metrics' => [
                ['label' => 'Petani aktif', 'value' => User::where('organization_id', $organizationId)->where('role', 'petani')->count(), 'detail' => 'Akun petani terdaftar', 'icon' => 'groups', 'tone' => 'primary'],
                ['label' => 'Total panen bulan ini', 'value' => number_format((float) $harvestsThisMonth->clone()->sum('quantity'), 2, ',', '.').' kg', 'detail' => $harvestsThisMonth->clone()->count().' catatan masuk', 'icon' => 'analytics', 'tone' => 'success'],
                ['label' => 'Admin organisasi', 'value' => User::where('organization_id', $organizationId)->where('role', 'admin')->where('account_status', 'active')->count(), 'detail' => 'Pengelola aktif', 'icon' => 'admin_panel_settings', 'tone' => 'accent'],
                ['label' => 'Panen menunggu', 'value' => HarvestLog::forOrganization($organizationId)->where('status', 'menunggu')->count(), 'detail' => 'Perlu verifikasi admin', 'icon' => 'description', 'tone' => 'warning'],
            ],
            'trends' => $this->monthlyHarvestTrend($organizationId),
            'distribution' => $this->harvestDistribution($organizationId),
            'recentActivities' => ActivityLog::with('user')->forOrganization($organizationId)->latest()->take(5)->get(),
        ]);
    }

    public function accessRequests(): View
    {
        $organizationId = auth()->user()->organization_id;

        return view('admin.access-requests', [
            'pageTitle' => 'Persetujuan akses admin',
            'requests' => User::with('organization')
                ->where('role', 'admin')
                ->where('organization_id', $organizationId)
                ->whereIn('account_status', ['pending', 'rejected'])
                ->latest()
                ->get(),
        ]);
    }

    public function approveAccessRequest(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdminRequest($user);
        $this->ensureUserBelongsToOrganization($request, $user);

        $user->update([
            'account_status' => 'active',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
            'rejected_at' => null,
        ]);

        ActivityLog::record(
            'admin_access_approved',
            "{$request->user()->name} menyetujui akses admin untuk {$user->name}.",
            $user,
            ['approved_user_id' => $user->id],
            $request->user(),
            $request
        );

        return redirect()->route('admin.access-requests')->with('status', 'Akses admin berhasil disetujui.');
    }

    public function rejectAccessRequest(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdminRequest($user);
        $this->ensureUserBelongsToOrganization($request, $user);

        $user->update([
            'account_status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
            'rejected_at' => now(),
        ]);

        ActivityLog::record(
            'admin_access_rejected',
            "{$request->user()->name} menolak akses admin untuk {$user->name}.",
            $user,
            ['rejected_user_id' => $user->id],
            $request->user(),
            $request
        );

        return redirect()->route('admin.access-requests')->with('status', 'Permintaan akses admin ditolak.');
    }

    public function activityLogs(): View
    {
        $organizationId = auth()->user()->organization_id;

        return view('admin.activity-logs', [
            'pageTitle' => 'Aktivitas sistem',
            'activities' => ActivityLog::with('user')->forOrganization($organizationId)->latest()->take(80)->get(),
        ]);
    }

    public function commodities(): View
    {
        $organizationId = auth()->user()->organization_id;
        $commoditiesQuery = Commodity::with('category')
            ->forOrganization($organizationId)
            ->when(request('search'), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama_komoditas', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('nama_kategori', 'like', "%{$search}%"));
                });
            })
            ->when(request('status'), function ($query, string $status): void {
                if (in_array($status, ['aktif', 'nonaktif'], true)) {
                    $query->where('is_active', $status === 'aktif');
                }
            })
            ->latest();

        return view('admin.commodities', [
            'pageTitle' => 'Manajemen komoditas',
            'categories' => Category::where('is_active', true)->orderBy('nama_kategori')->get(),
            'commodities' => $commoditiesQuery
                ->paginate(10)
                ->withQueryString()
                ->through(fn (Commodity $commodity): array => $this->formatCommodity($commodity)),
        ]);
    }

    public function storeCommodity(Request $request): RedirectResponse
    {
        $organizationId = $request->user()->organization_id;
        $validated = $request->validate([
            'nama_komoditas' => ['required', 'string', 'max:100', Rule::unique('komoditas', 'nama_komoditas')->where('organization_id', $organizationId)],
            'kategori_id' => ['required', 'exists:kategori_komoditas,id'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_acuan' => ['nullable', 'numeric', 'min:0'],
        ]);

        $commodity = Commodity::create($validated + ['organization_id' => $organizationId, 'is_active' => true]);

        ActivityLog::record('commodity_created', "Komoditas {$commodity->nama_komoditas} ditambahkan.", $commodity);

        return redirect()->route('admin.commodities')->with('status', 'Komoditas baru berhasil ditambahkan.');
    }

    public function updateCommodity(Request $request, Commodity $commodity): RedirectResponse
    {
        $this->ensureModelBelongsToOrganization($request, $commodity);
        $organizationId = $request->user()->organization_id;
        $validated = $request->validate([
            'nama_komoditas' => ['required', 'string', 'max:100', Rule::unique('komoditas', 'nama_komoditas')->where('organization_id', $organizationId)->ignore($commodity->id)],
            'kategori_id' => ['required', 'exists:kategori_komoditas,id'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_acuan' => ['nullable', 'numeric', 'min:0'],
        ]);

        $commodity->update($validated);

        ActivityLog::record('commodity_updated', "Komoditas {$commodity->nama_komoditas} diperbarui.", $commodity);

        return redirect()->route('admin.commodities')->with('status', 'Komoditas berhasil diperbarui.');
    }

    public function toggleCommodity(Commodity $commodity): RedirectResponse
    {
        $this->ensureModelBelongsToOrganization(request(), $commodity);

        $commodity->update(['is_active' => ! $commodity->is_active]);

        ActivityLog::record(
            'commodity_status_toggled',
            "Status komoditas {$commodity->nama_komoditas} diubah menjadi {$commodity->statusLabel()}.",
            $commodity
        );

        return redirect()->route('admin.commodities')->with('status', 'Status komoditas berhasil diubah.');
    }

    public function prices(PriceService $prices): View
    {
        $organizationId = auth()->user()->organization_id;

        return view('admin.prices', [
            'pageTitle' => 'Update harga komoditas',
            'markets' => Market::forOrganization($organizationId)->where('is_active', true)->orderBy('nama_pasar')->get(),
            'commodities' => Commodity::forOrganization($organizationId)->where('is_active', true)->with('category')->orderBy('nama_komoditas')->get(),
            'prices' => $prices->latestPrices(request()),
        ]);
    }

    public function storePrice(Request $request): RedirectResponse
    {
        $organizationId = $request->user()->organization_id;
        $validated = $request->validate([
            'id_pasar' => ['required', Rule::exists('pasar', 'id')->where('organization_id', $organizationId)->where('is_active', true)],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'prices' => ['required', 'array'],
            'prices.*' => ['nullable', 'numeric', 'min:1', 'max:100000000'],
            'status' => ['required', 'in:draft,submitted,verified'],
        ]);

        $activeCommodityIds = Commodity::forOrganization($organizationId)->where('is_active', true)->pluck('id')->map(fn (int $id): string => (string) $id);
        $priceData = collect($validated['prices'])
            ->only($activeCommodityIds)
            ->filter(fn ($price): bool => $price !== null && $price !== '')
            ->map(fn ($price): float => (float) $price)
            ->all();

        if ($priceData === []) {
            throw ValidationException::withMessages([
                'prices' => 'Isi minimal satu harga komoditas aktif.',
            ]);
        }

        $dailyPrice = DailyPrice::updateOrCreate(
            ['organization_id' => $organizationId, 'id_pasar' => $validated['id_pasar'], 'tanggal' => $validated['tanggal']],
            ['data_harga' => $priceData, 'status' => $validated['status'], 'created_by' => $request->user()->id]
        );

        $dailyPrice->items()->whereNotIn('commodity_id', array_keys($priceData))->delete();

        foreach ($priceData as $commodityId => $price) {
            DailyPriceItem::updateOrCreate(
                ['daily_price_id' => $dailyPrice->id, 'commodity_id' => (int) $commodityId],
                ['price' => $price]
            );
        }

        ActivityLog::record(
            'daily_price_saved',
            'Harga harian komoditas berhasil disimpan.',
            $dailyPrice,
            ['tanggal' => $validated['tanggal'], 'jumlah_komoditas' => count($priceData)]
        );

        return redirect()->route('admin.prices')->with('status', 'Harga harian berhasil disimpan.');
    }

    public function harvests(): View
    {
        $organizationId = auth()->user()->organization_id;
        $harvestsQuery = HarvestLog::with(['user', 'commodity'])
            ->forOrganization($organizationId)
            ->when(request('commodity_id'), fn ($query, string $id) => $query->where('commodity_id', $id))
            ->when(request('user_id'), fn ($query, string $id) => $query->where('user_id', $id))
            ->when(request('status'), function ($query, string $status): void {
                if (in_array($status, ['menunggu', 'terverifikasi', 'butuh-review'], true)) {
                    $query->where('status', $status);
                }
            })
            ->when(request('date_from'), fn ($query, string $date) => $query->whereDate('harvest_date', '>=', $date))
            ->when(request('date_to'), fn ($query, string $date) => $query->whereDate('harvest_date', '<=', $date))
            ->when(request('search'), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('location', 'like', "%{$search}%")
                        ->orWhere('note', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('commodity', fn ($commodityQuery) => $commodityQuery->where('nama_komoditas', 'like', "%{$search}%"));
                });
            })
            ->latest('harvest_date');

        return view('admin.harvests', [
            'pageTitle' => 'Pantau log panen yang masuk',
            'commodities' => Commodity::forOrganization($organizationId)->where('is_active', true)->orderBy('nama_komoditas')->get(),
            'farmers' => User::where('organization_id', $organizationId)->where('role', 'petani')->orderBy('name')->get(),
            'harvests' => $harvestsQuery
                ->paginate(10)
                ->withQueryString()
                ->through(fn (HarvestLog $harvest): array => $this->formatHarvest($harvest)),
        ]);
    }

    public function updateHarvestStatus(Request $request, HarvestLog $harvestLog): RedirectResponse
    {
        $this->ensureModelBelongsToOrganization($request, $harvestLog);

        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,terverifikasi,butuh-review'],
        ]);

        $harvestLog->update($validated);

        ActivityLog::record(
            'harvest_status_updated',
            "Status panen {$harvestLog->commodity?->nama_komoditas} diubah menjadi {$validated['status']}.",
            $harvestLog,
            ['status' => $validated['status']]
        );

        return redirect()->route('admin.harvests')->with('status', 'Status panen berhasil diperbarui.');
    }

    public function reports(Request $request, ReportService $reports, PriceService $prices): View
    {
        $report = $reports->pageData($reports->filters($request));
        $organizationId = $request->user()->organization_id;

        return view('admin.reports', [
            'pageTitle' => 'Analitik dan ekspor',
            'prices' => $prices->latestPrices($request),
            'commodities' => Commodity::forOrganization($organizationId)->where('is_active', true)->orderBy('nama_komoditas')->get(),
            'farmers' => User::where('organization_id', $organizationId)->where('role', 'petani')->orderBy('name')->get(),
            'report' => $report,
        ]);
    }

    public function reportPrint(Request $request, ReportService $reports): View
    {
        $filters = $reports->filters($request);
        $rows = $reports->exportRows($filters);
        $this->recordReportActivity($request, 'report_print_opened', 'print', $filters, $rows->count());

        return view('admin.reports-print', [
            'filters' => $filters,
            'summary' => $reports->summary($rows),
            'harvests' => $rows->map(fn (HarvestLog $harvest): array => $reports->formatHarvest($harvest)),
        ]);
    }

    public function reportPdf(Request $request, ReportService $reports)
    {
        $filters = $reports->filters($request);
        $rows = $reports->exportRows($filters);
        $filename = 'laporan-panen-'.now()->format('Ymd-His').'.pdf';
        $this->recordReportActivity($request, 'report_pdf_exported', 'pdf', $filters, $rows->count());

        return Pdf::loadView('admin.reports-pdf', [
            'filters' => $filters,
            'summary' => $reports->summary($rows),
            'harvests' => $rows->map(fn (HarvestLog $harvest): array => $reports->formatHarvest($harvest)),
            'generatedAt' => now(),
        ])
            ->setPaper('a4', 'portrait')
            ->download($filename);
    }

    public function reportXlsx(Request $request, ReportService $reports): BinaryFileResponse
    {
        $filters = $reports->filters($request);
        $rows = $reports->exportRows($filters);
        $filename = 'laporan-panen-'.now()->format('Ymd-His').'.xlsx';
        $this->recordReportActivity($request, 'report_xlsx_exported', 'xlsx', $filters, $rows->count());

        return Excel::download(new HarvestReportExport($rows), $filename);
    }

    public function reportCsv(Request $request, ReportService $reports): StreamedResponse
    {
        $filters = $reports->filters($request);
        $rows = $reports->exportRows($filters);
        $filename = 'laporan-panen-'.now()->format('Ymd-His').'.csv';
        $this->recordReportActivity($request, 'report_csv_exported', 'csv', $filters, $rows->count());

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Tanggal', 'Petani', 'Komoditas', 'Lokasi', 'Jumlah', 'Satuan', 'Kualitas', 'Status', 'Catatan']);

            foreach ($rows as $harvest) {
                fputcsv($handle, [
                    $harvest->harvest_date?->toDateString(),
                    $harvest->user?->name,
                    $harvest->commodity?->nama_komoditas,
                    $harvest->location,
                    (float) $harvest->quantity,
                    $harvest->unit,
                    $harvest->quality,
                    $harvest->status,
                    $harvest->note,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
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

    /**
     * @param  array<string, string|null>  $filters
     */
    private function recordReportActivity(Request $request, string $action, string $format, array $filters, int $rowCount): void
    {
        ActivityLog::record(
            $action,
            "Laporan panen dibuat dalam format {$format}.",
            null,
            ['format' => $format, 'filters' => $filters, 'row_count' => $rowCount],
            $request->user(),
            $request
        );
    }

    private function ensureAdminRequest(User $user): void
    {
        if ($user->role !== 'admin' || $user->isActive()) {
            abort(404);
        }
    }

    private function ensureUserBelongsToOrganization(Request $request, User $user): void
    {
        if ($user->organization_id !== $request->user()->organization_id) {
            abort(404);
        }
    }

    private function ensureModelBelongsToOrganization(Request $request, object $model): void
    {
        if (($model->organization_id ?? null) !== $request->user()->organization_id) {
            abort(404);
        }
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

    private function monthlyHarvestTrend(int $organizationId): array
    {
        $rows = collect(range(5, 0))->map(function (int $monthsAgo) use ($organizationId): array {
            $month = now()->subMonths($monthsAgo);

            return [
                'label' => $month->translatedFormat('M'),
                'quantity' => (float) HarvestLog::forOrganization($organizationId)
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

    private function harvestDistribution(int $organizationId): array
    {
        $rows = HarvestLog::query()
            ->forOrganization($organizationId)
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
