<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    protected $table = 'pasar';

    protected $fillable = [
        'organization_id',
        'nama_pasar',
        'tipe',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'image_pasar',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
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

    public function dailyPrices(): HasMany
    {
        return $this->hasMany(DailyPrice::class, 'id_pasar');
    }
}
