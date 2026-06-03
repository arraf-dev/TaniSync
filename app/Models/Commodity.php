<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commodity extends Model
{
    protected $table = 'komoditas';

    protected $fillable = [
        'organization_id',
        'nama_komoditas',
        'satuan',
        'harga_acuan',
        'kategori_id',
        'image_path',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'harga_acuan' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function scopeForOrganization(Builder $query, ?int $organizationId): Builder
    {
        return $query->where('organization_id', $organizationId);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function harvestLogs(): HasMany
    {
        return $this->hasMany(HarvestLog::class, 'commodity_id');
    }

    public function dailyPriceItems(): HasMany
    {
        return $this->hasMany(DailyPriceItem::class, 'commodity_id');
    }

    public function statusLabel(): string
    {
        return $this->is_active ? 'aktif' : 'nonaktif';
    }
}
