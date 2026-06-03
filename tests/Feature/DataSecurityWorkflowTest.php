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

class DataSecurityWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_farmer_registration_is_active_immediately(): void
    {
        $organization = Organization::factory()->create(['name' => 'Gapoktan Sukamaju']);

        $response = $this->post('/register', [
            'name' => 'Petani Baru',
            'organization_id' => $organization->id,
            'role' => 'petani',
            'email' => 'petani-baru@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'petani-baru@example.com')->first();

        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->isActive());
        $response->assertRedirect(route('petani.dashboard', absolute: false));
    }

    public function test_admin_registration_requires_approval(): void
    {
        $response = $this->post('/register', [
            'name' => 'Calon Admin',
            'organization_name' => 'Gapoktan Baru',
            'organization_type' => 'gapoktan',
            'region' => 'Sukamaju',
            'address' => 'Jl. Sawah',
            'role' => 'admin',
            'email' => 'calon-admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'calon-admin@example.com')->first();

        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->isPendingApproval());
        $response->assertRedirect(route('account.pending', absolute: false));
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'organization_requested',
            'subject_id' => $user->id,
        ]);
        $this->assertDatabaseHas('organizations', [
            'name' => 'Gapoktan Baru',
            'status' => 'pending',
        ]);
    }

    public function test_pending_admin_cannot_access_admin_area(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'account_status' => 'pending',
            'approved_at' => null,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('account.pending'));
    }

    public function test_active_admin_can_approve_and_reject_admin_requests(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        $pending = User::factory()->create(['organization_id' => $admin->organization_id, 'role' => 'admin', 'account_status' => 'pending', 'approved_at' => null]);
        $rejected = User::factory()->create(['organization_id' => $admin->organization_id, 'role' => 'admin', 'account_status' => 'pending', 'approved_at' => null]);

        $this->actingAs($admin)
            ->patch(route('admin.access-requests.approve', $pending))
            ->assertRedirect(route('admin.access-requests'));

        $this->assertTrue($pending->fresh()->isActive());
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'admin_access_approved',
            'subject_id' => $pending->id,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.access-requests.reject', $rejected))
            ->assertRedirect(route('admin.access-requests'));

        $this->assertTrue($rejected->fresh()->isRejected());
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'admin_access_rejected',
            'subject_id' => $rejected->id,
        ]);
    }

    public function test_active_admin_cannot_approve_or_reject_active_admin_accounts(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);

        $this->actingAs($admin)
            ->patch(route('admin.access-requests.approve', $admin))
            ->assertNotFound();

        $this->actingAs($admin)
            ->patch(route('admin.access-requests.reject', $admin))
            ->assertNotFound();

        $this->assertTrue($admin->fresh()->isActive());
        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'admin_access_rejected',
            'subject_id' => $admin->id,
        ]);
    }

    public function test_price_submission_requires_valid_active_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'account_status' => 'active']);
        [$commodity, $market] = $this->catalogFixture($admin->organization);

        $this->actingAs($admin)
            ->post(route('admin.prices.store'), [
                'id_pasar' => $market->id,
                'tanggal' => now()->toDateString(),
                'status' => 'verified',
                'prices' => [$commodity->id => null],
            ])
            ->assertSessionHasErrors('prices');

        $this->actingAs($admin)
            ->post(route('admin.prices.store'), [
                'id_pasar' => $market->id,
                'tanggal' => now()->toDateString(),
                'status' => 'verified',
                'prices' => [$commodity->id => -100],
            ])
            ->assertSessionHasErrors('prices.'.$commodity->id);

        $this->actingAs($admin)
            ->post(route('admin.prices.store'), [
                'id_pasar' => $market->id,
                'tanggal' => now()->toDateString(),
                'status' => 'verified',
                'prices' => [$commodity->id => 12500],
            ])
            ->assertRedirect(route('admin.prices'));

        $this->assertDatabaseHas('harga_bapok_harian', [
            'organization_id' => $admin->organization_id,
            'id_pasar' => $market->id,
            'status' => 'verified',
        ]);
        $this->assertDatabaseHas('daily_price_items', [
            'commodity_id' => $commodity->id,
            'price' => 12500,
        ]);
        $this->assertDatabaseHas('activity_logs', ['action' => 'daily_price_saved']);
    }

    public function test_harvest_submission_rejects_future_date_and_inactive_commodity(): void
    {
        $farmer = User::factory()->create(['role' => 'petani']);
        [$commodity] = $this->catalogFixture($farmer->organization);
        $inactive = Commodity::create([
            'organization_id' => $farmer->organization_id,
            'nama_komoditas' => 'Kedelai Nonaktif',
            'satuan' => 'kg',
            'harga_acuan' => 9000,
            'kategori_id' => $commodity->kategori_id,
            'is_active' => false,
        ]);

        $payload = [
            'commodity_id' => $commodity->id,
            'harvest_date' => now()->addDay()->toDateString(),
            'location' => 'Blok Barat',
            'quantity' => 25,
            'unit' => 'kg',
            'quality' => 'Grade A',
            'note' => null,
        ];

        $this->actingAs($farmer)
            ->post(route('petani.harvests.store'), $payload)
            ->assertSessionHasErrors('harvest_date');

        $payload['commodity_id'] = $inactive->id;
        $payload['harvest_date'] = now()->toDateString();

        $this->actingAs($farmer)
            ->post(route('petani.harvests.store'), $payload)
            ->assertSessionHasErrors('commodity_id');
    }

    public function test_farmer_harvest_history_only_shows_own_records(): void
    {
        $farmer = User::factory()->create(['role' => 'petani', 'name' => 'Petani Utama']);
        $otherFarmer = User::factory()->create(['organization_id' => $farmer->organization_id, 'role' => 'petani', 'name' => 'Petani Lain']);
        [$commodity] = $this->catalogFixture($farmer->organization);

        HarvestLog::create([
            'organization_id' => $farmer->organization_id,
            'user_id' => $otherFarmer->id,
            'commodity_id' => $commodity->id,
            'harvest_date' => now()->toDateString(),
            'location' => 'Lokasi Rahasia Petani Lain',
            'quantity' => 40,
            'unit' => 'kg',
            'quality' => 'Grade B',
            'status' => 'menunggu',
        ]);

        HarvestLog::create([
            'organization_id' => $farmer->organization_id,
            'user_id' => $farmer->id,
            'commodity_id' => $commodity->id,
            'harvest_date' => now()->toDateString(),
            'location' => 'Blok Milik Sendiri',
            'quantity' => 30,
            'unit' => 'kg',
            'quality' => 'Grade A',
            'status' => 'menunggu',
        ]);

        $this->actingAs($farmer)
            ->get(route('petani.harvests'))
            ->assertOk()
            ->assertSee('Blok Milik Sendiri')
            ->assertDontSee('Lokasi Rahasia Petani Lain');
    }

    public function test_super_admin_can_approve_pending_organization(): void
    {
        $superAdmin = User::factory()->create([
            'organization_id' => null,
            'role' => 'super_admin',
            'account_status' => 'active',
        ]);
        $organization = Organization::factory()->pending()->create(['name' => 'Gapoktan Pending']);
        $admin = User::factory()->create([
            'organization_id' => $organization->id,
            'role' => 'admin',
            'account_status' => 'pending',
            'approved_at' => null,
        ]);

        $this->actingAs($superAdmin)
            ->patch(route('platform.organizations.approve', $organization))
            ->assertRedirect(route('platform.organizations'));

        $this->assertTrue($organization->fresh()->isActive());
        $this->assertTrue($admin->fresh()->isActive());
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'organization_approved',
            'organization_id' => $organization->id,
        ]);
    }

    /**
     * @return array{0: Commodity, 1: Market}
     */
    private function catalogFixture(Organization $organization): array
    {
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

        return [$commodity, $market];
    }
}
