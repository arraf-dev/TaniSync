<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addOrganizationColumn('users', 'village');
        $this->addOrganizationColumn('komoditas', 'id');
        $this->addOrganizationColumn('pasar', 'id');
        $this->addOrganizationColumn('harga_bapok_harian', 'id');
        $this->addOrganizationColumn('catatan_panen', 'id');
        $this->addOrganizationColumn('activity_logs', 'id');

        $hasExistingData = collect(['users', 'komoditas', 'pasar', 'harga_bapok_harian', 'catatan_panen', 'activity_logs'])
            ->contains(fn (string $table): bool => Schema::hasTable($table) && DB::table($table)->exists());

        if ($hasExistingData && DB::table('organizations')->doesntExist()) {
            $now = now();
            $organizationId = DB::table('organizations')->insertGetId([
                'name' => 'Desa Sukamaju',
                'slug' => 'desa-sukamaju',
                'type' => 'desa',
                'region' => 'Sukamaju',
                'address' => 'Desa Sukamaju',
                'status' => 'active',
                'approved_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach (['users', 'komoditas', 'pasar', 'harga_bapok_harian', 'catatan_panen', 'activity_logs'] as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)
                        ->whereNull('organization_id')
                        ->update(['organization_id' => $organizationId]);
                }
            }
        }
    }

    public function down(): void
    {
        foreach (['activity_logs', 'catatan_panen', 'harga_bapok_harian', 'pasar', 'komoditas', 'users'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'organization_id')) {
                Schema::table($table, function (Blueprint $table): void {
                    $table->dropConstrainedForeignId('organization_id');
                });
            }
        }
    }

    private function addOrganizationColumn(string $tableName, string $after): void
    {
        if (! Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'organization_id')) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($after): void {
            $table->foreignId('organization_id')->nullable()->after($after)->constrained('organizations')->nullOnDelete();
        });
    }
};
