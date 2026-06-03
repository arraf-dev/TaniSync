<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyPrice extends Model
{
    protected $table = 'harga_bapok_harian';

    protected $fillable = [
        'organization_id',
        'id_pasar',
        'tanggal',
        'data_harga',
        'status',
        'created_by',
    ];

    protected $casts = [
        'data_harga' => 'array',
        'tanggal' => 'date',
    ];

    public function scopeForOrganization(Builder $query, ?int $organizationId): Builder
    {
        return $query->where('organization_id', $organizationId);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'id_pasar');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DailyPriceItem::class);
    }
}
