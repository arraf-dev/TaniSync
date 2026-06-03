<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\DailyPrice;
use App\Models\HarvestLog;
use App\Models\Market;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportingAndFilteringWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_admin_can_open_report_print_view(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        $this->reportFixture($admin->organization);

        $this->actingAs($admin)
            ->get(route('admin.reports.print', ['status' => 'terverifikasi']))
            ->assertOk()
            ->assertSee('Laporan Panen Organisasi')
            ->assertSee('Petani A')
            ->assertDontSee('Petani B');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'report_print_opened',
            'user_id' => $admin->id,
        ]);
    }

    public function test_non_active_admins_and_farmers_cannot_export_reports(): void
    {
        $farmer = User::factory()->create(['role' => 'petani']);
        $pendingAdmin = User::factory()->create([
            'role' => 'admin',
            'account_status' => 'pending',
            'approved_at' => null,
        ]);

        $this->actingAs($farmer)
            ->get(route('admin.reports.export-csv'))
            ->assertForbidden();

        $this->actingAs($farmer)
            ->get(route('admin.reports.export-pdf'))
            ->assertForbidden();

        $this->actingAs($farmer)
            ->get(route('admin.reports.export-xlsx'))
            ->assertForbidden();

        $this->actingAs($pendingAdmin)
            ->get(route('admin.reports.export-csv'))
            ->assertRedirect(route('account.pending'));

        $this->actingAs($pendingAdmin)
            ->get(route('admin.reports.export-pdf'))
            ->assertRedirect(route('account.pending'));

        $this->actingAs($pendingAdmin)
            ->get(route('admin.reports.export-xlsx'))
            ->assertRedirect(route('account.pending'));
    }

    public function test_csv_export_downloads_filtered_report_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        $this->reportFixture($admin->organization);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.export-csv', ['status' => 'terverifikasi']));

        $response->assertOk();
        $this->assertStringContainsString('attachment;', (string) $response->headers->get('content-disposition'));
        $this->assertStringContainsString('Petani A', $response->streamedContent());
        $this->assertStringNotContainsString('Petani B', $response->streamedContent());

        $log = ActivityLog::where('action', 'report_csv_exported')->first();
        $this->assertSame($admin->id, $log?->user_id);
        $this->assertSame('csv', $log?->metadata['format']);
        $this->assertSame('terverifikasi', $log?->metadata['filters']['status']);
        $this->assertSame(1, $log?->metadata['row_count']);
    }

    public function test_pdf_export_downloads_filtered_report_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        $this->reportFixture($admin->organization);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.export-pdf', ['status' => 'terverifikasi']));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment;', (string) $response->headers->get('content-disposition'));
        $this->assertStringStartsWith('%PDF', $response->getContent());

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'report_pdf_exported',
            'user_id' => $admin->id,
        ]);
    }

    public function test_xlsx_export_downloads_filtered_report_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        $this->reportFixture($admin->organization);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.export-xlsx', ['status' => 'terverifikasi']));

        $response->assertOk();
        $this->assertStringContainsString(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            (string) $response->headers->get('content-type')
        );
        $this->assertStringContainsString('attachment;', (string) $response->headers->get('content-disposition'));
        $this->assertStringContainsString('.xlsx', (string) $response->headers->get('content-disposition'));
        $this->assertSame('PK', file_get_contents($response->baseResponse->getFile()->getPathname(), false, null, 0, 2));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'report_xlsx_exported',
            'user_id' => $admin->id,
        ]);
    }

    public function test_report_filters_change_visible_results(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        $this->reportFixture($admin->organization);

        $this->actingAs($admin)
            ->get(route('admin.reports', ['status' => 'butuh-review']))
            ->assertOk()
            ->assertSee('Petani B')
            ->assertSee('Blok Selatan')
            ->assertDontSee('Blok Utara');
    }

    public function test_report_filters_reject_invalid_input(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        $this->reportFixture($admin->organization);

        $this->actingAs($admin)
            ->from(route('admin.reports'))
            ->get(route('admin.reports', [
                'date_from' => now()->toDateString(),
                'date_to' => now()->subDay()->toDateString(),
                'status' => 'selesai',
                'search' => str_repeat('a', 121),
            ]))
            ->assertRedirect(route('admin.reports'))
            ->assertSessionHasErrors(['date_to', 'status', 'search']);
    }

    public function test_admin_harvest_pagination_keeps_filter_query(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        [$commodity, , $farmer] = $this->reportFixture($admin->organization);

        for ($i = 0; $i < 11; $i++) {
            HarvestLog::create([
                'organization_id' => $admin->organization_id,
                'user_id' => $farmer->id,
                'commodity_id' => $commodity->id,
                'harvest_date' => now()->subDays($i)->toDateString(),
                'location' => "Lahan Uji {$i}",
                'quantity' => 10 + $i,
                'unit' => 'kg',
                'quality' => 'Grade A',
                'status' => 'menunggu',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.harvests', ['status' => 'menunggu']))
            ->assertOk()
            ->assertSee('status=menunggu', false);
    }

    public function test_farmer_history_search_only_shows_own_records(): void
    {
        [$commodity, , $farmer, $otherFarmer] = $this->reportFixture();

        HarvestLog::create([
            'organization_id' => $farmer->organization_id,
            'user_id' => $farmer->id,
            'commodity_id' => $commodity->id,
            'harvest_date' => now()->toDateString(),
            'location' => 'Blok Alpha',
            'quantity' => 20,
            'unit' => 'kg',
            'quality' => 'Grade A',
            'status' => 'menunggu',
        ]);

        HarvestLog::create([
            'organization_id' => $farmer->organization_id,
            'user_id' => $otherFarmer->id,
            'commodity_id' => $commodity->id,
            'harvest_date' => now()->toDateString(),
            'location' => 'Blok Alpha Rahasia',
            'quantity' => 40,
            'unit' => 'kg',
            'quality' => 'Grade B',
            'status' => 'menunggu',
        ]);

        $this->actingAs($farmer)
            ->get(route('petani.harvests', ['search' => 'Alpha']))
            ->assertOk()
            ->assertSee('Blok Alpha')
            ->assertDontSee('Blok Alpha Rahasia');
    }

    public function test_commodity_search_filters_results(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        [$commodity] = $this->reportFixture($admin->organization);

        Commodity::create([
            'organization_id' => $admin->organization_id,
            'nama_komoditas' => 'Cabai Merah',
            'satuan' => 'kg',
            'harga_acuan' => 30000,
            'kategori_id' => $commodity->kategori_id,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.commodities', ['search' => 'Cabai']))
            ->assertOk()
            ->assertSee('Cabai Merah')
            ->assertDontSee('Padi Ciherang');
    }

    public function test_price_filters_return_matching_market_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        [$commodity, $market] = $this->reportFixture($admin->organization);
        $otherMarket = Market::create([
            'organization_id' => $admin->organization_id,
            'nama_pasar' => 'Pasar Luar Desa',
            'tipe' => 'tradisional',
            'alamat_lengkap' => 'Jl. Luar Desa',
            'is_active' => true,
        ]);

        DailyPrice::create([
            'organization_id' => $admin->organization_id,
            'id_pasar' => $market->id,
            'tanggal' => now()->toDateString(),
            'data_harga' => [$commodity->id => 12500],
            'status' => 'verified',
            'created_by' => $admin->id,
        ])->items()->create(['commodity_id' => $commodity->id, 'price' => 12500]);

        DailyPrice::create([
            'organization_id' => $admin->organization_id,
            'id_pasar' => $otherMarket->id,
            'tanggal' => now()->subDay()->toDateString(),
            'data_harga' => [$commodity->id => 11000],
            'status' => 'draft',
            'created_by' => $admin->id,
        ])->items()->create(['commodity_id' => $commodity->id, 'price' => 11000]);

        $this->actingAs($admin)
            ->get(route('admin.prices', ['market_id' => $otherMarket->id, 'status' => 'draft']))
            ->assertOk()
            ->assertSee('Pasar Luar Desa')
            ->assertSee('draft')
            ->assertDontSee('Pasar Desa</p>', false);
    }

    public function test_price_filters_do_not_fallback_to_reference_prices(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        [$commodity, $market] = $this->reportFixture($admin->organization);
        $referenceOnlyCommodity = Commodity::create([
            'organization_id' => $admin->organization_id,
            'nama_komoditas' => 'Kedelai Lokal',
            'satuan' => 'kg',
            'harga_acuan' => 9800,
            'kategori_id' => $commodity->kategori_id,
            'is_active' => true,
        ]);

        DailyPrice::create([
            'organization_id' => $admin->organization_id,
            'id_pasar' => $market->id,
            'tanggal' => now()->toDateString(),
            'data_harga' => [$commodity->id => 12500],
            'status' => 'verified',
            'created_by' => $admin->id,
        ])->items()->create(['commodity_id' => $commodity->id, 'price' => 12500]);

        $this->actingAs($admin)
            ->get(route('admin.prices', ['market_id' => $market->id]))
            ->assertOk()
            ->assertSee('Padi Ciherang')
            ->assertDontSee('Harga acuan komoditas');

        $this->actingAs($admin)
            ->get(route('admin.prices', ['status' => 'draft']))
            ->assertOk()
            ->assertSee('Tidak ada harga yang cocok dengan filter.')
            ->assertDontSee('Harga acuan komoditas');

        $this->actingAs($admin)
            ->get(route('admin.prices', ['date_from' => now()->addDay()->toDateString()]))
            ->assertOk()
            ->assertSee('Tidak ada harga yang cocok dengan filter.')
            ->assertDontSee('Harga acuan komoditas');

        $this->actingAs($admin)
            ->get(route('admin.prices'))
            ->assertOk()
            ->assertSee($referenceOnlyCommodity->nama_komoditas)
            ->assertSee('Harga acuan komoditas');
    }

    /**
     * @return array{0: Commodity, 1: Market, 2: User, 3: User}
     */
    private function reportFixture(?Organization $organization = null): array
    {
        $organization ??= Organization::factory()->create();

        $category = Category::create([
            'nama_kategori' => 'Pangan',
            'is_active' => true,
        ]);

        $commodity = Commodity::create([
            'organization_id' => $organization->id,
            'nama_komoditas' => 'Padi Ciherang',
            'satuan' => 'kg',
            'harga_acuan' => 12000,
            'kategori_id' => $category->id,
            'is_active' => true,
        ]);

        $market = Market::create([
            'organization_id' => $organization->id,
            'nama_pasar' => 'Pasar Desa',
            'tipe' => 'tradisional',
            'alamat_lengkap' => 'Jl. Desa',
            'is_active' => true,
        ]);

        $farmerA = User::factory()->create(['organization_id' => $organization->id, 'role' => 'petani', 'name' => 'Petani A']);
        $farmerB = User::factory()->create(['organization_id' => $organization->id, 'role' => 'petani', 'name' => 'Petani B']);

        HarvestLog::create([
            'organization_id' => $organization->id,
            'user_id' => $farmerA->id,
            'commodity_id' => $commodity->id,
            'harvest_date' => now()->subDay()->toDateString(),
            'location' => 'Blok Utara',
            'quantity' => 100,
            'unit' => 'kg',
            'quality' => 'Grade A',
            'status' => 'terverifikasi',
        ]);

        HarvestLog::create([
            'organization_id' => $organization->id,
            'user_id' => $farmerB->id,
            'commodity_id' => $commodity->id,
            'harvest_date' => now()->subDays(2)->toDateString(),
            'location' => 'Blok Selatan',
            'quantity' => 80,
            'unit' => 'kg',
            'quality' => 'Grade B',
            'status' => 'butuh-review',
        ]);

        return [$commodity, $market, $farmerA, $farmerB];
    }
}
